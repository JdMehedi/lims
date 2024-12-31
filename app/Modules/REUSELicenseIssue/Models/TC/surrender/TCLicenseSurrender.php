<?php


namespace App\Modules\REUSELicenseIssue\Models\TC\surrender;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\TC\issue\TCLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\TC\renew\TCLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\TC\TCLicenseMaster;
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

class TCLicenseSurrender extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'tc_license_cancellation';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;
    private $formPath = 'TC.Surrender';


    public function createForm( $currentInstance ): string {
        $this->process_type_id   = $currentInstance->process_type_id;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();
        $data['process_type_id'] = $this->process_type_id;
        $data['acl_name']        = $currentInstance->acl_name;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['application_type'] = ProcessType::Where('id', $this->process_type_id)->value('name');


        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        return strval(view("REUSELicenseIssue::TC.Surrender.form", $data));

    }


    public function storeForm( $request, $currentInstance ): RedirectResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $this->form_url        = $currentInstance->process_info->form_url;

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

        $appData = $this->storeLicenseData( $appData, $request );

        if ( $appData->id ) {

            ## Store  Share Holder Data
            commonFunction::storeShareHolderPerson( $request, $this->process_type_id, $appData->id, );

            ## Store Contact Person Data
            commonFunction::storeContactPerson( $request, $this->process_type_id, $appData->id, );

            ## dynamic document start
            DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );

            ## Store Process list Data
            $this->storeProcessListData( $request, $processData, $appData );
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft','submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('TC', $this->process_type_id, $processData->id, $this->table, 'SUR', $appData->id);
        }

        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'TC License Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'TC License Surrender',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);



        }
        ## Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'TC License Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'TC License Surrender',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id);

        }

        CommonFunction::setFlashMessageByStatusId($processData->status_id);

        return redirect( "/$this->form_url/list/" . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {

        $decodedAppId          = Encryption::decodeId( $applicationId );
        $this->process_type_id = $data['process_type_id'] = $process_type_id = $processTypeId;

        $data['appInfo'] = ProcessList::leftJoin( "$this->table as apps", 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
                                      ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
                                      ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.op_office_district' )
                                      ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.op_office_thana' )
                                      ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                                      ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                                      ->leftJoin( 'area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district' )
                                      ->leftJoin( 'area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana' )
                                      ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )

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
                                          'noc_dis.area_nm as op_office_district_en',
                                          'noc_thana.area_nm as op_office_thana_en',
                                          'location_of_ins_district.area_nm as location_of_ins_district_en',
                                          'location_of_ins_thana.area_nm as location_of_ins_thana_en',
                                          'apps.*',
                                      ] );

        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
                                                         ->where( 'ref_id', $decodedAppId )
                                                         ->whereNotNull('uploaded_path')
                                                         ->get();

        // for sub-view
        $shareholders_data = TCLicenseSurrender::Join( 'shareholders', 'shareholders.app_id', '=', 'tc_license_cancellation.id' )
                                                ->where( [
                                                    'tc_license_cancellation.id'     => $decodedAppId,
                                                    'shareholders.process_type_id' => $this->process_type_id
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


        foreach ( $shareholders_data as $index => $value ) {
            $nationality        = Countries::where( 'id', $value->nationality )->first( [ 'name' ] );
            if($nationality){
                $value->nationality = $nationality->name;
            }

        }
        $data['appShareholderInfo'] = $shareholders_data; // for sub-view

        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $this->process_type_id
        ] )->get();
        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['contact_upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();


        $public_html = (string) view( "REUSELicenseIssue::$this->formPath.view", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm($processTypeId, $applicationId): JsonResponse
    {
            $this->process_type_id = $processTypeId;
            $data['process_type_id'] = $processTypeId;
            $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
            $companyId               = CommonFunction::getUserCompanyWithZero();
            $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
            $applicationId           = Encryption::decodeId( $applicationId );
            $process_type_id         = $processTypeId;
            $NixRenewData            = TCLicenseRenew::find( $applicationId );
            $data['divisions']       = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

            $data['appInfo'] = ProcessList::leftJoin( 'tc_license_cancellation as apps', 'apps.id', '=', 'process_list.ref_id' )
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
            $data['process_type_id'] = $process_type_id;

            $shareholders_data = Shareholder::where( [ 'app_id'          => $applicationId,
                'process_type_id' => $processTypeId
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
                    $value->image_real_path    = $value->shareholders_image;
                    $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
                }
            }
            $data['shareholders_data'] = $shareholders_data;

            $contact_data = ContactPerson::where( [ 'app_id'          => $applicationId,
                'process_type_id' => $processTypeId
            ] )->get();

            foreach ( $contact_data as $index => $value ) {
                if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                    $value->image_real_path = $value->image;
                    $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
                }
            }

            $data['contact_person'] = $contact_data;

            $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                    ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

                    $public_html = (string) view( "REUSELicenseIssue::$this->formPath.edit", $data );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }


    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = 66;

        $data['license_no']    = $request->license_no;
        $issue_company_id      = TCLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['process_type_id'] = $currentInstance->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $process_type_id   = $this->process_type_id;
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
        ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
        $data['master_data'] = TCLicenseMaster::where('license_no', $request->license_no)->first();

        if ( ($data['master_data']!=null) && $data['master_data']->renew_tracking_no) {
            $data['appInfo'] = ProcessList::leftJoin( 'tc_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'tc_license_master as ms', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id) );
                                      } )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( 69  ) );       // comes for renew, so have to have payment for issue -> 1
                                      } )
                                      ->where( 'ms.license_no', $request->license_no )
                                      ->where( 'process_list.process_type_id',$process_type_id )
                                      ->where( 'ms.status', 1 )                         // approved status can be renew
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
        }else{
            $data['appInfo'] = ProcessList::leftJoin( 'tc_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'tc_license_master as ms', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( $this->process_type_id  ) );       // comes for renew, so have to have payment for issue -> 1
                                      } )
                                      ->where( 'ms.license_no', $request->license_no )
                                      ->where( 'process_list.process_type_id',$this->process_type_id )
                                      ->where( 'ms.status', 1 )                         // approved status can be renew
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
        }


        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Data not found on provided license number' ] );

        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['process_type_id'] = $currentInstance->process_type_id;

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

        $public_html = (string) view( 'REUSELicenseIssue::TC.Surrender.search', $data );


        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }


    private function storeLicenseData( $licenseObj, $request ) {
        $licenseObj->org_nm              = $request->get( 'company_name' );
        $licenseObj->org_type            = $request->get( 'company_type' );
        $licenseObj->license_no          = $request->get( 'license_no' );
        $licenseObj->surrender_date      = ! empty( $request->get( 'surrender_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'surrender_date' ) ) ) : null;
        $licenseObj->reason_of_surrender = $request->get( 'reason_of_surrender' );
        $licenseObj->issue_tracking_no   = ! empty( $request->get( 'issue_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'issue_tracking_no' ) ) : null;
        $licenseObj->renew_tracking_no   = ! empty( $request->get( 'renew_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'renew_tracking_no' ) ) : null;
        $licenseObj->reg_office_district = $request->get( 'reg_office_district' );
        $licenseObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $licenseObj->reg_office_address  = $request->get( 'reg_office_address' );
        $licenseObj->op_office_district  = $request->get( 'op_office_district' );
        $licenseObj->op_office_thana     = $request->get( 'op_office_thana' );
        $licenseObj->op_office_address   = $request->get( 'op_office_address' );

        $licenseObj->applicant_name      = $request->get( 'applicant_name' );
        $licenseObj->applicant_mobile    = $request->get( 'applicant_mobile' );
        $licenseObj->applicant_telephone = $request->get( 'applicant_telephone' );
        $licenseObj->applicant_email     = $request->get( 'applicant_email' );
        $licenseObj->applicant_district  = $request->get( 'applicant_district' );
        $licenseObj->applicant_thana     = $request->get( 'applicant_thana' );
        $licenseObj->applicant_address   = $request->get( 'applicant_address' );
        $licenseObj->license_issue_date             = date( 'Y-m-d', strtotime( $request->issue_date ) );
        $licenseObj->expiry_date           = date( 'Y-m-d', strtotime( $request->expiry_date ) );

        $licenseObj->location_of_ins_district = $request->get( 'location_of_ins_district' );
        $licenseObj->location_of_ins_thana    = $request->get( 'location_of_ins_thana' );
        $licenseObj->location_of_ins_address  = $request->get( 'location_of_ins_address' );

        $licenseObj->home       = $request->get( 'home' );
        $licenseObj->cyber_cafe = $request->get( 'cyber_cafe' );
        $licenseObj->office     = $request->get( 'office' );
        $licenseObj->others     = $request->get( 'others' );

        $licenseObj->corporate_user = $request->get( 'corporate_user' );
        $licenseObj->personal_user  = $request->get( 'personal_user' );
        $licenseObj->branch_user    = $request->get( 'branch_user' );
        // list_of_clients
        if ( ! empty( $request->get( 'list_of_clients_file' ) ) ) {
            $licenseObj->list_of_clients = $request->get( 'list_of_clients_file' );
        }

        $licenseObj->business_plan       = $request->get( 'business_plan' );
        $licenseObj->declaration_q1      = $request->get( 'declaration_q1' );
        $licenseObj->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $licenseObj->declaration_q2      = $request->get( 'declaration_q2' );
        $licenseObj->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $licenseObj->declaration_q3      = $request->get( 'declaration_q3' );
        $licenseObj->status              = 1;
        $licenseObj->updated_at          = Carbon::now();
        $licenseObj->company_id          = CommonFunction::getUserCompanyWithZero();
        $licenseObj->total_no_of_share   = $request->get( 'total_no_of_share' );
        $licenseObj->total_share_value   = $request->get( 'total_share_value' );

        if ( ! empty( $request->get( 'declaration_q3_file' ) ) ) {
            $licenseObj->declaration_q3_doc = $request->get( 'declaration_q3_file' );
        }
        //images
        $licenseObj->save();

        return $licenseObj;
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
            $processListObj->license_no   = $request->get('license_no');
            $processListObj->submitted_at = Carbon::now();
        }

        $processListObj->ref_id          = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->license_no          = $appData->license_no;
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
