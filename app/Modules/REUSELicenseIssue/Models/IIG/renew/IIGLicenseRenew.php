<?php

namespace App\Modules\REUSELicenseIssue\Models\IIG\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;

use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\IIG\issue\IIGLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class IIGLicenseRenew extends Model implements FormInterface {

    protected $table = 'iig_license_renew';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $issue_process_type_id = 17;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;

    use SPPaymentManager;
    use SPAfterPaymentManager;

    public function createForm( $currentInstance ): string {
        $this->process_type_id = $currentInstance->process_type_id;
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $currentInstance->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $currentInstance->process_type_id )->value( 'name' );
        $data['districts']        = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']         = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['bank_list']        = Bank::orderBy( 'name' )->where( 'is_active', 1 )->pluck( 'name', 'id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( 'REUSELicenseIssue::IIG.Renew.form', $data ) );

    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {


        $this->process_type_id = $currentInstance->process_type_id;
        $app_id                = $request->get( 'app_id' );

        if ( $request->get( 'app_id' ) ) {
            $appData     = IIGLicenseRenew::find( Encryption::decodeId( $app_id ) );
            $processData = ProcessList::where( [
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new IIGLicenseRenew();
            $processData = new ProcessList();
        }

        $appData = $this->storeLicenseData( $appData, $request );

        if ( $appData->id ) {
            // Store ShareHolder Person
            if ( intval( $request->get( 'shareholderDataCount' ) ) ) {
                CommonFunction::storeShareHolderPerson( $request, $this->process_type_id, $appData->id );
            }

            // Store Contact Person
            CommonFunction::storeContactPerson( $request, $this->process_type_id, $appData->id );


            //Dynamic Document Store
            DocumentsController::storeAppDocuments( $currentInstance->process_type_id, $request->doc_type_key, $appData->id, $request );

            // Store Process list Data
            $processData = $this->storeProcessListData( $request, $processData, $appData );


        }

        //  Required Documents for attachment
        DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );

        //=================================================payment code==========================
        $check_payment_type = false;
        if ( ( isset( $request->payment_type ) || $processData->status_id != $this->re_submit_status_id ) && ! empty( $appData->isp_license_type ) ) {
            $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $appData->isp_license_type, 1 );
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];
            $check_payment_type   = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );
            $payment_id           = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array ) : '';
        }

        /** if application submitted and status is equal to draft then generate tracking number and payment initiate  ***/
        if ( in_array($request->get('actionBtn'), ['draft', 'submit'])) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'IIG-' . date( 'Ymd' ) . '-';
                CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
            }
            if ( $request->get( 'payment_type' ) == 'pay_order' ) {
                DB::commit();

                // redirect to Sonali Payment Portal
                // return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }
        }


        // Send Email for application re-submission
        if ( $processData->status_id == $this->re_submit_status_id ) {
            CommonFunction::sendEmailForReSubmission( $processData );
        }

        // for Pay Order
        if ( $check_payment_type ) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $request->get( 'pay_amount' ), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => $request->get( 'vat_on_pay_amount' ), // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];
            $this->storeSubmissionFeeDataV2( $appData->id, 1, $contact_info, $unfixed_amount_array, $request );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id, $request->get('actionBtn'));

        }

        if ( $processData->status_id == $this->draft_status_id ) {
            Session::flash( 'success', 'Successfully updated the Application!' );
        } elseif ( $processData->status_id == $this->submitted_status_id ) {
            Session::flash( 'success', 'Successfully Application Submitted !' );
        } elseif ( $processData->status_id == $this->re_submit_status_id ) {
            Session::flash( 'success', 'Successfully Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]' );
        }

        return redirect( 'client/iig-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $appId ): JsonResponse {
        $this->process_type_id = $processTypeId;
        $decodedAppId          = Encryption::decodeId( $appId );
        $process_type_id       = $this->process_type_id;

        $data['process_type_id'] = $process_type_id;


        $data['appInfo'] = ProcessList::leftJoin( 'iig_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'pay_order_payment as pop', function ( $join ) use ( $process_type_id ) {
                $join->on( 'pop.app_id', '=', 'process_list.ref_id' );
                $join->on( 'pop.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
            ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
            //   ->leftJoin( 'area_info as contact_district', 'contact_district.area_id', '=', 'apps.cntct_prsn_district' )
            //   ->leftJoin( 'area_info as contact_thana', 'contact_thana.area_id', '=', 'apps.cntct_prsn_upazila' )
            //   ->leftJoin( 'area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division' )
            //   ->leftJoin( 'area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district' )
            //   ->leftJoin( 'area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila' )
            //   ->leftJoin( 'area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district' )
            //  ->leftJoin( 'area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana' )
            ->where( 'process_list.ref_id', $decodedAppId )
            ->where( 'process_list.process_type_id', $process_type_id )
            ->first( [
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.submitted_at',
                'ps.status_name',
                'process_type.form_url',
                'reg_office_district.area_nm as reg_office_district_en',
                'reg_office_thana.area_nm as reg_office_thana_en',
                'op_office_district.area_nm as op_office_district_en',
                'op_office_thana.area_nm as op_office_thana_en',
                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',
                //   'contact_district.area_nm as contact_dis_nm',
                //   'contact_thana.area_nm as contact_thana_nm',
                'apps.*',

                //   'isp_license_division_info.area_nm as isp_license_division',
                //   'isp_license_district_info.area_nm as isp_license_district',
                //   'isp_license_upazila_info.area_nm as isp_license_upazila',

                //   'location_of_ins_district.area_nm as location_of_ins_district_en',
                //  'location_of_ins_thana.area_nm as location_of_ins_thana_en',

                'sfp.contact_name as sfp_contact_name',
                'sfp.contact_email as sfp_contact_email',
                'sfp.contact_no as sfp_contact_phone',
                'sfp.address as sfp_contact_address',
                'sfp.pay_amount as sfp_pay_amount',
                'sfp.vat_on_pay_amount as sfp_vat_tax',
                'sfp.transaction_charge_amount as sfp_bank_charge',
                'sfp.payment_status as sfp_payment_status',
                'sfp.pay_mode as pay_mode',
                'sfp.pay_mode_code as pay_mode_code',
                'sfp.total_amount as sfp_total_amount',
                'pop.contact_name as pop_name',
                'pop.contact_email as pop_email',
                'pop.contact_no as pop_mobile',
                'pop.address as pop_address',
            ] );


        $data['appShareholderInfo'] = Shareholder::where( [ 'app_id' => $decodedAppId, 'process_type_id' => $this->process_type_id ] )->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where( 'process_type_id', $process_type_id )->where( 'ref_id', $decodedAppId )->whereNotNull('uploaded_path')->get();
        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $processTypeId
        ] )->get();

        foreach ( $data['contact_person'] as $key => $item ) {

            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        // if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
        //     $data['payment_step_id'] = 2;
        //     $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'] );
        // } elseif ( $data['appInfo']->status_id == 16 ) { // 16 = Approved for annual payment
        //     $data['payment_step_id'] = 3;
        //     $data['unfixed_amounts'] = $this->unfixedAmountsForGovtApplicationFee( $data['appInfo']->isp_license_type, $data['payment_step_id'] );
        // } elseif ( $data['appInfo']->status_id == 46 ) {
        //     $data['payment_step_id']                         = 2;
        //     $data['unfixed_amounts']                         = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'] );
        //     $data['pay_order_info']                          = DB::table( 'pay_order_payment' )
        //                                                          ->where( [
        //                                                              'app_id'          => $data['appInfo']['id'],
        //                                                              'payment_step_id' => 2
        //                                                          ] )->first();
        //     $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
        //     $data['pay_order_info']->bg_expire_formated_date = date_format( date_create( $data['pay_order_info']->bg_expire_date ), 'Y-m-d' );
        // }
        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
            $data['payment_step_id'] = 2;
            $data['unfixed_amounts'] = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
        }
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string) view( 'REUSELicenseIssue::IIG.Renew.view', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm( $processTypeId, $applicationId ): JsonResponse {
        $this->process_type_id   = $processTypeId;
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $applicationId     = Encryption::decodeId( $applicationId );
        $process_type_id   = $this->process_type_id;
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana'] = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['appInfo']   = ProcessList::leftJoin( 'iig_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->where( 'process_list.ref_id', $applicationId )
            ->where( 'process_list.process_type_id', $process_type_id )
            ->first( [
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.locked_by',
                'process_list.locked_at',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.submitted_at',
                'ps.status_name',
                'ps.color',
                'apps.*',
                'apps.op_office_district as op_office_district',
                'apps.op_office_thana as op_office_thana',
//                'apps.per_office_address as op_office_address',
                'apps.org_nm as org_nm',
                'apps.org_type as org_type',
                'apps.license_issue_date as license_issue_date',
                'sfp.contact_name as sfp_contact_name',
                'sfp.contact_email as sfp_contact_email',
                'sfp.contact_no as sfp_contact_phone',
                'sfp.address as sfp_contact_address',
                'sfp.pay_amount as sfp_pay_amount',
                'sfp.vat_on_pay_amount as sfp_vat_tax',
                'sfp.transaction_charge_amount as sfp_bank_charge',
                'sfp.payment_status as sfp_payment_status',
                'sfp.pay_mode as pay_mode',
                'sfp.pay_mode_code as pay_mode_code',
                'sfp.total_amount as sfp_total_amount',
            ] );


        // Share Holder Person
        $shareholders_data = Shareholder::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $this->process_type_id
        ] )->get( [
            'shareholders.id as shareholders_id',
            'shareholders.app_id as app_id',
            'shareholders.name as shareholders_name',
            'shareholders.nationality as shareholders_nationality',
            'shareholders.passport as shareholders_passport',
            'shareholders.nid as shareholders_nid',
            'shareholders.dob as shareholders_dob',
            'shareholders.designation as shareholders_designation',
            'shareholders.mobile as shareholders_mobile',
            'shareholders.email as shareholders_email',
            'shareholders.image as shareholders_image',
            'shareholders.share_percent as shareholders_share_percent',
            'shareholders.no_of_share as no_of_share',
            'shareholders.share_value as share_value',
        ] );

        foreach ( $shareholders_data as $index => $value ) {
            if ( public_path( $value->shareholders_image ) && ! empty( $value->shareholders_image ) ) {
                $value->image_real_path    = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;


        // Contact Person
        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $this->process_type_id
        ] )->get();
        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['image']         = ! empty( $item->image ) ? CommonFunction::imagePathToBase64( public_path( $item->image ) ) : '';
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
        if ( $data['appInfo']->issue_tracking_no ) {
            $public_html = (string) view( 'REUSELicenseIssue::IIG.Renew.form-edit', $data );
        } else {
            $public_html = (string) view( 'REUSELicenseIssue::IIG.Renew.form-edit-v2', $data );
        }

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData($request, $currentInstance): JsonResponse
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $data['license_no']      = $request->license_no;
        $data['process_type_id'] = $currentInstance->process_type_id;
        $issue_company_id      = IIGLicenseIssue::where('license_no', $request->license_no)->value('company_id');

        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();
        if (empty($data['license_no'])) {
            return response()->json(['responseCode' => -1, 'msg' => 'Please provide valid license no']);
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }


        $data['vat_percentage'] = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();


        $data['appInfo'] = ProcessList::leftJoin('iig_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('iig_license_master as ms', function ($join) {
                $join->on('ms.issue_tracking_no', '=', 'apps.tracking_no');
            })
            ->leftJoin('process_status as ps', function ($join) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($this->issue_process_type_id));
            })
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
            ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
            ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.per_office_district')
            ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.per_office_thana')
            ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
            ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
            ->where('ms.license_no', $request->license_no)
            ->where('ms.status', 1)// approved status can be renew
            ->where( 'process_list.process_type_id', 17 )
            ->first([
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.submitted_at',
                'ps.status_name',
                'apps.*',
                'apps.per_office_district as op_office_district',
                'apps.per_office_thana as op_office_thana',
                'apps.per_office_address as op_office_address',
                'apps.company_name as org_nm',
                'apps.company_type as org_type',
            ]);

        if (empty($data['appInfo'])) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Data not found on provided license number' ] );
            // $companyId                    = CommonFunction::getUserCompanyWithZero();
            // $data['companyInfo']          = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();
            // $data['acl_name']             = $currentInstance->acl_name;
            // $data['existing_isp_license'] = DB::table('iig_license_issue')
            //     ->where('company_id', auth()->user()->working_company_id)
            //     ->whereNotNull('license_no')
            //     ->first([
            //         'license_no',
            //         'expiry_date',
            //     ]);

            // $data['application_type'] = ProcessType::Where('id', $this->issue_process_type_id)->value('name');
            // $data['districts']        = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
            // $data['division']         = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

            // $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
            //         ->orderby('nationality')->pluck('nationality', 'id')->toArray();
            // $public_html         = strval(view('REUSELicenseIssue::IIG.Renew.search-blank', $data));
            // return response()->json(['responseCode' => 1, 'html' => $public_html]);
        }
        $shareholders_data = Shareholder::where([
            'app_id' => $data['appInfo']['id'],
            'process_type_id' => $this->issue_process_type_id
        ])->get([
            'shareholders.id as shareholders_id',
            'shareholders.app_id as shareholders_isp_issue_id',
            'shareholders.name as shareholders_name',
            'shareholders.nid as shareholders_nid',
            'shareholders.dob as shareholders_dob',
            'shareholders.nationality as shareholders_nationality',
            'shareholders.passport as shareholders_passport',
            'shareholders.designation as shareholders_designation',
            'shareholders.mobile as shareholders_mobile',
            'shareholders.email as shareholders_email',
            'shareholders.image as shareholders_image',
            'shareholders.no_of_share',
            'shareholders.share_value',
            'shareholders.share_percent as shareholders_share_percent'
        ]);
        foreach ($shareholders_data as $index => $value) {
            if (public_path($value->shareholders_image) && !empty($value->shareholders_image)) {
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data              = ContactPerson::where([
            'app_id' => $data['appInfo']['id'],
            'process_type_id' => $this->issue_process_type_id
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }

        $data['contact_person'] = $contact_data;


        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['contact_upazila_name']  = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        if ($data['appInfo']->status_id == 15) { // 15 = Approved for license payment
            $data['payment_step_id'] = 2;
            $data['unfixed_amounts'] = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
        }

        $data['districts']      = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['divisions']      = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['nationality']    = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        $data['pay_order_info'] = DB::table('pay_order_payment')
            ->where([
                'app_id' => $data['appInfo']['id'],
                'process_type_id' => $currentInstance->process_type_id,
                'payment_step_id' => 1
            ])->first();
        if (!empty($data['pay_order_info']->pay_order_date)) {
            $data['pay_order_info']->pay_order_formated_date = date_format(date_create($data['pay_order_info']->pay_order_date), 'Y-m-d');
        }
        if (!empty($data['pay_order_info']->bg_expire_date)) {
            $data['pay_order_info']->bg_expire_formated_date = date_format(date_create($data['pay_order_info']->bg_expire_date), 'Y-m-d');
        }

        $public_html = (string)view('REUSELicenseIssue::IIG.Renew.search', $data);


        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    private function storeLicenseData( $LicenseIssueObj, $request ) {

        $LicenseIssueObj->org_nm   = $request->get( 'company_name' );
        $LicenseIssueObj->org_type = $request->get( 'company_type' );

        $LicenseIssueObj->reg_office_district = $request->get( 'reg_office_district' );
        $LicenseIssueObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $LicenseIssueObj->reg_office_address  = $request->get( 'reg_office_address' );
        $LicenseIssueObj->op_office_district  = $request->get( 'op_office_district' );
        $LicenseIssueObj->op_office_thana     = $request->get( 'op_office_thana' );
        $LicenseIssueObj->op_office_address   = $request->get( 'op_office_address' );

        $LicenseIssueObj->applicant_name      = $request->get( 'applicant_name' );
        $LicenseIssueObj->applicant_mobile    = $request->get( 'applicant_mobile' );
        $LicenseIssueObj->applicant_telephone = $request->get( 'applicant_telephone' );
        $LicenseIssueObj->applicant_email     = $request->get( 'applicant_email' );
        $LicenseIssueObj->applicant_district  = $request->get( 'applicant_district' );
        $LicenseIssueObj->applicant_thana     = $request->get( 'applicant_thana' );
        $LicenseIssueObj->applicant_address   = $request->get( 'applicant_address' );


        $LicenseIssueObj->status              = 1;
        $LicenseIssueObj->updated_at          = Carbon::now();
        $LicenseIssueObj->company_id          = CommonFunction::getUserCompanyWithZero();
        $LicenseIssueObj->total_no_of_share   = $request->get( 'total_no_of_share' );
        $LicenseIssueObj->license_no        = $request->get( 'license_no' );
        $LicenseIssueObj->license_issue_date       = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $LicenseIssueObj->expiry_date              = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;
        $LicenseIssueObj->total_share_value   = $request->get( 'total_share_value' );
        $LicenseIssueObj->save();

        return $LicenseIssueObj;
    }

    private function storeProcessListData( $request, $processListObj, $appData ) {
        $processListObj->company_id = CommonFunction::getUserCompanyWithZero();

        //Set category id for process differentiation
        $processListObj->cat_id = 1;
        if ( $request->get( 'actionBtn' ) === 'draft' ) {
            $processListObj->status_id = $this->draft_status_id;
            $processListObj->desk_id   = 0;
        } elseif ( $processListObj->status_id === $this->shortfall_status_id ) {
            // For shortfall
            $submission_sql_param = [
                'app_id'          => $appData->id,
                'process_type_id' => $this->process_type_id,
            ];

            $process_type_info = ProcessType::where( 'id', $this->process_type_id )
                ->orderBy( 'id', 'desc' )
                ->first( [
                    'form_url',
                    'process_type.process_desk_status_json',
                    'process_type.name'
                ] );

            $resubmission_data              = $this->getProcessDeskStatus( 'resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param );
            $processListObj->status_id      = $resubmission_data['process_starting_status'];
            $processListObj->desk_id        = $resubmission_data['process_starting_desk'];
            $processListObj->process_desc   = 'Re-submitted form applicant';
            $processListObj->resubmitted_at = Carbon::now(); // application resubmission Date

            $resultData = "{$processListObj->id}-{$processListObj->tracking_no}{$processListObj->desk_id}-{$processListObj->status_id}-{$processListObj->user_id}-{$processListObj->updated_by}";

            $processListObj->previous_hash = $processListObj->hash_value ?? '';
            $processListObj->hash_value    = Encryption::encode( $resultData );

        } else {
            $processListObj->status_id = 1;
            $processListObj->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
        }

        $processListObj->ref_id          = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->office_id       = 0;
        $jsonData['Applicant Name']      = Auth::user()->user_first_name;
        $jsonData['Company Name']        = $request->company_org_name;
        $jsonData['Email']               = Auth::user()->user_email;
        $jsonData['Phone']               = Auth::user()->user_mobile;
        $processListObj['json_object']   = json_encode( $jsonData );
        $processListObj->save();

        return $processListObj;

    }

    // private function unfixedAmountsForGovtServiceFee( $isp_license_type, $payment_step_id ) {
    //     $vat_percentage = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
    //     if ( empty( $vat_percentage ) ) {
    //         DB::rollback();
    //         Session::flash( 'error', 'Please, configure the value for VAT.[INR-1026]' );

    //         return redirect()->back()->withInput();
    //     }

    //     $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
    //         'process_type_id' => $this->process_type_id,
    //         'payment_step_id' => $payment_step_id,
    //         'license_type_id' => $isp_license_type,
    //         'status'          => 1,
    //     ] )->first();

    //     $unfixed_amount_array = [
    //         1 => 0, // Vendor-Service-Fee
    //         2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
    //         3 => 0, // Govt. Application Fee
    //         4 => 0, // Vendor-Vat-Fee
    //         5 => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
    //         6 => 0 //govt-vendor-vat-fee
    //     ];

    //     return $unfixed_amount_array;
    // }

}
