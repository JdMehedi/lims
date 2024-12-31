<?php


namespace App\Modules\REUSELicenseIssue\Models\BPO\surrender;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Models\User;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterMaster;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\ProposalArea;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\ExistingCallCenterDetails;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\RenewProposalArea;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
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

class CallCenterSurrender extends Model implements FormInterface
{
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'call_center_surrender';
    protected $guarded = ['id'];
    public $timestamps = true;
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = -1;
    private $submitted_status_id = 1;
    private $chairman_desk_id = 1;
    private $formPath = 'BPO.Surrender';
    private $master_table = 'call_center_master';
    private $form_url = 'bpo-or-call-center-surrender';


    public function createForm($currentInstance): string
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        $data['process_type_id'] = $this->process_type_id;
        $data['formPath'] = $this->formPath;
        $data['acl_name'] = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where('id', $this->process_type_id)->value('name');

        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['bank_list'] = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();
        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        return strval(view("REUSELicenseIssue::$this->formPath.form", $data));
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {

        $this->process_type_id = $currentInstance->process_type_id;
        $this->form_url = $currentInstance->process_info->form_url;

        if ($request->get('app_id')) {
            $appData = self::find(Encryption::decodeId($request->get('app_id')));
            $processData = ProcessList::where([
                'process_type_id' => $this->process_type_id,
                'ref_id' => $appData->id
            ])->first();
        } else {
            $appData = new self();
            $processData = new ProcessList();
        }

        $appData = $this->storeLicenseData($appData, $request);

        if ($appData->id) {
            ## Store Proposal Area Data
            $this->storeProposalAreaData($appData->id, $request);

            ## Store  Share Holder Data
            commonFunction::storeShareHolderPerson($request, $this->process_type_id, $appData->id,);

            ## Store Contact Person Data
            commonFunction::storeContactPerson($request, $this->process_type_id, $appData->id,);

            ## dynamic document start
            DocumentsController::storeAppDocuments($this->process_type_id, $request->doc_type_key, $appData->id, $request);
            // store Existing Call Center Details
            $this->storeExistingCallCenterDetails( $appData->id, $request );

            ## Store Process list Data
            $this->storeProcessListData($request, $processData, $appData);
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('BPO', $this->process_type_id, $processData->id, $this->table, 'SUR', $appData->id);
        }

        if ($request->get('actionBtn') == 'submit' && $processData->status_id == 1) {
//            if (empty($appData->tracking_no)) {
//                $trackingPrefix = 'BPO-S-' . date('Ymd') . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
//                #CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
//                //CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table);
//            }
            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'BPO/ Call Center Registration Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'BPO/ Call Center License Registration',
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
        if ($processData->status_id == 2) {
//            CommonFunction::sendEmailForReSubmission($processData);
            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'BPO/ Call Center Registration Surrender', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'BPO/ Call Center Registration Surrender',
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

        return redirect("/$this->form_url/list/" . Encryption::encodeId($this->process_type_id));
    }

    public function fetchData($request, $currentInstance): JsonResponse
    {

        $this->process_type_id = $currentInstance->process_type_id;
        $data['license_no'] = $request->license_no;
        $issue_company_id      = CallCenterNew::where('license_no', $request->license_no)->value('company_id');

        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage'] = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');

        $process_type_id = $this->process_type_id;
        $data['divisions'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        $data['master_data'] = CallCenterMaster::where('license_no', $request->get('license_no'))->first();
        if (!empty($data['master_data']->renew_tracking_no)) {
            $data['appInfo'] = ProcessList::leftJoin('call_center_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin("$this->master_table as ms", function ($join) {
                    $join->on('ms.renew_tracking_no', '=', 'apps.tracking_no');
                })
                ->leftJoin('process_status as ps', function ($join) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw(6));
                })
                ->where('ms.license_no', $request->get('license_no'))
//                ->where('ms.status', 1)
                ->where('process_list.process_type_id', 6)
                ->first([
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
                    'apps.noc_district as op_office_district',
                    'apps.noc_thana as op_office_thana',
                    'apps.noc_address as op_office_address',
                    'ms.issue_tracking_no',
                    'ms.renew_tracking_no',
                    'ms.status',
                    'apps.declaration_q1 as declaration_q1',
                    'apps.declaration_q1_text as declaration_q1_text',
                    'apps.declaration_q2 as declaration_q2',
                    'apps.declaration_q2_text as declaration_q2_text',
                    'apps.declaration_q3 as declaration_q3',
                    'apps.dd_file_1 as dd_file_1'
                ]);

            $shareholders_data = Shareholder::where([
                'app_id' => $data['appInfo']['id'],
                'process_type_id' => 6 // 6 = Call Center renew process type id
            ])->get([
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
            ]);
            $data['contact_person'] = ContactPerson::where([
                'app_id' => $data['appInfo']['id'],
                'process_type_id' => 6 // 6 = Call Center renew process type id
            ])->get();
            $data['proposal_area'] = RenewProposalArea::where(['call_center_renew_id' => $data['appInfo']['id']])->get();
            $data['existingCallCenterDetails'] = ExistingCallCenterDetails::where( [ 'call_center_renew_id' => $data['appInfo']['id'] ] )->get();
        } else {
            $data['appInfo'] = ProcessList::leftJoin('call_center_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin("$this->master_table as ms", function ($join) {
                    $join->on('ms.issue_tracking_no', '=', 'apps.tracking_no');
                })
                ->leftJoin('process_status as ps', function ($join) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw(5));
                })
                ->where('ms.license_no', $request->get('license_no'))
