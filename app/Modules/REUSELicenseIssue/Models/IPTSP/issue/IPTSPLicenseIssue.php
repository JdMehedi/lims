<?php

namespace App\Modules\REUSELicenseIssue\Models\IPTSP\issue;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssueConnectedISPInfo;
use App\Modules\IPTSPLicenseIssue\Models\IPTSPLicenseIssueContactPerson;
use App\Modules\IPTSPLicenseIssue\Models\IPTSPLicenseIssueShareholder;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\AllLicense;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\AnnualFeeInfo;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
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


class IPTSPLicenseIssue extends Model implements FormInterface
{

    protected $table =     'iptsp_license_issue';
    protected $guarded = ['id'];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = -1;
    private $submitted_status_id = 1;

    use SPPaymentManager;
    use SPAfterPaymentManager;

    public function createForm($currentInstance): string
    {
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        $data['process_type_id']      = $currentInstance->process_type_id;
        $data['acl_name']             = $currentInstance->acl_name;
        $data['existing_isp_license'] = DB::table('isp_license_issue')
            ->where('company_id', auth()->user()->working_company_id)
            ->whereNotNull('license_no')
            ->first([
                'license_no',
                'expiry_date',
                'license_issue_date',
                'isp_license_type'
            ]);

        $data['application_type'] = ProcessType::Where('id', $currentInstance->process_type_id)->value('name');
        $data['districts']        = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division']         = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thana']            = ['' => 'Select'] + Area::where('area_type', 3)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['bank_list']        = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();
        $data['multiDistricts']   = Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['multiLicense']     = AllLicense::where('status', 1)->orderBy('id', 'ASC')->pluck('name','id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        return strval(view('REUSELicenseIssue::IPTSP.Issue.master', $data));

    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {

        $this->process_type_id = $currentInstance->process_type_id;
        $app_id                = $request->get('app_id');

        if ($request->get('app_id')) {
            $appData     = IPTSPLicenseIssue::find(Encryption::decodeId($app_id));
            $processData = ProcessList::where([
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id' => $appData->id
            ])->first();
        } else {
            $appData     = new IPTSPLicenseIssue();
            $processData = new ProcessList();
        }

        $appData = $this->storeLicenseData($appData, $request);

        if ($appData->id) {
            if (intval($request->get('shareholderDataCount'))) {
                CommonFunction::storeShareHolderPerson($request, $this->process_type_id, $appData->id);
            }

            // Store Contact Person
            CommonFunction::storeContactPerson($request, $this->process_type_id, $appData->id);

            /** Store into iptsp_connected_isp_info table */
            $this->storeConnectedISPInfo($appData->id, $request);


            //dynamic document start
            DocumentsController::storeAppDocuments($this->process_type_id, $request->doc_type_key, $appData->id, $request);

            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation
            $processData->cat_id = 1;
            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id   = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall
                    // Get last desk and status
                    $submission_sql_param        = [
                        'app_id' => $appData->id,
                        'process_type_id' => $this->process_type_id,
                    ];
                    $process_type_info           = ProcessType::where('id', $this->process_type_id)
                        ->orderBy('id', 'desc')
                        ->first([
                            'form_url',
                            'process_type.process_desk_status_json',
                            'process_type.name',
                        ]);
                    $resubmission_data           = $this->getProcessDeskStatus('resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param);
                    $processData->status_id      = $resubmission_data['process_starting_status'];
                    $processData->desk_id        = $resubmission_data['process_starting_desk'];
                    $processData->process_desc   = 'Re-submitted form applicant';
                    $processData->resubmitted_at = Carbon::now(); // application resubmission Date
                    $license_name = DB::table('license_type')
                    ->where('id', $request->get('type_of_iptsp_licensese'))
                    ->first();
                    $div_name = DB::table('area_info')
                    ->where('area_id', $request->get('iptsp_licensese_area_division'))
                    ->where('area_type', 1)
                    ->pluck('area_nm')
                    ->first();
                    $license_json_data['License Type']= $license_name->name;
                    if(isset($div_name)){
                        $license_json_data['Division']= ($div_name)? $div_name: ''  ;
                    }

                    $processData['license_json']=json_encode($license_json_data);
                    $resultData = $processData->id . '-' . $processData->tracking_no .
                        $processData->desk_id . '-' . $processData->status_id . '-' . $processData->user_id . '-' .
                        $processData->updated_by;

                    $processData->previous_hash = $processData->hash_value ?? "";
                    $processData->hash_value    = Encryption::encode($resultData);

                } else {
                    $processData->status_id = -1;
                    $processData->desk_id   = 0;
                    $license_name = DB::table('license_type')
                    ->where('id', $request->get('type_of_iptsp_licensese'))
                    ->first();
                    $div_name = DB::table('area_info')
                    ->where('area_id', $request->get('iptsp_licensese_area_division'))
                    ->where('area_type', 1)
                    ->pluck('area_nm')
                    ->first();
                    $license_json_data['License Type']= $license_name->name;
                    if(isset($div_name)){
                        $license_json_data['Division']= ($div_name)? $div_name: ''  ;
                    }

                    $processData['license_json']=json_encode($license_json_data);
                }
            }
            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0;
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            $jsonData['Company Name']     = $request->company_name;
            $jsonData['Email']            = Auth::user()->user_email;
            $jsonData['Phone']            = Auth::user()->user_mobile;

            $processData['json_object']   = json_encode($jsonData);
            $processData->save();
        }

        //  Required Documents for attachment
        DocumentsController::storeAppDocuments($this->process_type_id, $request->doc_type_key, $appData->id, $request);

        // Payment info

        $check_payment_type = false;
        if ( ( isset( $request->payment_type ) || $processData->status_id != $this->re_submit_status_id ) && !empty($appData->isptspli_type)) {

            $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $appData->isptspli_type, 1, $request->get('iptsp_licensese_area_division'));
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];
            $check_payment_type   = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );
            $payment_id           = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('IPTSP', $this->process_type_id, $processData->id, $this->table, 'ISS', $appData->id);
        }

        /** if application submitted and status is equal to draft then generate tracking number and payment initiate  ***/
        if ( $request->get( 'actionBtn' ) == 'submit'  ) {
//            if ( empty( $processData->tracking_no ) ) {
//                $trackingPrefix = 'IPTSP-' . date( 'Ymd' ) . '-';
//                CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
//            }
            //Preparing E-nothi Data Start
            $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();
//            commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
            //Preparing E-nothi Data End

            if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
                DB::commit();

                // redirect to Sonali Payment Portal
                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }
        }
        // Send Email for application re-submission
        if ( $processData->status_id == $this->re_submit_status_id ) {

            //Preparing E-nothi Data Start
            $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();
//            commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
            //Preparing E-nothi Data End

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'IPTSP License Issue', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'IPTSP License Issue',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        // for Pay Order
        if ( $check_payment_type && $request->get( 'actionBtn' ) == 'submit' ) {


            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => 0, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => 0, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
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

        if ($processData->status_id == -1) {
            Session::flash('success', 'Successfully updated the Application!');
        } elseif ($processData->status_id == 1) {
            Session::flash('success', 'Successfully Application Submitted !');
        } elseif ($processData->status_id == 2) {
            Session::flash('success', 'Successfully Application Re-Submitted !');
        } else {
            Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]');
        }
        return redirect('client/iptsp-license-issue/list/' . Encryption::encodeId($this->process_type_id));
    }

    public function viewForm($processTypeId, $appId): JsonResponse
    {
        $this->process_type_id = $process_type_id = $processTypeId;
        $decodedAppId          = Encryption::decodeId($appId);
        $processList = ProcessList::where('ref_id', $decodedAppId)
            ->where('process_type_id', $process_type_id)
            ->first(['company_id']);
        $compId = $processList->company_id;
        $data['process_type_id'] = $process_type_id;

        $data['appInfo'] = ProcessList::leftJoin('iptsp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin( 'company_info', 'company_info.id', '=', DB::raw($compId) )
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
            ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
            ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
            ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
            ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
            ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
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

                'reg_office_district.area_nm as reg_office_district_en',
                'reg_office_thana.area_nm as reg_office_thana_en',
                'op_office_district.area_nm as op_office_district_en',
                'op_office_thana.area_nm as op_office_thana_en',
                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',

                'apps.*',
//                'apps.org_per_district as org_per_pdistrict',
                'company_info.incorporation_num',
                'company_info.incorporation_date',

            ]);

        if($data['appInfo']->bulk_status != '1'){
            $cover_ot_dis    = json_decode($data['appInfo']->cover_ot_dis);
            $multi_license   = json_decode($data['appInfo']->multi_license);
            $cover_ot_dis                    = Area::whereIn('area_id', $cover_ot_dis)->first([
                DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_ot_dis_name')
            ]);
            if ($multi_license !== null && !empty($multi_license)){
                $multi_licenses = AllLicense::whereIn('id', $multi_license)->pluck('name')->toArray();
                $data['appInfo']['multi_license'] = implode(', ', $multi_licenses);
            } else {
                $data['appInfo']['multi_license'] = '';
            }

            $data['appInfo']['cover_ot_dis'] = $cover_ot_dis->cover_ot_dis_name;
            $cover_dis = json_decode($data['appInfo']->cover_dis);
            //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
            $cover_dis                    = Area::whereIn('area_id', $cover_dis)->first([
                DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_dis_name')
            ]);
            $data['appInfo']['cover_dis'] = $cover_dis->cover_dis_name;

            /** Fetch data from iptsp_Connected_ISP */
            $data['iptsp_connected_isp_info'] = IPTSPLicenseIssueConnectedISPInfo::where(['iptsp_license_issue_id' => $data['appInfo']['id']])->get();
        }

        $data['appShareholderInfo'] = Shareholder::where(['app_id' => $decodedAppId, 'process_type_id' => $this->process_type_id])->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where('process_type_id', $process_type_id)->where('ref_id', $decodedAppId)->whereNotNull('uploaded_path')->get();
        $data['contact_person']     = ContactPerson::where([
            'app_id' => $decodedAppId,
            'process_type_id' => $this->process_type_id
        ])->get();



        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['contact_upazila_name']  = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
            $data['payment_step_id'] = 2;
            $data['iptsp_licensese_area_division'] =
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId, $process_type_id = 0, $data['appInfo']->isptspli_area_div);
        }

        $data['org_permanent_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_district)->first([
            'area_nm'
        ]);
        $data['org_permanent_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_upazila)->first([
            'area_nm'
        ]);
        $data['applicant_district']     = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_district)->first([
            'area_nm'
        ]);
        if($data['appInfo']->bulk_status != '1') {
            $data['applicant_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_upazila)->first([
                'area_nm'
            ]);
            $data['signatory_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_district)->first([
                'area_nm'
            ])->area_nm;


        $data['signatory_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_thana)->first([
            'area_nm'
        ])->area_nm;
        }
        if($data['appInfo']->bulk_status != '1') {
            if (!empty($data['appInfo']->isptspli_area_div)) {
                $data['license_type_division'] = DB::table('area_info')->where('area_id', $data['appInfo']->isptspli_area_div)->first([
                    'area_nm'
                ])->area_nm;
            }
        }
        if($data['appInfo']->bulk_status != '1') {
            $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
            $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
            $data['multiDistricts'] = Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        }
        if(empty($data['appInfo']['bulk_status'])) {
            if ($data['appInfo']->status_id == 15) {
                // 15 = Approved for license payment
                $data['payment_step_id'] = 2;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            } elseif ($data['appInfo']->status_id == 25) { // 25 = generate license then eligible for second year annual fee

                $data['payment_step_id'] = 3;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            } elseif ($data['appInfo']->status_id == 54) { // 54 = success second annual payment
                $data['payment_step_id'] = 4;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            } elseif ($data['appInfo']->status_id == 55) { // 55 = success fourth year annual payment
                $data['payment_step_id'] = 5;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            } elseif ($data['appInfo']->status_id == 56) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 6;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 57) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 7;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 69) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 8;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 70) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 9;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 71) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 10;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 72) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 11;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 73) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 12;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 74) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 13;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 75) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 14;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 76) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 15;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            }elseif ($data['appInfo']->status_id == 77) { // 56 = success fifth year annual payment
                $data['payment_step_id'] = 16;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
            } elseif ($data['appInfo']->status_id == 46) {
                $data['payment_step_id']                         = 2;
                $data['unfixed_amounts']                         = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isptspli_type, $data['payment_step_id'], $decodedAppId,  $process_type_id = 0, $data['appInfo']->isptspli_area_div);
                $data['pay_order_info']= DB::table('pay_order_info')
                    ->where('pay_order_info_id', $data['appInfo']->payment_id)
                    ->get();

                $data['payment_info'] = SonaliPayment::where([
                    'app_id' => $data['appInfo']->id,
                    'process_type_id' => $processTypeId,
                    'payment_step_id' => 2
                ])->first();
                if(!empty($data['payment_info']->bg_expire_date)) {
                    $data['payment_info']->bg_expire_date = date_format(date_create($data['payment_info']->bg_expire_date), 'Y-m-d');
                }
            }
        }

        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string)view('REUSELicenseIssue::IPTSP.Issue.masterView', $data);

        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function editForm($decoded_process_type_id, $applicationId): JsonResponse
    {
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['process_type_id'] = $decoded_process_type_id;
        $data['vat_percentage']  = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');

        $applicationId = Encryption::decodeId($applicationId);

        $process_type_id = $decoded_process_type_id;
        $data['divisions'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thana']     = ['' => 'Select'] + Area::where('area_type', 3)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['multiDistricts'] =  Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['multiLicense']   =  AllLicense::where('status', 1)->orderBy('id', 'ASC')->pluck('name', 'id')->toArray();

        $data['appInfo'] = ProcessList::leftJoin('iptsp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                $join->on('sfp.app_id', '=', 'process_list.ref_id');
                $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
            })
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

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['process_type_id'] = $process_type_id;

        if($data['appInfo']['cs_photo_base64']) {
            $data['appInfo']['cs_photo_base64'] = CommonFunction::imagePathToBase64( public_path( $data['appInfo']['cs_photo_base64'] ) );
        }

        $shareholders_data = Shareholder::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $process_type_id
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
        foreach (  $shareholders_data as $index => $value ) {
            if ( public_path( $value->shareholders_image ) && ! empty( $value->shareholders_image ) ) {
                $value->image_real_path    = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;
        $data['appDynamicDocInfo']  = ApplicationDocuments::where('process_type_id', $process_type_id)->where('ref_id', $applicationId)->whereNotNull('uploaded_path')->get();
        $contact_data = ContactPerson::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }

        $data['contact_person'] = $contact_data;


        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['contact_upazila_name']  = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        /** Fetch data from iptsp_connected_isp_info */
        $data['iptsp_connected_isp_info'] = IPTSPLicenseIssueConnectedISPInfo::where(['iptsp_license_issue_id'=> $data['appInfo']['id']])->get();

        /** Fetch data from IPTSPLicenseIssue */
        $data['iptsp_license_issue'] = IPTSPLicenseIssue::where(['id'=> $data['appInfo']['id']])->first();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        $data['pay_order_info'] = DB::table('pay_order_payment')
            ->where([
                'app_id' => $data['appInfo']['id'],
                'process_type_id' => $process_type_id,
                'payment_step_id' => 1
            ])->first();
        if (!empty($data['pay_order_info']->pay_order_date)) {
            $data['pay_order_info']->pay_order_formated_date = date_format(date_create($data['pay_order_info']->pay_order_date), 'Y-m-d');
        }
        if (!empty($data['pay_order_info']->bg_expire_date)) {
            $data['pay_order_info']->bg_expire_formated_date = date_format(date_create($data['pay_order_info']->bg_expire_date), 'Y-m-d');
        }

        $public_html = (string)view('REUSELicenseIssue::IPTSP.Issue.masterEdit', $data);
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    private function storeLicenseData($LicenseIssueObj, $request)
    {
        $LicenseIssueObj->company_id = CommonFunction::getUserCompanyWithZero();
        $LicenseIssueObj->org_nm     = $request->get('company_name');
        $LicenseIssueObj->org_type   = $request->get('company_type');
        //Registered Office Address
        $LicenseIssueObj->reg_office_district = $request->get('reg_office_district');
        $LicenseIssueObj->reg_office_thana    = $request->get('reg_office_thana');
        $LicenseIssueObj->reg_office_address  = $request->get('reg_office_address');
        $LicenseIssueObj->reg_office_address2 = $request->get('reg_office_address2');
        //Operational Office Address
        $LicenseIssueObj->op_office_district = $request->get('op_office_district');
        $LicenseIssueObj->op_office_thana    = $request->get('op_office_thana');
        $LicenseIssueObj->op_office_address  = $request->get('op_office_address');
        $LicenseIssueObj->op_office_address2  = $request->get('op_office_address2');
        //Applicant Profile
        $LicenseIssueObj->applicant_name      = $request->get('applicant_name');
        $LicenseIssueObj->applicant_mobile    = $request->get('applicant_mobile');
        $LicenseIssueObj->applicant_telephone = $request->get('applicant_telephone');
        $LicenseIssueObj->applicant_email     = $request->get('applicant_email');
        $LicenseIssueObj->applicant_district  = $request->get('applicant_district');
        $LicenseIssueObj->applicant_thana     = $request->get('applicant_thana');
        $LicenseIssueObj->applicant_address   = $request->get('applicant_address');
        $LicenseIssueObj->applicant_address2  = $request->get('applicant_address2');
        $LicenseIssueObj->total_no_of_share   = $request->get('total_no_of_share');
        $LicenseIssueObj->total_share_value   = $request->get('total_share_value');

        // Declaration
        $LicenseIssueObj->dclar_q1                     = $request->get('declaration_q1');
        $LicenseIssueObj->dclar_q1_date                = !empty($request->get('declaration_q1_date')) ? date('Y-m-d', strtotime($request->get('declaration_q1_date'))) : null;
        $LicenseIssueObj->dclar_q1_text                = $request->get('declaration_q1_text');
        $LicenseIssueObj->dclar_q2                     = $request->get('declaration_q2');
        $LicenseIssueObj->dclar_q2_serv_lst            = $request->get('declaration_q2_service_list');
        $LicenseIssueObj->dclar_q2_licen_num           = $request->get('declaration_q2_license_number');
        $LicenseIssueObj->dclar_q2_comp_name           = $request->get('declaration_q2_company_name');
        $LicenseIssueObj->dclar_q2_shar_holdr_name     = $request->get('declaration_q2_share_holder_name');
        $LicenseIssueObj->dclar_q3                     = $request->get('declaration_q3');
        $LicenseIssueObj->dclar_q3_date                = !empty($request->get('declaration_q3_date')) ? date('Y-m-d', strtotime($request->get('declaration_q3_date'))) : '';
        $LicenseIssueObj->dclar_q3_text                = $request->get('declaration_q3_text');
        $LicenseIssueObj->dclar_q4                     = $request->get('declaration_q4');
        $LicenseIssueObj->dclar_q4_iligl_VoIP_activiti = $request->get('declaration_q4_illegal_VoIP_activities');
        $LicenseIssueObj->dclar_q4_case_no             = $request->get('declaration_q4_case_no');
        $LicenseIssueObj->dclar_q4_amount              = $request->get('declaration_q4_amount');
        $LicenseIssueObj->dclar_q4_chq_bank_drft       = $request->get('declaration_q4_cheque_or_bank_draft_no');
        $LicenseIssueObj->dclar_q4_givn_commision      = $request->get('declaration_q4_given_commision');
        //Name of Authorized Signatory
        $LicenseIssueObj->cs_person_name    = $request->get('contact_signatory_person_name');
        $LicenseIssueObj->cs_designation    = $request->get('contact_signatory_designation');
        $LicenseIssueObj->cs_mobile         = $request->get('contact_signatory_mobile');
        $LicenseIssueObj->cs_person_email   = $request->get('contact_signatory_person_email');
        $LicenseIssueObj->cs_district       = $request->get('contact_signatory_district');
        $LicenseIssueObj->cs_thana          = $request->get('contact_signatory_thana');
        $LicenseIssueObj->cs_person_address = $request->get('contact_signatory_person_address');
        if (isset($request->contact_signatory_photo_base64) && !empty($request->contact_signatory_photo_base64)) {
            $yearMonth = date("Y") . "/" . date("m") . "/";
            $path      = "uploads/signatory/" . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $splited = explode(',', substr($request->contact_signatory_photo_base64, 5), 2);
            if ((count($splited) == 1 && !empty($splited[0]))) {
                $LicenseIssueObj->cs_photo_base64 = $request->contact_signatory_photo_base64;
            } else {
                $imageData                = $splited[1];
                $base64ResizeImage        = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                $base64ResizeImage        = base64_decode($base64ResizeImage);
                $correspondent_photo_name = trim(uniqid('BSCIC_IR-' . '-', true) . '.' . 'jpeg');
                file_put_contents($path . $correspondent_photo_name, $base64ResizeImage);
                $LicenseIssueObj->cs_photo_base64 = $path . $correspondent_photo_name;
            }
        } else {
            if (empty($LicenseIssueObj->auth_person_pic)) {
                $LicenseIssueObj->cs_photo_base64 = Auth::user()->user_pic;
            }
        }

        //Details of Existing ISP License
        $LicenseIssueObj->isp_li_number          = $request->get('isp_license_number');
        $LicenseIssueObj->isp_li_date_expire     = $request->get('isp_license_date_of_expire');
        $LicenseIssueObj->isp_license_types      = $request->get('types_of_isp_license');
        $LicenseIssueObj->isp_license_issue_date = $request->get('isp_license_issue_date');
        if (!empty($request->input('multi_license')) && $request->get('actionBtn') == "submit") {
            $multi_license_array = $request->input('multi_license', []);
            $exploded_array = explode(",", $multi_license_array[0]);
            $LicenseIssueObj->multi_license = json_encode($exploded_array);
        } else {
            $LicenseIssueObj->multi_license = json_encode($request->get('multi_license'));
        }

        //Investment Information
        $LicenseIssueObj->local_investment   = $request->get('local_investment');
        $LicenseIssueObj->pre_val_t_invest   = $request->get('present_value_of_total_investment');
        $LicenseIssueObj->total_investment   = $request->get('total_investment');
        $LicenseIssueObj->foreign_investment = $request->get('foreign_investment');
        $LicenseIssueObj->gr_rev_last_year   = $request->get('gross_revenue_eamed_in_last_year');
        //gross_revenue_eamed_in_last_year_img
        if (isset($request->gross_revenue_eamed_in_last_year_img) && $request->hasFile('gross_revenue_eamed_in_last_year_img')) {
            $yearMonth = date("Y") . "/" . date("m") . "/";
            $path      = 'uploads/iptsp-license-issue/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $_file_path = $request->file('gross_revenue_eamed_in_last_year_img');
            $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
            $_file_path->move($path, $file_path);
            $LicenseIssueObj->gr_rev_last_year_img = $path . $file_path;
        } elseif(isset($request->gross_revenue_eamed_in_last_year_img_searched)){
            $LicenseIssueObj->gr_rev_last_year_img = $request->gross_revenue_eamed_in_last_year_img_searched;
        }

        if (isset($request->prev_license_copy) && $request->hasFile('prev_license_copy')) {
            $yearMonth = date("Y") . "/" . date("m") . "/";
            $path      = 'uploads/iptsp-license-issue/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $_file_path = $request->file('prev_license_copy');
            $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
            $_file_path->move($path, $file_path);
            $LicenseIssueObj->prev_license_copy = $path . $file_path;
        }

        //Employee Information
        $LicenseIssueObj->emp_info       = $request->get('employee_information');
        $LicenseIssueObj->tit_specialist = $request->get('total_it_specialist');
        //Types of IPTSP License
        $LicenseIssueObj->isptspli_type     = $request->get('type_of_iptsp_licensese');
        $LicenseIssueObj->isptspli_area_div = $request->get('iptsp_licensese_area_division');
        //Coverage Area Information
        $LicenseIssueObj->cover_area   = $request->get('coverage_area');
        $LicenseIssueObj->cover_dis    = json_encode($request->get('coverage_district'));
        $LicenseIssueObj->t_cover_area = $request->get('total_coverage_area');
        //Coverage Out of Area In Rural Area Information
        $LicenseIssueObj->cover_ot_area = $request->get('coverage_out_of_area');
        $LicenseIssueObj->cover_ot_dis  = json_encode($request->get('coverage_out_of_district'));
        $LicenseIssueObj->tc_out_area   = $request->get('total_coverage_out_of_area');
        // Date of Commencement of the Service
        $LicenseIssueObj->commencement_date = !empty($request->get('commencement_date')) ? date('Y-m-d', strtotime($request->get('commencement_date'))) : null;
        //Information of Existing Subscriber Level
        $LicenseIssueObj->existsubs_dial_up    = $request->get('existing_subscriber_dial_up');
        $LicenseIssueObj->existsubs_corporate  = $request->get('existing_subscriber_corporate');
        $LicenseIssueObj->existsubs_individual = $request->get('existing_subscriber_individual');
        $LicenseIssueObj->existsubs_broadband  = $request->get('existing_subscriber_broadband');
        $LicenseIssueObj->exsubs_corpc_namenum = $request->get('existing_subscriber_name_with_corporate_clients_subscriber_number');
        //  Wired Network Information
        $LicenseIssueObj->wirenet_len_lid_cabl = $request->get('wired_network_length_of_laid_cable');
        $LicenseIssueObj->wir_net_optic_fiber  = $request->get('wired_network_optical_fiber');
        $LicenseIssueObj->wire_net_dsl         = $request->get('wired_network_dsl');
        $LicenseIssueObj->wire_net_adsl        = $request->get('wired_network_adsl');
        $LicenseIssueObj->wire_net_utp         = $request->get('wired_network_utp');
        $LicenseIssueObj->wire_net_stp         = $request->get('wired_network_stp');
        $LicenseIssueObj->wire_net_other       = $request->get('wired_network_other');
        //  Bandwidth Details for Last Years Level
        $LicenseIssueObj->bnd_lst_totl_alloc = $request->get('bandwidth_lastyear_total_allocation');
        $LicenseIssueObj->bnd_lst_totl_util  = $request->get('bandwidth_lastyear_total_utilization');
        $LicenseIssueObj->bnd_lst_iig        = $request->get('bandwidth_lastyear_iig');
        $LicenseIssueObj->bnd_lst_iplc       = $request->get('bandwidth_lastyear_iplc');
        $LicenseIssueObj->bnd_lst_vsat       = $request->get('bandwidth_lastyear_vsat');
        //Uplink Information
        $LicenseIssueObj->bnd_lst_upli_iig  = $request->get('bandwidth_lastyear_uplink_iig');
        $LicenseIssueObj->bnd_lst_upli_iplc = $request->get('bandwidth_lastyear_uplink_iplc');
        $LicenseIssueObj->bnd_lst_upli_vsat = $request->get('bandwidth_lastyear_uplink_vsat');
        //Medium for Uplink Allocation
        $LicenseIssueObj->bnd_lst_med_upli_iig  = $request->get('bandwidth_lastyear_medium_uplink_iig');
        $LicenseIssueObj->bnd_lst_med_upli_iplc = $request->get('bandwidth_lastyear_medium_uplink_iplc');
        $LicenseIssueObj->bnd_lst_med_upli_vsat = $request->get('bandwidth_lastyear_medium_uplink_vsat');
        //Downlink Allocation Information
        $LicenseIssueObj->bnd_lst_downli_iig  = $request->get('bandwidth_lastyear_downlink_iig');
        $LicenseIssueObj->bnd_lst_downli_iplc = $request->get('bandwidth_lastyear_downlink_iplc');
        $LicenseIssueObj->bnd_lst_downli_vsat = $request->get('bandwidth_lastyear_downlink_vsat');
        //Medium Downlink Allocation Information
        $LicenseIssueObj->bnd_lst_med_downli_iig  = $request->get('bandwidth_lastyear_medium_downlink_iig');
        $LicenseIssueObj->bnd_lst_med_downli_iplc = $request->get('bandwidth_lastyear_medium_downlink_iplc');
        $LicenseIssueObj->bnd_lst_med_downli_vsat = $request->get('bandwidth_lastyear_medium_downlink_vsat');
        //Provider Information
        $LicenseIssueObj->bnd_lst_prov_name = $request->get('bandwidth_lastyear_provider_name');
        $LicenseIssueObj->bnd_lst_prov_iig  = $request->get('bandwidth_lastyear_provider_iig');
        $LicenseIssueObj->bnd_lst_prov_iplc = $request->get('bandwidth_lastyear_provider_iplc');
        $LicenseIssueObj->bnd_lst_prov_vsat = $request->get('bandwidth_lastyear_provider_vsat');
        //Average Minimam Growth Rate of Subscribers for per year Information
        $LicenseIssueObj->subsc_indivi = $request->get('subscriber_individual');
        $LicenseIssueObj->subsc_corpo  = $request->get('subscriber_corporate');
        //  POP Information
        $LicenseIssueObj->no_of_POP = $request->get('no_of_POP');
        $LicenseIssueObj->location  = $request->get('location');
        //Other License Awarded by the Commission to the Licensee
        $LicenseIssueObj->otrlis_award_info = $request->get('other_license_awarded_info');
        //  Wireless Network Information
        $LicenseIssueObj->wirls_num_bis_pop  = $request->get('wireless_number_of_bis_pop');
        $LicenseIssueObj->wireless_frequency = $request->get('wireless_frequency');
        //Backup Information
        $LicenseIssueObj->bkupinf_num_of_vsat   = $request->get('backup_info_of_number_of_vsat');
        $LicenseIssueObj->bkupinf_uplin_alloc   = $request->get('backup_info_of_uplink_allocation');
        $LicenseIssueObj->bkupinf_downlin_alloc = $request->get('backup_info_of_downlink_allocation');
        $LicenseIssueObj->bkupinf_uplin_freq    = $request->get('backup_info_of_uplink_frequency');
        $LicenseIssueObj->bkupinf_dwnlin_freq   = $request->get('backup_info_of_downlink_frequency');
        $LicenseIssueObj->bkupinf_desc          = $request->get('backup_info_of_description');
        //  Per Subscriber Average width
        $LicenseIssueObj->per_subsc_indivi = $request->get('per_subscriber_individual');
        $LicenseIssueObj->per_subsc_corpo  = $request->get('per_subscriber_corporate');

        $LicenseIssueObj->accept_terms = $request->get('accept_terms');

        $LicenseIssueObj->status     = 1;
        $LicenseIssueObj->updated_at = Carbon::now();
        $LicenseIssueObj->save();

        return $LicenseIssueObj;
    }

    public function storeShareHolder($appData, $request)
    {
        if ($request->get('shareholderDataCount') > 0) {
            IPTSPLicenseIssueShareholder::where('iptsp_license_issue_id', $appData->id)->delete();
            foreach ($request->shareholder_name as $key => $data) {
                $shareHolderData                         = new IPTSPLicenseIssueShareholder();
                $shareHolderData->iptsp_license_issue_id = $appData->id;
                $shareHolderData->iptsp_license_issue_id = $appData->id;
                $shareHolderData->name                   = $request->shareholder_name[$key];
                $shareHolderData->nationality            = $request->shareholder_nationality[$key];
                $shareHolderData->passport               = $request->shareholder_passport[$key];
                $shareHolderData->nid                    = $request->shareholder_nid[$key];
                $shareHolderData->dob                    = date('Y-m-d', strtotime($request->shareholder_dob[$key]));
                $shareHolderData->designation            = $request->shareholder_designation[$key];
                $shareHolderData->mobile                 = $request->shareholder_mobile[$key];
                $shareHolderData->email                  = $request->shareholder_email[$key];
                $shareHolderData->share_percent          = $request->shareholder_share_of[$key];

                if (!empty($request->correspondent_photo_base64[$key])) {
                    $yearMonth = date("Y") . "/" . date("m") . "/";
                    $path      = "uploads/shareholder/" . $yearMonth;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $splited                  = explode(',', substr($request->correspondent_photo_base64[$key], 5), 2);
                    $imageData                = $splited[1];
                    $base64ResizeImage        = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                    $base64ResizeImage        = base64_decode($base64ResizeImage);
                    $correspondent_photo_name = trim(uniqid('BSCIC_IR-' . '-', true) . '.' . 'jpeg');
                    file_put_contents($path . $correspondent_photo_name, $base64ResizeImage);
                    $shareHolderData->image = $path . $correspondent_photo_name;
                } else {
                    if (empty($appData->auth_person_pic)) {
                        $shareHolderData->image = Auth::user()->user_pic;
                    }
                }
                $shareHolderData->created_at = Carbon::now();

                $shareHolderData->save();
            }
        }
    }

    public function storeContactPerson($appData, $request)
    {

        if (isset($request->contact_person_name) && count($request->contact_person_name) > 0) {
            IPTSPLicenseIssueContactPerson::where('iptsp_license_issue_id', $appData->id)->delete();
            foreach ($request->contact_person_name as $index => $value) {
                $contactPersonObj                         = new IPTSPLicenseIssueContactPerson();
                $contactPersonObj->iptsp_license_issue_id = $appData->id;
                $contactPersonObj->name                   = $value;
                $contactPersonObj->designation            = isset($request->contact_designation) ? $request->contact_designation[$index] : '';
                $contactPersonObj->mobile                 = isset($request->contact_mobile) ? $request->contact_mobile[$index] : '';
                $contactPersonObj->email                  = isset($request->contact_person_email) ? $request->contact_person_email[$index] : '';
                $contactPersonObj->district               = isset($request->contact_district) ? $request->contact_district[$index] : '';
                $contactPersonObj->upazila                = isset($request->contact_thana) ? $request->contact_thana[$index] : '';
                $contactPersonObj->address                = isset($request->contact_person_address) ? $request->contact_person_address[$index] : '';
                if (isset($request->contact_photo_base64) && !empty($request->contact_photo_base64[$index])) {
                    $yearMonth = date("Y") . "/" . date("m") . "/";
                    $path      = "uploads/contact/" . $yearMonth;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $splited                  = explode(',', substr($request->contact_photo_base64[$index], 5), 2);
                    $imageData                = $splited[1];
                    $base64ResizeImage        = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                    $base64ResizeImage        = base64_decode($base64ResizeImage);
                    $correspondent_photo_name = trim(uniqid('BSCIC_IR-' . '-', true) . '.' . 'jpeg');
                    file_put_contents($path . $correspondent_photo_name, $base64ResizeImage);
                    $contactPersonObj->image = $path . $correspondent_photo_name;

                } else {
                    if (empty($appData->auth_person_pic)) {
                        $contactPersonObj->image = Auth::user()->user_pic;
                    }
                }
                $contactPersonObj->created_at = date('Y-m-d H:i:s');
                $contactPersonObj->save();
            }
        }
    }

    public function storeConnectedISPInfo($appDataId, $request)
    {
        if (isset($request->institution_type) && count($request->institution_type) > 0) {
            IPTSPLicenseIssueConnectedISPInfo::where('iptsp_license_issue_id', $appDataId)->delete();
            foreach ($request->institution_type as $index => $value) {
                if (empty($value)) {
                    continue;
                }
                $serviceProviderObj                         = new IPTSPLicenseIssueConnectedISPInfo();
                $serviceProviderObj->iptsp_license_issue_id = $appDataId;
                $serviceProviderObj->institution_type       = $value;
                $serviceProviderObj->institution_name       = $request->institution_name[$index];
                $serviceProviderObj->created_at             = date('Y-m-d H:i:s');
                $serviceProviderObj->save();
            }
        }
    }

    public function unfixedAmountsForGovtServiceFee($isp_license_type, $payment_step_id, $app_id = 0, $process_type_id = 0, $division_id = 0) {
        date_default_timezone_set("Asia/Dhaka");
        if (empty($this->process_type_id)) $this->process_type_id = $process_type_id;

        $vat_percentage = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
        if ( empty( $vat_percentage ) ) {
            DB::rollback();
            Session::flash( 'error', 'Please, configure the value for VAT.[INR-1026]' );
            return redirect()->back()->withInput();
        }

        $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
            'process_type_id' => $this->process_type_id,
            'payment_step_id' => $payment_step_id,
            'license_type_id' => $isp_license_type,
            'status'          => 1,
        ] )->When(in_array($division_id, [2, 14]), function ($query) use ($division_id) {
            return $query->where('division_id', $division_id);
        })->first();

        $unfixed_amount_array = [];

        if ($payment_step_id == 1) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => 0, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => 0, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
        } elseif ($payment_step_id == 2) {

            $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where( [
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 3,
                'license_type_id' => $isp_license_type,
                'status'          => 1,
            ] )->first();

            //TODO:: delay fee calculation
            $submissionPaymentData = SonaliPayment::where([
                'app_id' => $app_id,
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1,
                'payment_status' => 1
            ])->first(['updated_at']); // Submission payment date

            $delay_fee = 0;
            $delay_vat_fee = 0;
            $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date('Y-m-d', strtotime($submissionPaymentData->updated_at)) : date('Y-m-d');
            $currentDateTime = date('Y-m-d', strtotime('-1 year'));

            if ($currentDateTime > $submissionPaymentDateTime) {
                $yarly_delay_fee = (($SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount) * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = intval($yarly_delay_fee) / 365;
                $date_diff = date_diff(date_create($currentDateTime),date_create($submissionPaymentDateTime));
                $delay_day_count = abs(intval($date_diff->format('%r%a')));
                $delay_fee = $delay_day_count * $daily_delay_fee;
                $delay_vat_fee = ($delay_fee * intval($vat_percentage)) / 100; // 15% vat over delay fee
            }

            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => $spPaymentAmountforAnnualFee->pay_amount, //1st year govt-annual-fee
                8 => $delay_fee, //govt-delay-fee
                9 => ($spPaymentAmountforAnnualFee->pay_amount * $vat_percentage) / 100, //govt-annual-vat-fee
                10 => $delay_vat_fee //govt-delay-vat-fee
            ];

        } elseif(in_array($payment_step_id, [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])) {
            //TODO::Delay fee calculation
            $annualFeeData = AnnualFeeInfo::where([
                'process_type_id' => $this->process_type_id,
                'app_id' => $app_id,
                'status' => 0
            ])->first();
            $delay_fee = 0;
            $delay_vat_fee = 0;
            $paymentLastDate = strval($annualFeeData->payment_due_date);
            $currentDateTime = date('Y-m-d');
            if ($currentDateTime > $paymentLastDate) {
                $yarly_delay_fee = ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = intval($yarly_delay_fee) / 365;
                $date_diff = date_diff(date_create($currentDateTime),date_create($paymentLastDate));
                $delay_day_count = abs(intval($date_diff->format('%r%a')));
                $delay_fee = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                $delay_vat_fee = ($delay_fee * intval($vat_percentage)) / 100; // 15% vat over delay fee
            }
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => $SpPaymentAmountConfData->pay_amount,
                4 => $SpPaymentAmountConfData->pay_amount, // Vendor-Vat-Fee
                5 => $SpPaymentAmountConfData->pay_amount, // Govt-Vat-Fee
                6 => $SpPaymentAmountConfData->pay_amount, //govt-vendor-vat-fee
                7 => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                8 => $delay_fee, //govt-delay-fee
                9 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, //govt-annual-vat-fee
                10 => $delay_vat_fee //govt-delay-vat-fee
            ];
        }


        return $unfixed_amount_array;
    }

}
