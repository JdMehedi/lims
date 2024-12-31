<?php


namespace App\Modules\REUSELicenseIssue\Models\ISP\surrender;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Models\User;
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
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewTariffChart;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Users\Models\Countries;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ISPLicenseSurrender extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    private $form_url;
    protected $table = 'isp_license_surrender';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;
    private $chairman_desk_id = 1;
    private $formPath = 'ISP.Surrender';
    private $master_table = 'isp_license_master';


    public function createForm( $currentInstance ): string {
        $this->process_type_id = $currentInstance->process_type_id;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $this->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $this->process_type_id )->value( 'name' );

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']  = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['bank_list']        = Bank::orderBy( 'name' )->where( 'is_active', 1 )->pluck( 'name', 'id' )->toArray();
        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( "REUSELicenseIssue::$this->formPath.form", $data ) );

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
        $appData->save();

        if ( $appData->id ) {
            ## Store Equipment List Data
            $this->storeISPEquipment( $appData->id, $request );

            ## Store Tariff Chart Data
            $this->storeISPTariffChart( $appData->id, $request );

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
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('ISP', $this->process_type_id, $processData->id, $this->table, 'SUR', $appData->id);
        }

        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {
//            if ( empty( $appData->tracking_no ) ) {
//                $trackingPrefix = 'ISP-S-' . date( 'Ymd' ) . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
//                #CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
//               //CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
//
//            }

            /*
            //Preparing E-nothi Data Start
            $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();
            commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
            //Preparing E-nothi Data End
            */

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'ISP License Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'ISP License Surrender',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);


            // Send mail to desk officer for notifying that an application has been submitted

//            $getAllDeskUserForSendMail = User::where([
//                'user_type' => '4x404',
//                'desk_id' => 1,
//            ])->get()->toArray();


//            if(!empty($getAllDeskUserForSendMail)){
//                foreach ($getAllDeskUserForSendMail as $user){
//                    $receiverInfo = [
//                        array(
//                            'user_mobile' => $user['user_mobile'],
//                            'user_email' => $user['user_email']
//                        )
//                    ];
//                    CommonFunction::sendEmailSMS( 'DESK_PROCESS_MAIL_TO_DESK_OFFICER', $appInfo, $receiverInfo );
//                }
//            }


        }
        ## Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
