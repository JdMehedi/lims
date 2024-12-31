<?php


namespace App\Modules\REUSELicenseIssue\Models\SS\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\SS\issue\SSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SS\SSLicenseMaster;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\SSLicenseIssue\Models\SSLicenseContactPerson;
use App\Modules\SSLicenseIssue\Models\SSLicenseIssueShareholder;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class SSLicenseRenew extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'ss_license_renew';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $ss_issue_process_type_id = 78;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;


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
        $data['process_type_id'] = $process_type_id = $this->process_type_id;

        return strval( view( 'REUSELicenseIssue::SS.Renew.form', $data ) );
    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $license_no            = $request->get( 'license_no' );
        if ( empty( $license_no ) ) {
            Session::flash( 'error', 'Invalid License No [SSR-006]' );

            return redirect()->back()->withInput();
        }

        if ( $request->get( 'app_id' ) ) {
            $appData     = self::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new SSLicenseRenew();
            $processData = new ProcessList();
        }


        /** Fetch ISP License Issue existing data and prepare them for renew application */
        $this->fetchAndPrprIssueData( $license_no, $appData, $request );

        /** Prepare new additional data for renew application */
        $this->prprRenewData( $request, $appData, $license_no );
        $appData->save();

        if ( $appData->id ) {

            /** Store data to Share Holder Data */
            $this->storeShareHolderData( $license_no, $appData->id, $request );

            /** Store data to Contact Person Data */
            $this->storeContactPersonData( $license_no, $appData->id, $request );

            ## dynamic document start
            DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );
            ## dynamic document end

            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation
            $processData->cat_id = 1;
            if ( $request->get( 'actionBtn' ) == 'draft' ) {
                $processData->status_id = - 1;
                $processData->desk_id   = 0;
            } else {
                if ( $processData->status_id == 5 ) { // For shortfall
                    // Get last desk and status
                    $submission_sql_param        = [
                        'app_id'          => $appData->id,
                        'process_type_id' => $this->process_type_id,
                    ];
                    $process_type_info           = ProcessType::where( 'id', $this->process_type_id )
                        ->orderBy( 'id', 'desc' )
                        ->first( [
                            'form_url',
                            'process_type.process_desk_status_json',
                            'process_type.name',
                        ] );
                    $resubmission_data           = $this->getProcessDeskStatus( 'resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param );
                    $processData->status_id      = $resubmission_data['process_starting_status'];
                    $processData->desk_id        = $resubmission_data['process_starting_desk'];
                    $processData->process_desc   = 'Re-submitted form applicant';
                    $processData->resubmitted_at = Carbon::now(); // application resubmission Date

                    $resultData = $processData->id . '-' . $processData->tracking_no .
                        $processData->desk_id . '-' . $processData->status_id . '-' . $processData->user_id . '-' .
                        $processData->updated_by;

                    $processData->previous_hash = $processData->hash_value ?? '';
                    $processData->hash_value    = Encryption::encode( $resultData );
                } else {
                    $processData->status_id = 1;
                    $processData->submitted_at = Carbon::now();
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
                }
            }

            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            // need to change
            $jsonData['Company Name'] = $appData->org_nm;
            #$jsonData['Office name'] = CommonFunction::getBSCICOfficeName($request->get('pref_reg_office'));
            $jsonData['Email']          = Auth::user()->user_email;
            $jsonData['Phone']          = Auth::user()->user_mobile;
            $processData['json_object'] = json_encode( $jsonData );

            $processData->save();
            //process list data insert
        }

        // =================================================payment code==========================
        // Payment info will not be updated for resubmit
        if ( $processData->status_id != 2 && ! empty( $appData->isp_license_type ) ) {
            $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $appData->isp_license_type, 1 );
            $contact_info         = [
                'contact_name'    => $request->contact_name,
                'contact_email'   => $request->contact_email,
                'contact_no'      => $request->contact_no,
                'contact_address' => $request->contact_address,
            ];
            if(isset($unfixed_amount_array)){
                $payment_id           = $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array );
            }

        }
        /*
         * if application submitted and status is equal to draft then
         * generate tracking number and payment initiate
         */
        if ( empty( $processData->tracking_no ) ) {
            $trackingPrefix = 'SSR-' . date( 'Ymd' ) . '-';
            CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
        }
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == - 1 && isset($payment_id)) {
            DB::commit();

            return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'ISP License Renew',
                'remarks'           => '',
            ];

            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id);

        }

        if ( $processData->status_id == - 1 ) {
            Session::flash( 'success', 'Successfully updated the Renew Application!' );
        } elseif ( $processData->status_id == 1 ) {
            Session::flash( 'success', 'Successfully Renew Application Submitted !' );
        } elseif ( $processData->status_id == 2 ) {
            Session::flash( 'success', 'Successfully Renew Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [ISPR-007]' );
        }

        return redirect( '/ss-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {

        $decodedAppId = Encryption::decodeId( $applicationId );

        $data['process_type_id'] = $processTypeId;

        $data['appInfo'] = ProcessList::leftJoin( 'ss_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
            ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
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
                'apps.op_office_address as op_office_address',

                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',

                'apps.*',
                'apps.company_name as org_nm',
                'apps.issue_date as license_issue_date',
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

        $data['appShareholderInfo'] = self::Join( 'shareholders', 'shareholders.app_id', '=', 'ss_license_renew.id' )
            ->where( [
                'ss_license_renew.id'        => $decodedAppId,
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

        $public_html       = (string) view( 'REUSELicenseIssue::SS.Renew.view', $data );

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
        $data['appInfo']   = ProcessList::leftJoin( 'ss_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
            $public_html = (string) view( 'REUSELicenseIssue::SS.Renew.form-edit', $data );
        } else {
            $public_html = (string) view( 'REUSELicenseIssue::ss.Renew.form-edit-v2', $data );
        }

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $issue_company_id      = SSLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $process_type_id   = $this->process_type_id;
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['appInfo'] = ProcessList::leftJoin( 'ss_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'ss_license_master as ms', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( 78) );
            } )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( 78 ) );       // comes for renew, so have to have payment for issue -> 1
            } )
            ->where( 'ms.license_no', $request->license_no )
            ->where( 'ms.status', 1 )
            ->where( 'process_list.process_type_id', 78 )                       // approved status can be renew
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
                'ms.company_id as master_company_id',
                'ms.issue_tracking_no',
                'apps.declaration_q1 as declaration_q1',
                'apps.declaration_q1_text as declaration_q1_text',
                'apps.declaration_q2 as declaration_q2',
                'apps.declaration_q2_text as declaration_q2_text',
                'apps.declaration_q3 as declaration_q3',
                'apps.declaration_q3_doc as declaration_q3_doc',
                'apps.dd_file_1 as dd_file_1',
                'apps.dd_file_2 as dd_file_2',
                'apps.dd_file_3 as dd_file_3',

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
        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Data not found on provided license number' ] );
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['process_type_id'] = $this->process_type_id;
        $shareholders_data       = Shareholder::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $this->ss_issue_process_type_id // 1 = isp issue process type id
        ] )->get( [
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
            'shareholders.share_percent as shareholders_share_percent',
            'shareholders.no_of_share as no_of_share',
            'shareholders.share_value as share_value',
            'nationality as shareholders_nationality'
        ] );
        foreach ( $shareholders_data as $index => $value ) {
            if ( public_path( $value->shareholders_image ) && ! empty( $value->shareholders_image ) ) {
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $this->ss_issue_process_type_id// for isp license issue
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }

        $data['contact_person'] = $contact_data;
        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();
        //dd($data['contact_person']);
        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $public_html = (string) view( 'REUSELicenseIssue::SS.Renew.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    private function fetchAndPrprIssueData( $license_no, $appData, $request ) {
        $existedData = SSLicenseMaster::leftJoin( 'ss_license_issue as apps', 'apps.tracking_no', '=', 'ss_license_master.issue_tracking_no' )
            ->where( [ 'ss_license_master.license_no' => $license_no ] )->first();

        $appData->org_nm       = $request->company_name;
        $appData->org_type     = $request->company_type;
        $appData->reg_office_district = $request->reg_office_district;
        $appData->reg_office_thana = $request->reg_office_thana;
        $appData->reg_office_address = $request->reg_office_address;
        $appData->op_office_district = $request->op_office_district;
        $appData->op_office_thana = $request->op_office_thana;
        $appData->op_office_address = $request->op_office_address;
        $appData->applicant_name = $request->applicant_name;
        $appData->applicant_mobile   = $request->applicant_mobile;
        $appData->applicant_telephone    = $request->applicant_telephone;
        $appData->applicant_email    = $request->applicant_email;
        $appData->applicant_district = $request->applicant_district;
        $appData->applicant_thana  = $request->applicant_thana;
        $appData->applicant_address  = $request->applicant_address;

        $appData->declaration_q1      = $request->get( 'declaration_q1' );
        $appData->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $appData->declaration_q2      = $request->get( 'declaration_q2' );
        $appData->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $appData->declaration_q3      = $request->get( 'declaration_q3' );

        if ( $request->hasFile( 'declaration_q3_images' ) ) {
            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
            $path      = 'uploads/ss-license-renew/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $_file_path = $request->file( 'declaration_q3_images' );
            $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
            $_file_path->move( $path, $file_path );
            $appData->declaration_q3_doc = $path . $file_path;
        }

        $appData->location_of_ins_district = $request->get('location_of_ins_district');
        $appData->location_of_ins_thana    = $request->get('location_of_ins_thana');
        $appData->location_of_ins_address  = $request->get('location_of_ins_address');
        $appData->location_of_ins_address2 = $request->get('location_of_ins_address2');

        $appData->home       = $request->get('home');
        $appData->cyber_cafe = $request->get('cyber_cafe');
        $appData->office     = $request->get('office');
        $appData->others     = $request->get('others');

        $appData->corporate_user = $request->get('corporate_user');
        $appData->personal_user  = $request->get('personal_user');
        $appData->branch_user    = $request->get('branch_user');
        // list_of_clients
        if ($request->hasFile('list_of_clients')) {
            $yearMonth = date('Y') . '/' . date('m') . '/';
            $path      = 'uploads/ss-license-renew/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $_file_path = $request->file('list_of_clients');
            $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
            $_file_path->move($path, $file_path);
            $appData->list_of_clients = $path . $file_path;
        }

        $appData->status     = 1;
        $appData->updated_at = Carbon::now();
        $company_id = CommonFunction::getUserCompanyWithZero();
        $appData->company_id        = ! empty( $company_id ) ? $company_id : 0;
        $appData->issue_tracking_no = ! empty( $existedData['issue_tracking_no'] ) ? $existedData['issue_tracking_no'] : null;
        $appData->license_no        = $license_no;
        $appData->expiry_date       = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;
        $appData->issue_date       = ! empty( $existedData['license_issue_date'] ) ? $existedData['license_issue_date'] : null;
        $appData->total_no_of_share = $request->get('total_no_of_share');
        $appData->total_share_value = $request->get('total_share_value');
        return $appData;

    }

    private function prprRenewData( $request, $appData, $license_no ) {
        $existedData = SSLicenseMaster::leftJoin( 'ss_license_issue as apps', 'apps.tracking_no', '=', 'ss_license_master.issue_tracking_no' )
            ->where( [ 'ss_license_master.license_no' => $license_no ] )->first();

        $appData->issue_date            = ! empty( $existedData['license_issue_date'] ) ? $existedData['license_issue_date'] : null;
        $appData->expiry_date           = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;
        $appData->type_of_services      = ! empty( $request->service_type ) ? json_encode( $request->service_type ) : '';
        $appData->cable_length          = ! empty( $request->cable_length ) ? $request->cable_length : '';
        $appData->cable_type            = ! empty( $request->cable_type ) ? json_encode( $request->cable_type ) : '';
        $appData->installation_address  = ! empty( $request->installation_address ) ? $request->installation_address : '';

        return $appData;
    }

    private function storeShareHolderData( $license_no, $appDataId, $request ) {
        CommonFunction::storeShareHolderPerson($request, $this->process_type_id,$appDataId);

    }

    private function storeContactPersonData( $license_no, $appDataId, $request ) {
        // Store Contact Person
        CommonFunction::storeContactPerson($request, $this->process_type_id, $appDataId);

    }

}
