<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\TVAS\surrender;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\TVAS\issue\TVASLicenseMaster;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Users\Models\Countries;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TVASLicenseSurrender extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'tvas_license_surrender';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    protected $master_table = 'tvas_license_master';
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;
    private $dg_desk_id = 3;
    // expect column for data store from nix_license_issue table

    public function createForm($currentInstance): string
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id']  = $this->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['application_type'] = ProcessType::Where( 'id', $this->process_type_id )->value( 'name' );
        $data['districts']        = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']         = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality']      = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();


        return strval( view( "REUSELicenseIssue::TVAS.Surrender.form", $data ) );
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {

        $this->process_type_id = $currentInstance->process_type_id;
        $license_no            = $request->get( 'license_no' );

        if ( empty( $license_no ) ) {
            Session::flash( 'error', "Invalid License No [TVASS-006]" );

            return redirect()->back()->withInput();
        }

        if ( $request->get( 'app_id' ) ) {
            $appData     = TVASLicenseSurrender::find( Encryption::decodeId($request->get('app_id')) );
            $processData = ProcessList::where( [
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new TVASLicenseSurrender();
            $processData = new ProcessList();
        }


        $appData = $this->storeTVASSurrenderData( $appData, $request );


        if ( $appData->id ) {

            //shareholder data insert operation
            if ( $request->get( 'shareholderDataCount' ) > 0 ) {
                CommonFunction::storeShareHolderPerson( $request, $currentInstance->process_type_id, $appData->id );
            }

            // contact person data insert operation

            CommonFunction::storeContactPerson( $request, $currentInstance->process_type_id, $appData->id );



            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation
            $processData->cat_id = 1;
            if ( $request->get( 'actionBtn' ) == "draft" ) {
                $processData->status_id = $this->draft_status_id;
                $processData->desk_id   = 0;
            } else {
                if ( $processData->status_id == $this->shortfall_status_id ) {
                    // Get last desk and status
                    $submission_sql_param        = [
                        'app_id'          => $appData->id,
                        'process_type_id' => $currentInstance->process_type_id,
                    ];
                    $process_type_info           = ProcessType::where( 'id', $currentInstance->process_type_id )
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

                    $processData->previous_hash = $processData->hash_value ?? "";
                    $processData->hash_value    = Encryption::encode( $resultData );

                } else {
                    $processData->status_id = $this->submitted_status_id;
                    $processData->desk_id   = $this->dg_desk_id;
                }
            }

            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $currentInstance->process_type_id;
            $processData->license_no      = $appData->license_no;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            // need to change
            $jsonData['Company Name']   = $request->company_name;
            $jsonData['Email']          = Auth::user()->user_email;
            $jsonData['Phone']          = Auth::user()->user_mobile;
            $processData['json_object'] = json_encode( $jsonData );
            $processData->submitted_at  = Carbon::now();


            $processData->save();
            //process list data insert
        }

        //  Required Documents for attachment
        $doc_type_id = '';
        DocumentsController::storeAppDocuments( $currentInstance->process_type_id, $request->doc_type_key, $appData->id, $request );


        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('TVAS', $currentInstance->process_type_id, $processData->id, $this->table, 'SUR', $appData->id);
        }


        /*
         * if application submitted and status is equal to draft then
         * generate tracking number and payment initiate
         */
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {

//            if ( empty( $processData->tracking_no ) ) {
//                $trackingPrefix = 'TVAS-S-' . date( "Ymd" ) . '-';
//                commonFunction::generateTrackingNumber( $trackingPrefix, $currentInstance->process_type_id, $processData->id, $appData->id, $this->table );
//            }

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'TVAS License Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'tracking_no'       => $trackingNumber,
                'process_type_name' => 'TVAS License Surrender',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);

        }

        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
//            CommonFunction::sendEmailForReSubmission( $processData );
            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'TVAS License Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'TVAS License Surrender',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id,$request->get( 'actionBtn' ));

        }

        CommonFunction::setFlashMessageByStatusId($processData->status_id);

        return redirect( 'client/tvas-license-cancellation/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm($processTypeId, $applicationId): JsonResponse
    {

        $decodedAppId    = Encryption::decodeId( $applicationId );
        $process_type_id = $processTypeId;

        $data['process_type_id'] = $process_type_id;
        $data['appInfo']         = ProcessList::leftJoin( 'tvas_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
            ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as per_off_district', 'per_off_district.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as per_off_thana', 'per_off_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
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
                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',
                'reg_off_district.area_nm as reg_office_district_en',
                'reg_off_thana.area_nm as reg_office_thana_en',
                'per_off_district.area_nm as op_office_district_en',
                'per_off_thana.area_nm as op_office_thana_en',
                'apps.*',
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

        $data['appShareholderInfo'] = Shareholder::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $process_type_id
        ] )->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where( 'process_type_id', $process_type_id )
            ->where( 'ref_id', $decodedAppId )
            ->whereNotNull('uploaded_path')
            ->get();


        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $process_type_id
        ] )->get();


        foreach ( $data['contact_person'] as $key => $item ) {

            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


//                    if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
//                        $data['payment_step_id'] = 1;
//                        $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['payment_step_id'] );
//                    }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();


        $public_html = (string) view( 'REUSELicenseIssue::TVAS.Surrender.view', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm($processTypeId, $applicationId): JsonResponse
    {
        $this->process_type_id  = $data['process_type_id'] = $process_type_id = $processTypeId;
        $data['vat_percentage'] = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $applicationId = Encryption::decodeId( $applicationId );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo']   = ProcessList::leftJoin( 'tvas_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                'apps.org_nm as company_name',
                'apps.org_type as company_type',
                'apps.*',

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

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $shareholders_data       = Shareholder::where( [
            'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
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
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where( [
            'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }

        $data['contact_person'] = $contact_data;

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();


        $public_html = (string) view( 'REUSELicenseIssue::TVAS.Surrender.edit', $data );


            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData($request, $currentInstance): JsonResponse{

        $this->process_type_id = $data['process_type_id'] = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }


        $data['master_data'] = TVASLicenseMaster::where( 'license_no', $request->license_no )->first();


        if ( ! empty( $data['master_data']->renew_tracking_no ) ) {
            $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin( "$this->master_table as ms", function ( $join ){
                    $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                } )
                ->leftJoin( 'process_status as ps', function ( $join ) {
                    $join->on( 'ps.id', '=', 'process_list.status_id' );
                    $join->on( 'ps.process_type_id', '=', DB::raw( 26 ) );
                } )
                ->leftJoin( 'sp_payment as sfp', function ( $join ) {
                    $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                    $join->on( 'sfp.process_type_id', '=', DB::raw( 27 ) );
                } )
                ->where( 'ms.license_no', $request->get('license_no') )
                ->where( 'ms.status', 1 )
                ->where( 'process_list.process_type_id', 26 )
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
                    'apps.company_name as org_nm',
                    'apps.company_type as org_type',
                    'apps.per_office_district as op_office_district',
                    'apps.per_office_thana as op_office_thana',
                    'apps.per_office_address as op_office_address',
                    'ms.issue_tracking_no',
                    'ms.renew_tracking_no',
                    'apps.declaration_q1 as declaration_q1',
                    'apps.declaration_q1_text as declaration_q1_text',
                    'apps.declaration_q2 as declaration_q2',
                    'apps.declaration_q2_text as declaration_q2_text',
                    'apps.declaration_q3 as declaration_q3',
                    'apps.dd_file_1 as dd_file_1',

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
        } else {
            $data['appInfo']    = ProcessList::leftJoin( 'tvas_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin( "$this->master_table as ms", function ( $join ) {
                    $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                } )
                ->leftJoin( 'process_status as ps', function ( $join )  {
                    $join->on( 'ps.id', '=', 'process_list.status_id' );
                    $join->on( 'ps.process_type_id', '=', DB::raw( 25 ) );
                } )
                ->leftJoin( 'sp_payment as sfp', function ( $join ) {
                    $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                    $join->on( 'sfp.process_type_id', '=', DB::raw( 27 ) );
                } )
                ->where( 'ms.license_no', $request->get('license_no') )
                ->where( 'ms.status', 1 )
                ->where( 'process_list.process_type_id', 25 )
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
                    'apps.company_name as org_nm',
                    'apps.company_type as org_type',
                    'apps.per_office_district as op_office_district',
                    'apps.per_office_thana as op_office_thana',
                    'apps.per_office_address as op_office_address',
                    'ms.issue_tracking_no',
                    'ms.renew_tracking_no',
                    'apps.declaration_q1 as declaration_q1',
                    'apps.declaration_q1_text as declaration_q1_text',
                    'apps.declaration_q2 as declaration_q2',
                    'apps.declaration_q2_text as declaration_q2_text',
                    'apps.declaration_q3 as declaration_q3',
                    'apps.dd_file_1 as dd_file_1',

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

        }

//        dd($data['appInfo']);

        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        $shareholders_data = Shareholder::where( [
            'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ] )
            ->get( [
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
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where( [
            'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }
        $data['contact_person'] = $contact_data;

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $public_html = (string) view( 'REUSELicenseIssue::TVAS.Surrender.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    private function storeTVASSurrenderData( $issueObj, $request ) {


        $issueObj->company_id   = CommonFunction::getUserCompanyWithZero();
        $issueObj->org_nm = $request->get( 'company_name' );
        $issueObj->org_type = $request->get( 'company_type' );
        $issueObj->license_no          = $request->get( 'license_no' );
        $issueObj->surrender_date      = ! empty( $request->get( 'surrender_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'surrender_date' ) ) ) : null;
        $issueObj->reason_of_surrender = $request->get( 'reason_of_surrender' );
        $issueObj->issue_tracking_no   = ! empty( $request->get( 'issue_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'issue_tracking_no' ) ) : null;
        $issueObj->renew_tracking_no   = ! empty( $request->get( 'renew_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'renew_tracking_no' ) ) : null;
        $issueObj->reg_office_district = $request->get( 'reg_office_district' );
        $issueObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $issueObj->reg_office_address  = $request->get( 'reg_office_address' );

        $issueObj->op_office_district = $request->get( 'op_office_district' );
        $issueObj->op_office_thana    = $request->get( 'op_office_thana' );
        $issueObj->op_office_address  = $request->get( 'op_office_address' );


        $issueObj->applicant_name      = $request->get( 'applicant_name' );
        $issueObj->applicant_district  = $request->get( 'applicant_district' );
        $issueObj->applicant_thana     = $request->get( 'applicant_thana' );
        $issueObj->applicant_address   = $request->get( 'applicant_address' );
        $issueObj->applicant_email     = $request->get( 'applicant_email' );
        $issueObj->applicant_website   = $request->get( 'applicant_website' );
        $issueObj->applicant_mobile    = $request->get( 'applicant_mobile' );
        $issueObj->applicant_telephone = $request->get( 'applicant_telephone' );
        $issueObj->total_share_value = $request->get( 'total_share_value' );
        $issueObj->total_no_of_share = $request->get( 'total_no_of_share' );

        $issueObj->declaration_q1      = $request->get( 'declaration_q1' );
        $issueObj->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $issueObj->q1_application_date = ! empty( $request->get( 'declaration_q1_application_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'declaration_q1_application_date' ) ) ) : null;
        $issueObj->declaration_q2      = $request->get( 'declaration_q2' );
        $issueObj->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $issueObj->declaration_q3      = $request->get( 'declaration_q3' );
        $issueObj->declaration_q3_text = $request->get( 'declaration_q3_text' );
        $issueObj->q3_application_date = ! empty( $request->get( 'declaration_q3_application_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'declaration_q3_application_date' ) ) ) : null;
        $issueObj->declaration_q4      = $request->get( 'declaration_q4' );
        $issueObj->q4_license_number   = $request->get( 'q4_license_number' );
        $issueObj->q4_case_no          = $request->get( 'q4_case_no' );
        $issueObj->q4_amount           = $request->get( 'q4_amount' );
        $issueObj->q4_bank_draft_no    = $request->get( 'q4_bank_draft_no' );
        $issueObj->q4_given_comission  = $request->get( 'q4_given_comission' );
        $issueObj->status              = 1;
        $issueObj->updated_at          = Carbon::now();

        $issueObj->save();

        return $issueObj;
    }
}