//            CommonFunction::sendEmailForReSubmission( $processData );

            /*
            //Preparing E-nothi Data Start
            $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();
            commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
            //Preparing E-nothi Data End
            */

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'ISP License Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'ISP License Surrender',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id, $request->get( 'actionBtn' ));

        }

        CommonFunction::setFlashMessageByStatusId($processData->status_id);

        return redirect( "/$this->form_url/list/" . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {

        $decodedAppId          = Encryption::decodeId( $applicationId );
        $this->process_type_id = $data['process_type_id'] = $process_type_id = $processTypeId;
        $processList = ProcessList::where('ref_id', $decodedAppId)
            ->where('process_type_id', $process_type_id)
            ->first(['company_id']);
        $compId = $processList->company_id;
        $data['appInfo'] = ProcessList::leftJoin( "$this->table as apps", 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'company_info', 'company_info.id', '=', DB::raw($compId) )
                                      ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin("$this->master_table as master", 'master.issue_tracking_no', '=', 'apps.issue_tracking_no')
                                      ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
                                      ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
                                      ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.op_office_district' )
                                      ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.op_office_thana' )
                                      ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                                      ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                                      ->leftJoin( 'area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district' )
                                      ->leftJoin( 'area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana' )
                                      ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
                                      ->leftJoin('area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division')
                                      ->leftJoin('area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district')
                                       ->leftJoin('area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila')
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

                                          'isp_license_division_info.area_nm as isp_license_division_name',
                                          'isp_license_district_info.area_nm as isp_license_district_name',
                                          'isp_license_upazila_info.area_nm as isp_license_upazila_name',

                                          'applicant_district.area_nm as applicant_district_en',
                                          'applicant_thana.area_nm as applicant_thana_en',
                                          'reg_off_district.area_nm as reg_office_district_en',
                                          'reg_off_thana.area_nm as reg_office_thana_en',
                                          'noc_dis.area_nm as op_office_district_en',
                                          'noc_thana.area_nm as op_office_thana_en',
                                          'location_of_ins_district.area_nm as location_of_ins_district_en',
                                          'location_of_ins_thana.area_nm as location_of_ins_thana_en',
                                          'apps.*',
                                          'master.license_issue_date as license_issue_date',
                                          'master.expiry_date as expiry_date',
                                          'company_info.incorporation_num',
                                          'company_info.incorporation_date',

                                      ] );

        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
                                                         ->where( 'ref_id', $decodedAppId )
                                                         ->whereNotNull('uploaded_path')
                                                         ->get();

        // for sub-view
        $shareholders_data = ISPLicenseSurrender::Join( 'shareholders', 'shareholders.app_id', '=', 'isp_license_surrender.id' )
                                                ->where( [
                                                    'isp_license_surrender.id'     => $decodedAppId,
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
            $value->nationality = $nationality->name;
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
        /** Fetch data from isp_license_equipment_list */
        $data['isp_equipment_list'] = ISPLicenseSurrenderEquipmentList::where( [ 'isp_license_surrender_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from isp_license_tariff_chart */
        $data['isp_tariff_chart_list'] = ISPLicenseSurrenderTariffChart::where( [ 'isp_license_surrender_id' => $data['appInfo']['id'] ] )->get();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();
        $public_html = (string) view( "REUSELicenseIssue::$this->formPath.view", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm( $processTypeId, $applicationId ): JsonResponse {
        $this->process_type_id  = $data['process_type_id'] = $process_type_id = $processTypeId;
        $data['vat_percentage'] = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $applicationId = Encryption::decodeId( $applicationId );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo']   = ProcessList::leftJoin( 'isp_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id' )
                                        ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                            $join->on( 'ps.id', '=', 'process_list.status_id' );
                                            $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                        } )
                                        ->leftJoin("$this->master_table as master", 'master.issue_tracking_no', '=', 'apps.issue_tracking_no')
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
                                            'master.license_issue_date as license_issue_date',
                                            'master.expiry_date as expiry_date',
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

        /** Fetch data from isp_license_equipment_list */
        $data['isp_equipment_list'] = ISPLicenseSurrenderEquipmentList::where( [ 'isp_license_surrender_id' => $data['appInfo']['ref_id'] ] )->get();

        /** Fetch data from isp_license_tariff_chart */
        $data['isp_tariff_chart_list'] = ISPLicenseSurrenderTariffChart::where( [ 'isp_license_surrender_id' => $data['appInfo']['ref_id'] ] )->get();

        $public_html = (string) view( "REUSELicenseIssue::$this->formPath.edit", $data );
        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $data['process_type_id'] = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $issue_company_id      = ISPLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        if ( $companyId != $issue_company_id ) {
            return response()->json(['responseCode' => -1, 'msg' => 'Try with valid Owner']);
        }



        $data['master_data'] = ISPLicenseMaster::where( 'license_no', $request->license_no )->first();


        if ( ! empty( $data['master_data']->renew_tracking_no ) ) {
            $data['appInfo'] = ProcessList::leftJoin( 'isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                                          ->leftJoin( "$this->master_table as ms", function ( $join ){
                                                  $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                                              } )
                                          ->leftJoin( 'process_status as ps', function ( $join ) {
                                                  $join->on( 'ps.id', '=', 'process_list.status_id' );
                                                  $join->on( 'ps.process_type_id', '=', DB::raw( 2 ) );
                                              } )
                                          ->leftJoin( 'sp_payment as sfp', function ( $join ) {
                                                  $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                                  $join->on( 'sfp.process_type_id', '=', DB::raw( 3 ) );
                                              } )
                                          ->where( 'ms.license_no', $request->get('license_no') )
                                          ->where( 'ms.status', 1 )
                                          ->where( 'process_list.process_type_id', 2 )
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
                                              'apps.org_nm as company_name',
                                              'apps.org_type as company_type',
                                              'apps.issue_date as license_issue_date',
                                              'ms.issue_tracking_no',
                                              'ms.renew_tracking_no',
                                              'ms.cancellation_tracking_no',
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
                                          if ( $data['appInfo']!= null && $data['appInfo']->cancellation_tracking_no != null  ) {
                                            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

                                        }
        } else {
            $data['appInfo']    = ProcessList::leftJoin( 'isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                             ->leftJoin( "$this->master_table as ms", function ( $join ) {
                                                     $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                                 } )
                                             ->leftJoin( 'process_status as ps', function ( $join )  {
                                                     $join->on( 'ps.id', '=', 'process_list.status_id' );
                                                     $join->on( 'ps.process_type_id', '=', DB::raw( 1 ) );
                                                 } )
                                             ->leftJoin( 'sp_payment as sfp', function ( $join ) {
                                                     $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                                     $join->on( 'sfp.process_type_id', '=', DB::raw( 3 ) );
                                                 } )
                                             ->where( 'ms.license_no', $request->get('license_no') )
                                             ->where( 'ms.status', 1 )
                                             ->where( 'process_list.process_type_id', 1 )
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
                                                 'apps.org_nm as company_name',
                                                 'apps.org_type as company_type',
                                                 'ms.issue_tracking_no',
                                                 'ms.renew_tracking_no',
                                                 'ms.cancellation_tracking_no',
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
                                             if ( $data['appInfo']!= null && $data['appInfo']->cancellation_tracking_no != null  ) {
                                                return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

                                            }

        }



        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        if ( empty( $data['appInfo'] ) ) {
               //special code for cancel
                $data['appInfotest'] = ProcessList::leftJoin( 'isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin( "$this->master_table as ms", function ( $join ){
                        $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                    } )
                ->leftJoin( 'process_status as ps', function ( $join ) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw( 2 ) );
                    } )
                ->leftJoin( 'sp_payment as sfp', function ( $join ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                        $join->on( 'sfp.process_type_id', '=', DB::raw( 3 ) );
                    } )
                ->where( 'ms.license_no', $request->get('license_no') )
                ->where( 'process_list.process_type_id', 2 )
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
                    'apps.org_nm as company_name',
                    'apps.org_type as company_type',
                    'ms.issue_tracking_no',
                    'ms.renew_tracking_no',
                    'ms.cancellation_tracking_no',
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
                if ( $data['appInfotest']!= null && $data['appInfotest']->cancellation_tracking_no != null  ) {
                return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

                }

                //special code for cancel end
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

        if(!empty($data['master_data']->renew_tracking_no)){
            /** Fetch data from isp_license_equipment_list */
            $data['isp_equipment_list'] = ISPLicenseRenewEquipmentList::where( [ 'id' => $data['appInfo']['ref_id'] ] )->latest()->get();

            /** Fetch data from isp_license_tariff_chart */
            $data['isp_tariff_chart_list'] = ISPLicenseRenewTariffChart::where( [ 'isp_license_id' => $data['appInfo']['ref_id'] ] )->latest()->get();

        }else {
            /** Fetch data from isp_license_equipment_list */
            $data['isp_equipment_list'] = ISPLicenseEquipmentList::where(['isp_license_issue_id' => $data['appInfo']['ref_id']])->get();

            /** Fetch data from isp_license_tariff_chart */
            $data['isp_tariff_chart_list'] = ISPLicenseTariffChart::where(['isp_license_issue_id' => $data['appInfo']['ref_id']])->get();
        }
        $public_html = (string) view( 'REUSELicenseIssue::ISP.Surrender.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    private function storeISPEquipment( $appDataId, $request ) {
        if ( isset( $request->equipment_name ) && count( $request->equipment_name ) > 0 ) {
            ISPLicenseSurrenderEquipmentList::where( 'isp_license_surrender_id', $appDataId )->delete();

            foreach ( $request->equipment_name as $index => $value ) {
                $equipObj                           = new ISPLicenseSurrenderEquipmentList();
                $equipObj->isp_license_surrender_id = $appDataId;
                $equipObj->name                     = $value;
                $equipObj->brand_model              = $request->equipment_brand_model[ $index ];
                $equipObj->quantity                 = $request->equipment_quantity[ $index ];
                $equipObj->remarks                  = $request->equipment_remarks[ $index ];
                $equipObj->created_at               = date( 'Y-m-d H:i:s' );
                $equipObj->save();
            }
        }
    }

    private function storeISPTariffChart( $appDataId, $request ) {
        if ( isset( $request->tariffChart_package_name_plan ) && count( $request->tariffChart_package_name_plan ) > 0 ) {
            ISPLicenseSurrenderTariffChart::where( 'isp_license_surrender_id', $appDataId )->delete();

            foreach ( $request->tariffChart_package_name_plan as $index => $value ) {
                $tariffObj                           = new ISPLicenseSurrenderTariffChart();
                $tariffObj->isp_license_surrender_id = $appDataId;
                $tariffObj->package_name_plan        = $value;
                $tariffObj->bandwidth_package        = $request->tariffChart_bandwidth_package[ $index ];
                $tariffObj->price                    = $request->tariffChart_price[ $index ];
                $tariffObj->duration                 = $request->tariffChart_duration[ $index ];
                $tariffObj->remarks                  = $request->tariffChart_remarks[ $index ];
                $tariffObj->created_at               = date( 'Y-m-d H:i:s' );
                $tariffObj->save();
            }
        }

    }


    private function storeLicenseData( $licenseObj, $request ) {
        $licenseObj->org_nm              = $request->get( 'company_name' );
        $licenseObj->org_type            = $request->get( 'company_type' );
        $licenseObj->license_no          = $request->get( 'license_no' );
        $licenseObj->surrender_date      = ! empty( $request->get( 'surrender_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'surrender_date' ) ) ) : null;
        $licenseObj->reason_of_surrender = $request->get( 'reason_of_surrender' );
        if(!$licenseObj->issue_tracking_no){
            $licenseObj->issue_tracking_no   = ! empty( $request->get( 'issue_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'issue_tracking_no' ) ) : null;
        }
        if(!$licenseObj->renew_tracking_no){
            $licenseObj->renew_tracking_no   = ! empty( $request->get( 'renew_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'renew_tracking_no' ) ) : null;
        }

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


        $typeOfIspLicense                 = $request->get( 'type_of_isp_licensese' );
        $licenseObj->isp_license_type     = $typeOfIspLicense;
        $licenseObj->isp_license_division = $request->get( 'isp_licensese_area_division' );
        $licenseObj->isp_license_district = $request->get( 'isp_licensese_area_district' );
        $licenseObj->isp_license_upazila  = $request->get( 'isp_licensese_area_thana' );

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

        if ( $typeOfIspLicense == 1 ) {
            $licenseObj->isp_license_division = null;
            $licenseObj->isp_license_district = null;
            $licenseObj->isp_license_upazila  = null;
        } elseif ( $typeOfIspLicense == 2 ) {
            $licenseObj->isp_license_district = null;
            $licenseObj->isp_license_upazila  = null;
        } elseif ( $typeOfIspLicense == 3 ) {
            $licenseObj->isp_license_upazila = null;
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

            $license_json_data=[];
            $license_name = DB::table('license_type')
                ->where('id', $request->get('type_of_isp_licensese'))
                ->first();
            $div_name = DB::table('area_info')
                ->where('area_id', $request->get('isp_licensese_area_division'))
                ->where('area_type', 1)
                ->pluck('area_nm')
                ->first();
            $dist_name = DB::table('area_info')
                ->where('area_id', $request->get('isp_licensese_area_district'))
                ->where('area_type', 2)
                ->pluck('area_nm')
                ->first();
            $upz_name = DB::table('area_info')
                ->where('area_id', $request->get('isp_licensese_area_thana'))
                ->where('area_type', 3)
                ->pluck('area_nm')
                ->first();
            $license_json_data['License Type']= $license_name->name;
            if(isset($div_name)){
                $license_json_data['Division']= ($div_name)? $div_name: ''  ;
            }
            if(isset($dist_name)){
                $license_json_data['District']= ($dist_name)? $dist_name: '';
            }
            if(isset($upz_name)){
                $license_json_data['Upazilla']= ($upz_name)?$upz_name : '';
            }

            $processListObj->license_json =json_encode($license_json_data);

            $resultData = "{$processListObj->id}-{$processListObj->tracking_no}{$processListObj->desk_id}-{$processListObj->status_id}-{$processListObj->user_id}-{$processListObj->updated_by}";

            $processListObj->previous_hash = $processListObj->hash_value ?? '';
            $processListObj->hash_value    = Encryption::encode( $resultData );

        } else {
            $processListObj->status_id = $this->submitted_status_id;
            $processListObj->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
            $license_json_data=[];
            $license_name = DB::table('license_type')
                ->where('id', $request->get('type_of_isp_licensese'))
                ->first();
            $div_name = DB::table('area_info')
                ->where('area_id', $request->get('isp_licensese_area_division'))
                ->where('area_type', 1)
                ->pluck('area_nm')
                ->first();
            $dist_name = DB::table('area_info')
                ->where('area_id', $request->get('isp_licensese_area_district'))
                ->where('area_type', 2)
                ->pluck('area_nm')
                ->first();
            $upz_name = DB::table('area_info')
                ->where('area_id', $request->get('isp_licensese_area_thana'))
                ->where('area_type', 3)
                ->pluck('area_nm')
                ->first();

            $license_json_data['License Type']= $license_name->name;
            if(isset($div_name)){
                $license_json_data['Division']= ($div_name)? $div_name: ''  ;
            }
            if(isset($dist_name)){
                $license_json_data['District']= ($dist_name)? $dist_name: '';
            }
            if(isset($upz_name)){
                $license_json_data['Upazilla']= ($upz_name)?$upz_name : '';
            }

            $processListObj->license_json =json_encode($license_json_data);
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