//                ->where('ms.status', 1)
                ->where('process_list.process_type_id', 5)
                ->first([
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
                    'apps.noc_district as op_office_district',
                    'apps.noc_thana as op_office_thana',
                    'apps.noc_address as op_office_address',
                    'ms.issue_tracking_no',
                    'ms.renew_tracking_no',
                    'ms.status',
                    'apps.declaration_q1 as declaration_q1',
                    'apps.declaration_q1_text as declaration_q1_text',
                    'apps.declaration_q2 as declaration_q2',
                    'apps.declaration_q2_text as declaration_q2_text',
                    'apps.declaration_q3 as declaration_q3',
                    'apps.dd_file_1 as dd_file_1',
                ]);

            $shareholders_data = Shareholder::where([
                'app_id' => $data['appInfo']['id'],
                'process_type_id' => 5 // 5 = Call Center issue process type id
            ])->get([
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
            ]);

            $data['proposal_area'] = ProposalArea::where(['ref_id' => $data['appInfo']['id']])->get();
            $data['contact_person'] = ContactPerson::where([
                'app_id' => $data['appInfo']['id'],
                'process_type_id' => 5 // 5 = Call Center issue process type id
            ])->get();
        }
        if (isset($data['appInfo']->status) && $data['appInfo']->status === 0){
            return response()->json(['responseCode' => -1, 'msg' => 'This license already surrendered']);
        }
        if (empty($data['appInfo'])) {
            return response()->json(['responseCode' => -1, 'msg' => 'Please provide valid license no']);
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['process_type_id'] = $this->process_type_id;

        foreach ($shareholders_data as $index => $value) {
            if (public_path($value->shareholders_image) && !empty($value->shareholders_image)) {
                $value->image_real_path = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;


        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
            if (public_path($item->image) && !empty($item->image)) {
                $data['contact_person'][$key]['image_real_path'] = $item->image;
                $data['contact_person'][$key]['image']         = CommonFunction::imagePathToBase64(public_path($item->image));
            }
        }
        $existingCallCenterData = ExistingCallCenterDetails::where('call_center_renew_id', $data['appInfo']['id'])->get();
        foreach ($existingCallCenterData as $index => $value) {
            $value->district = Area::where('area_id', $value->district)->value('area_nm');
            $value->thana = Area::where('area_id', $value->thana)->value('area_nm');
        }
        $data['existingCallCenterData'] = $existingCallCenterData;

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $public_html = (string)view("REUSELicenseIssue::$this->formPath.search", $data);

        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function viewForm($processTypeId, $applicationId): JsonResponse
    {

        $decodedAppId = Encryption::decodeId($applicationId);
        $this->process_type_id = $data['process_type_id'] = $process_type_id = $processTypeId;
        $data['form_url'] = $this->form_url;

        $data['appInfo'] = ProcessList::leftJoin("$this->table as apps", 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin("$this->master_table as master", 'master.issue_tracking_no', '=', 'apps.issue_tracking_no')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district')
            ->leftJoin('area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana')
            ->leftJoin('area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.op_office_district')
            ->leftJoin('area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.op_office_thana')
            ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
            ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->where('process_list.ref_id', $decodedAppId)
            ->where('process_list.process_type_id', $process_type_id)
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
                'process_type.form_url',
                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',
                'reg_off_district.area_nm as reg_office_district_en',
                'reg_off_thana.area_nm as reg_office_thana_en',
                'noc_dis.area_nm as op_office_district_en',
                'noc_thana.area_nm as op_office_thana_en',
                'apps.*',
                'apps.company_name as org_nm',
                'apps.company_type as org_type',
                'master.license_issue_date as license_issue_date',
                'master.expiry_date as expiry_date',
            ]);

        $data['existingCallCenterData'] = ExistingCallCenterSurrenderDetails::where( 'ref_id', $data['appInfo']['id'] )->get();
        $data['appDynamicDocInfo'] = ApplicationDocuments::where('process_type_id', $processTypeId)
            ->where('ref_id', $decodedAppId)
            ->whereNotNull('uploaded_path')
            ->get();

        // for sub-view
        $shareholders_data = self::Join('shareholders', 'shareholders.app_id', '=', "$this->table.id")
            ->where([
                "$this->table.id" => $decodedAppId,
                'shareholders.process_type_id' => $this->process_type_id
            ])
            ->get([
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
            ]);


        foreach ($shareholders_data as $index => $value) {
            $nationality = Countries::where('id', $value->nationality)->first(['name']);
            $value->nationality = $nationality->name;
        }
        $data['appShareholderInfo'] = $shareholders_data; // for sub-view

        $data['contact_person'] = ContactPerson::where([
            'app_id' => $data['appInfo']['id'],
            'process_type_id' => $this->process_type_id
        ])->get();
        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['contact_upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }


        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thana']     = ['' => 'Select'] + Area::where('area_type', 3)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $proposal_area_data = SurrenderProposalArea::where(['call_center_surrender_id' => $data['appInfo']['id']])->get();

        foreach ($proposal_area_data as $index => $value) {
            $value->proposal_district = Area::where('area_id', $value->proposal_district)->value('area_nm');
            $value->proposal_thana = Area::where('area_id', $value->proposal_thana)->value('area_nm');
        }
        $data['proposal_area'] = $proposal_area_data;
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string)view("REUSELicenseIssue::$this->formPath.view", $data);

        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function editForm($processTypeId, $applicationId): JsonResponse
    {
        $this->process_type_id = $data['process_type_id'] = $process_type_id = $processTypeId;
        $data['vat_percentage'] = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');

        $applicationId = Encryption::decodeId($applicationId);

        $data['divisions'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['appInfo'] = ProcessList::leftJoin("$this->table as apps", 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                $join->on('sfp.app_id', '=', 'process_list.ref_id');
                $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin("$this->master_table as master", 'master.issue_tracking_no', '=', 'apps.issue_tracking_no')
            ->where('process_list.ref_id', $applicationId)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
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
            ]);

        $data['existingCallCenterDetails'] = ExistingCallCenterSurrenderDetails::where( [ 'ref_id' => $data['appInfo']['id'] ] )->get();

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();
        $shareholders_data = Shareholder::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ])->get([
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
        ]);

        foreach ($shareholders_data as $index => $value) {
            if (public_path($value->shareholders_image) && !empty($value->shareholders_image)) {
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }

        $data['contact_person'] = $contact_data;

        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        $data['proposal_area'] = SurrenderProposalArea::where(['call_center_surrender_id' => $data['appInfo']['id']])->get();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        $public_html = (string)view("REUSELicenseIssue::$this->formPath.edit", $data);


        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    private function storeLicenseData($licenseObj, $request)
    {
        $licenseObj->company_id = CommonFunction::getUserCompanyWithZero();
        $licenseObj->company_name = $request->get('company_name');
        $licenseObj->issue_tracking_no = !empty($request->get('issue_tracking_no')) ? Encryption::decodeId($request->get('issue_tracking_no')) : null;
        $licenseObj->renew_tracking_no = !empty($request->get('renew_tracking_no')) ? Encryption::decodeId($request->get('renew_tracking_no')) : null;
        $licenseObj->license_no = $request->get('license_no');
        $licenseObj->surrender_date = ! empty( $request->get( 'surrender_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'surrender_date' ) ) ) : null;
        $licenseObj->reason_of_surrender = $request->get('reason_of_surrender');
        $licenseObj->license_issue_date    = date( 'Y-m-d', strtotime( $request->issue_date ) );
        $licenseObj->expiry_date           = date( 'Y-m-d', strtotime( $request->expiry_date ) );
        $licenseObj->company_type = $request->get('company_type');
        $licenseObj->reg_office_district = $request->get('reg_office_district');
        $licenseObj->reg_office_thana = $request->get('reg_office_thana');
        $licenseObj->reg_office_address = $request->get('reg_office_address');
        $licenseObj->op_office_district = $request->get('op_office_district');
        $licenseObj->op_office_thana = $request->get('op_office_thana');
        $licenseObj->op_office_address = $request->get('op_office_address');
        $licenseObj->applicant_name = $request->get('applicant_name');
        $licenseObj->applicant_district = $request->get('applicant_district');
        $licenseObj->applicant_thana = $request->get('applicant_thana');
        $licenseObj->applicant_address = $request->get('applicant_address');
        $licenseObj->applicant_email = $request->get('applicant_email');
        $licenseObj->applicant_mobile = $request->get('applicant_mobile');
        $licenseObj->applicant_telephone = $request->get('applicant_telephone');
        $licenseObj->present_business_actives = $request->get('present_business_actives');
        $licenseObj->proposal_service_type = $request->get('proposal_service_type')?json_encode($request->get('proposal_service_type')): null;
        $licenseObj->proposal_service = $request->get('proposal_service')?json_encode($request->get('proposal_service')): null;

        $licenseObj->declaration_q1 = $request->get('declaration_q1');
        $licenseObj->declaration_q1_text = $request->get('declaration_q1_text');
        $licenseObj->q1_application_date = date('Y-m-d', strtotime($request->get('declaration_q1_application_date')));
        $licenseObj->declaration_q2 = $request->get('declaration_q2');
        $licenseObj->declaration_q2_text = $request->get('declaration_q2_text');
        $licenseObj->declaration_q3 = $request->get('declaration_q3');
        $licenseObj->declaration_q3_text = $request->get('declaration_q3_text');
        $licenseObj->total_no_of_share = $request->get('total_no_of_share');
        $licenseObj->total_share_value = $request->get('total_share_value');
        $licenseObj->total_share_value = $request->get('total_share_value');

        $licenseObj->bandwidth_call_center = $request->get('bandwidth_call_center');
        $licenseObj->address_of_foreign = $request->get('address_of_foreign');
        $licenseObj->existing_bandwidth_iplc = $request->get('existing_bandwidth_iplc');
        $licenseObj->existing_bandwidth_backup = $request->get('existing_bandwidth_backup');
        $licenseObj->bandwidth_provider_iplc = $request->get('bandwidth_provider_iplc');
        $licenseObj->bandwidth_provider_backup = $request->get('bandwidth_provider_backup');
        $licenseObj->local_employee = $request->get('local_employee');
        $licenseObj->foreign_employee = $request->get('foreign_employee');
        $licenseObj->fast_financial_years      = $request->input( 'financial_years' )[1] ?? null;
        $licenseObj->fast_financial_amount     = $request->input( 'financial_amount' )[1] ?? null;
        $licenseObj->second_financial_years    = $request->input( 'financial_years' )[2] ?? null;
        $licenseObj->second_financial_amount   = $request->input( 'financial_amount' )[2] ?? null;
        //images
        if ($request->get('declaration_image_file')) {
            $licenseObj->declaration_q3_images = $request->get('declaration_image_file');
        }
        $licenseObj->status = 1;
        $licenseObj->updated_at = Carbon::now();
        $licenseObj->save();

        return $licenseObj;
    }

    private function storeProposalAreaData($appDataId, $request)
    {
        if (isset($request->proposal_district) && count($request->proposal_district) > 0) {
            SurrenderProposalArea::where('call_center_surrender_id', $appDataId)->delete();
            $proposalAreaData = [];
            foreach ($request->proposal_district as $index => $value) {
                $proposalAreaData[] = [
                    'call_center_surrender_id' => $appDataId,
                    'proposal_district' => $request->proposal_district[$index] ?? 0,
                    'proposal_thana' => $request->proposal_thana[$index] ?? 0,
                    'proposal_address' => $request->proposal_address[$index] ?? null,
                    'proposal_no_of_seats' => $request->proposal_no_of_seats[$index] ?? null,
                    'proposal_employee' => $request->proposal_employee[$index] ?? null,
                    'local' => $request->local[$index] ?? null,
                    'expatriate' => $request->expatriate[$index] ?? null,
                    'created_at' => now()
                ];
            }
            SurrenderProposalArea::insert($proposalAreaData);
        }
    }

    private function storeProcessListData($request, $processListObj, $appData)
    {
        $processListObj->company_id = CommonFunction::getUserCompanyWithZero();
        //Set category id for process differentiation
        $processListObj->cat_id = 1;
        if ($request->get('actionBtn') === 'draft') {
            $processListObj->status_id = $this->draft_status_id;
            $processListObj->desk_id = 0;
        } elseif ($processListObj->status_id === $this->shortfall_status_id) {
            // For shortfall
            $submission_sql_param = [
                'app_id' => $appData->id,
                'process_type_id' => $this->process_type_id,
            ];

            $process_type_info = ProcessType::where('id', $this->process_type_id)
                ->orderBy('id', 'desc')
                ->first([
                    'form_url',
                    'process_type.process_desk_status_json',
                    'process_type.name'
                ]);

            $resubmission_data = $this->getProcessDeskStatus('resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param);
            $processListObj->status_id = $resubmission_data['process_starting_status'];
            $processListObj->desk_id = $resubmission_data['process_starting_desk'];
            $processListObj->process_desc = 'Re-submitted form applicant';
            $processListObj->resubmitted_at = Carbon::now(); // application resubmission Date

            $resultData = "{$processListObj->id}-{$processListObj->tracking_no}{$processListObj->desk_id}-{$processListObj->status_id}-{$processListObj->user_id}-{$processListObj->updated_by}";

            $processListObj->previous_hash = $processListObj->hash_value ?? '';
            $processListObj->hash_value = Encryption::encode($resultData);

        } else {
            $processListObj->status_id = $this->submitted_status_id;
            $processListObj->desk_id = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
            $processListObj->submitted_at = Carbon::now();
        }

        $processListObj->ref_id = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->license_no = $appData->license_no;
        $processListObj->office_id = 0;
        $jsonData['Applicant Name'] = Auth::user()->user_first_name;
        $jsonData['Company Name'] = $request->company_name;
        $jsonData['Email'] = Auth::user()->user_email;
        $jsonData['Phone'] = Auth::user()->user_mobile;
        $processListObj['json_object'] = json_encode($jsonData);
        $processListObj->submitted_at = Carbon::now();
        $processListObj->save();

        return $processListObj;

    }

    private function storeExistingCallCenterDetails( $appDataId, $request ) {
        $existingCallCenterCount = $request->input( 'existing_call_center_count' );

        if ( ! empty( $existingCallCenterCount ) ) {
            $existingCallCenterDetails = [];

            foreach ( $request->existing_district as $index => $value ) {
                $existingCallCenterDetails[] = [
                    'ref_id'      => $appDataId,
                    'district'                  => $value,
                    'thana'                     => $request->input( 'existing_thana' )[ $index ] ?? 0,
                    'address'                   => $request->input( 'existing_address' )[ $index ] ?? null,
                    'nature_of_center'          => $request->input( 'nature_of_center' )[ $index ] ?? null,
                    'type_of_center'            => $request->input( 'type_of_center' )[ $index ] ?? null,
                    'name_call_center_provider' => $request->input( 'name_call_center_provider' )[ $index ] ?? null,
                    'existing_license_no'       => $request->input( 'existing_license_no' )[ $index ] ?? null,
                    'date_of_license'           => ! empty( $request->input( 'date_of_license' )[ $index ] ) ? date( 'Y-m-d', strtotime( $request->input( 'date_of_license' )[ $index ] ) ) : null,
                    'no_of_agents'              => $request->input( 'no_of_agents' )[ $index ] ?? null,
                    'bandwidth'                 => $request->input( 'bandwidth' )[ $index ] ?? null,
                    'name_of_clients'           => $request->input( 'name_of_clients' )[ $index ] ?? null,
                    'type_of_activity'          => $request->input( 'type_of_activity' )[ $index ] ?? null,
                    'status'                    => 1,
                    'created_at'                => now(),
                ];
            }
            ExistingCallCenterSurrenderDetails::where( 'ref_id', $appDataId )->delete();
            ExistingCallCenterSurrenderDetails::insert( $existingCallCenterDetails );
        }

    }
}
