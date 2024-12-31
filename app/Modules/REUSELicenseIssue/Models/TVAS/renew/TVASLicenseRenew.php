<?php


namespace App\Modules\REUSELicenseIssue\Models\TVAS\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\ProposalArea;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\CallCenterRenew;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Users\Models\Countries;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class TVASLicenseRenew extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'tvas_license_renew';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;
    private $tvas_issue_process_type_id = 25;
    private $chairman_desk_id = 1;


    public function createForm( $currentInstance ): string {
        $this->process_type_id   = $currentInstance->process_type_id;
        $data['acl_name']        = $currentInstance->acl_name;
        $data['process_type_id'] = $currentInstance->process_type_id;
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']        = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality']     = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                                 ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( 'REUSELicenseIssue::TVAS.Renew.form', $data ) );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();


        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        $data['process_type_id'] = $process_type_id = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->join( 'tvas_license_master as ms', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->where( 'ms.license_no', $request->get( 'license_no' ) )
                                      ->where( 'ms.status', 1 )
                                      ->first( [
                                          'ms.license_issue_date',
                                          'ms.expiry_date',
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
                                          'apps.per_office_district as op_office_district',
                                          'apps.per_office_thana as op_office_thana',
                                          'apps.per_office_address as op_office_address',
                                          'apps.company_name as org_nm',
                                          'apps.company_type as org_type',
                                      ] );


        if ( empty( $data['appInfo'] ) ) {
            $companyId           = CommonFunction::getUserCompanyWithZero();
            $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
            $data['districts']   = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['division']    = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                                 ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

            $public_html = strval( view( 'REUSELicenseIssue::TVAS.Renew.search-blank', $data ) );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $shareholders_data = Shareholder::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $this->tvas_issue_process_type_id
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


        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $this->tvas_issue_process_type_id
        ] )->get();

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['image']         = ! empty( $value->shareholders_image ) ? CommonFunction::imagePathToBase64( public_path( $item->image ) ) : '';
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $public_html = (string) view( 'REUSELicenseIssue::TVAS.Renew.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $license_no            = $request->get( 'license_no' );

        if ( $request->get( 'actionBtn' ) == 'submit' && empty( $license_no ) ) {
            Session::flash( 'error', 'License no field is required' );
            return redirect()->back()->withInput();
        }

        if ( $request->get( 'app_id' ) ) {
            $appData     = self::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new self();
            $processData = new ProcessList();
        }

        $appData = $this->storeTVASLicenseData( $appData, $request );

        if ( $appData->id ) {
            //dynamic document start
            DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );
            // Contact Person data insert
            CommonFunction::storeContactPerson( $request, $currentInstance->process_type_id, $appData->id, );
            // Share Holder Data insert
            CommonFunction::storeShareHolderPerson( $request, $currentInstance->process_type_id, $appData->id );

            $this->storeProcessListData( $request, $processData, $appData );
        }
        //================================================= payment code =====================================
        $check_payment_type = false;
        if ( ( isset( $request->payment_type ) || $processData->status_id != $this->re_submit_status_id ) ) {
            $unfixed_amount_array = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0
            ];
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];
            $check_payment_type   = ( $request->get( 'payment_type' ) === 'pay_order' );
            $payment_id           = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array, $request ) : '';
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('TVAS', $this->process_type_id, $processData->id, $this->table, 'REN', $appData->id);
        }

        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id != $this->re_submit_status_id) {
//            if ( empty( $processData->tracking_no ) ) {
//                $trackingPrefix = 'TVAS-R-' . date( 'Ymd' ) . '-';
//                CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
//            }
            if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
                DB::commit();
                // redirect to Sonali Payment Portal
                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }
        }

        // Send Email notification to user on application re-submit
        if ( $processData->status_id == $this->re_submit_status_id ) {

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'TVAS License Renew', '{$trackingNumber}' => $trackingNumber], $userMobile);

            //TODO:: send email
            $receiverInfo = [
                array(
                    'user_mobile' => Auth::user()->user_mobile,
                    'user_email' => Auth::user()->user_email
                )
            ];

            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'TVAS License Renew',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        // for Pay Order
        if ( $check_payment_type && $request->get( 'actionBtn' ) == 'submit' ) {
            $unfixed_amount_array = [
                1 => 0,
                2 => $request->get( 'pay_amount' ),
                3 => 0,
                4 => 0,
                5 => $request->get( 'vat_on_pay_amount' ),
                6 => 0
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
            CommonFunction::DNothiRequest($processData->id,$request->get( 'actionBtn' ));

        }

        if ( $processData->status_id == - 1 ) {
            Session::flash( 'success', 'Successfully updated the Application!' );
        } elseif ( $processData->status_id == 1 ) {
            Session::flash( 'success', 'Successfully Application Submitted !' );
        } elseif ( $processData->status_id == 2 ) {
            Session::flash( 'success', 'Successfully Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]' );
        }

        return redirect( 'client/tvas-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {

        $decodedAppId = Encryption::decodeId( $applicationId );

        $data['process_type_id'] = $processTypeId;

        $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $processTypeId ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( $processTypeId ) );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $processTypeId ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $processTypeId ) );
                                      } )
                                      ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
                                      ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
                                      ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
                                      ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.per_office_district' )
                                      ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.per_office_thana' )
                                      ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                                      ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                                      ->where( 'process_list.ref_id', $decodedAppId )
                                      ->where( 'process_list.process_type_id', $processTypeId )
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
                                          'apps.per_office_address as op_office_address',

                                          'applicant_district.area_nm as applicant_district_en',
                                          'applicant_thana.area_nm as applicant_thana_en',

                                          'apps.*',
                                          'apps.company_name as org_nm',
                                          'apps.company_type as org_type',
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

        $data['appShareholderInfo'] = self::Join( 'shareholders', 'shareholders.app_id', '=', 'tvas_license_renew.id' )
                                          ->where( [
                                              'tvas_license_renew.id'        => $decodedAppId,
                                              'shareholders.process_type_id' => $processTypeId
                                          ] )
                                          ->get( [
                                              'shareholders.id as shareholders_id',
                                              'shareholders.app_id as shareholders_ref_id',
                                              'shareholders.name as name',
                                              'shareholders.nationality as nationality',
                                              'shareholders.passport as passport',
                                              'shareholders.nid as nid',
                                              'shareholders.dob as dob',
                                              'shareholders.designation as designation',
                                              'shareholders.mobile as mobile',
                                              'shareholders.email as email',
                                              'shareholders.image as image',
                                              'shareholders.share_percent as share_percent',
                                              'shareholders.no_of_share as no_of_share',
                                              'shareholders.share_value as share_value'
                                          ] );


        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
                                                         ->where( 'ref_id', $decodedAppId )
                                                         ->whereNotNull('uploaded_path')
                                                         ->get();


        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $processTypeId
        ] )->get();

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['contact_upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


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

        $public_html       = (string) view( 'REUSELicenseIssue::TVAS.Renew.view', $data );

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
        $data['appInfo']   = ProcessList::leftJoin( 'tvas_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                                            'apps.per_office_district as op_office_district',
                                            'apps.per_office_thana as op_office_thana',
                                            'apps.per_office_address as op_office_address',
                                            'apps.company_name as org_nm',
                                            'apps.company_type as org_type',
                                            'apps.issue_date as license_issue_date',
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
            $public_html = (string) view( 'REUSELicenseIssue::TVAS.Renew.form-edit', $data );
        } else {
            $public_html = (string) view( 'REUSELicenseIssue::TVAS.Renew.form-edit-v2', $data );
        }

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    private function storeTVASLicenseData( $renewObj, $request ) {
        $renewObj->company_id          = CommonFunction::getUserCompanyWithZero();
        $renewObj->company_name        = $request->get( 'company_name' );
        $renewObj->company_type        = $request->get( 'company_type' );
        $renewObj->issue_tracking_no   = ! empty( $request->get( 'issue_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'issue_tracking_no' ) ) : null;
        $renewObj->license_no          = $request->get( 'license_no', null );
        if(!empty($request->get('issue_date')) && !empty($request->get('expiry_date'))){
            $issue_date = Carbon::createFromFormat('d-M-Y', $request->get('issue_date'));
            $formattedIssueDate = $issue_date->format('Y-m-d');
            $expire_date = Carbon::createFromFormat('d-M-Y', $request->get('expiry_date'));
            $formattedExpireDate = $expire_date->format('Y-m-d');
        }
        if(!empty($request->get('issue_date')) && !empty($request->get('expiry_date'))){
            $renewObj->issue_date         = $formattedIssueDate ?? null;
            $renewObj->expiry_date          = $formattedExpireDate ?? null;
        }
        $renewObj->reg_office_district = $request->get( 'reg_office_district' );
        $renewObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $renewObj->reg_office_address  = $request->get( 'reg_office_address' );
        $renewObj->reg_office_address2  = $request->get( 'reg_office_address2' );

        $renewObj->per_office_district = $request->get( 'op_office_district' );
        $renewObj->per_office_thana    = $request->get( 'op_office_thana' );
        $renewObj->per_office_address  = $request->get( 'op_office_address' );
        $renewObj->per_office_address2  = $request->get( 'op_office_address2' );


        $renewObj->applicant_name      = $request->get( 'applicant_name' );
        $renewObj->applicant_district  = $request->get( 'applicant_district' );
        $renewObj->applicant_thana     = $request->get( 'applicant_thana' );
        $renewObj->applicant_address   = $request->get( 'applicant_address' );
        $renewObj->applicant_address2   = $request->get( 'applicant_address2' );
        $renewObj->applicant_email     = $request->get( 'applicant_email' );
        $renewObj->applicant_website   = $request->get( 'applicant_website' );
        $renewObj->applicant_mobile    = $request->get( 'applicant_mobile' );
        $renewObj->applicant_telephone = $request->get( 'applicant_telephone' );
        $renewObj->total_no_of_share   = $request->get( 'total_no_of_share' );
        $renewObj->total_share_value   = $request->get( 'total_share_value' );

        $renewObj->declaration_q1      = $request->get( 'declaration_q1' );
        $renewObj->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $renewObj->q1_application_date = ! empty( $request->get( 'declaration_q1_application_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'declaration_q1_application_date' ) ) ) : null;
        $renewObj->declaration_q2      = $request->get( 'declaration_q2' );
        $renewObj->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $renewObj->declaration_q3      = $request->get( 'declaration_q3' );
        $renewObj->declaration_q3_text = $request->get( 'declaration_q3_text' );
        $renewObj->q3_application_date = ! empty( $request->get( 'declaration_q3_application_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'declaration_q3_application_date' ) ) ) : null;
        $renewObj->declaration_q4      = $request->get( 'declaration_q4' );
        $renewObj->q4_license_number   = $request->get( 'q4_license_number' );
        $renewObj->q4_case_no          = $request->get( 'q4_case_no' );
        $renewObj->q4_amount           = $request->get( 'q4_amount' );
        $renewObj->q4_bank_draft_no    = $request->get( 'q4_bank_draft_no' );
        $renewObj->q4_given_comission  = $request->get( 'q4_given_comission' );
        $renewObj->status              = 1;

        $renewObj->updated_at          = Carbon::now();

        //Updated Information for Resubmit application
        //trade file
        $renewObj->trade_license_number          = $request->get('trade_license_number');
        $renewObj->trade_validity                = $request->get('trade_validity');
        $renewObj->trade_address                 = $request->get('trade_address');
        $renewObj->tax_clearance                 = $request->get('tax_clearance');
        $renewObj->current_trade_license_number  = $request->get('current_trade_license_number');
        $renewObj->current_tax_clearance         = $request->get('current_tax_clearance');
        $renewObj->current_trade_validity        = $request->get('current_trade_validity');
        $renewObj->current_trade_address         = $request->get('current_trade_address');

        if ( $request->hasFile( 'trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->tax_clearance_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->current_trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->current_tax_clearance_attachment = $path . $simple_file_name;
        }


        //House rental
        $renewObj->house_rental_address              = $request->get('house_rental_address');
        $renewObj->house_rental_validity             = $request->get('house_rental_validity');
        $renewObj->current_house_rental_address      = $request->get('current_house_rental_address');
        $renewObj->current_house_rental_validity     = $request->get('current_house_rental_validity');


        if ( $request->hasFile( 'house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->house_rental_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->current_house_rental_attachment = $path . $simple_file_name;
        }

        //ISPAB
        $renewObj->ispab_validity                = $request->get('ispab_validity');
        $renewObj->current_ispab_validity        = $request->get('current_ispab_validity');

        if ( $request->hasFile( 'ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'ispab_attachment' );
            $simple_file_name = trim( uniqid( 'ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->ispab_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_ispab_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->current_ispab_attachment = $path . $simple_file_name;
        }

        //Shareholder
        $renewObj->number_of_share                   = $request->get('number_of_share');
        $renewObj->shareholders_name                 = $request->get('shareholders_name');
        $renewObj->shareholders_nid_passport         = $request->get('shareholders_nid_passport');
        $renewObj->current_number_of_share           = $request->get('current_number_of_share');
        $renewObj->current_shareholders_name         = $request->get('current_shareholders_name');
        $renewObj->current_shareholders_nid_passport = $request->get('current_shareholders_nid_passport');
        if ( $request->hasFile( 'shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->shareholders_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $renewObj->current_shareholders_attachment = $path . $simple_file_name;
        }
        $renewObj->save();

        return $renewObj;
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
            $processListObj->status_id = $this->submitted_status_id;
            $processListObj->desk_id   = $this->chairman_desk_id;
        }

        $processListObj->ref_id          = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->license_no      = $appData->license_no;
        $processListObj->office_id       = 0;
        $jsonData['Applicant Name']      = Auth::user()->user_first_name;
        $jsonData['Company Name']        = $request->company_name;
        $jsonData['Email']               = Auth::user()->user_email;
        $jsonData['Phone']               = Auth::user()->user_mobile;
        $processListObj['json_object']   = json_encode( $jsonData );
        $processListObj->submitted_at    = Carbon::now();
        $processListObj->save();

        return $processListObj;

    }
}
