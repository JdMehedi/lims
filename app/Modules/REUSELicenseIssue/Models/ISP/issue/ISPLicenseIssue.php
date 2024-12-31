<?php

namespace App\Modules\REUSELicenseIssue\Models\ISP\issue;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Models\ENothiSubmissionInfo;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
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
use App\Modules\SonaliPayment\Models\AnnualFeeInfo;
use PhpParser\Node\Stmt\TryCatch;

class ISPLicenseIssue extends Model implements FormInterface
{

    protected $table = 'isp_license_issue';
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
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();
        $data['process_type_id']  = $currentInstance->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where('id', $currentInstance->process_type_id)->value('name');
        $data['districts']        = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division']         = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thana']   = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['bank_list']        = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();
        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();
        return strval(view('REUSELicenseIssue::ISP.Issue.master', $data));
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $app_id                = $request->get('app_id');

        //E-nothi information array
        $applicationInfo = [];
        $shareHolderArr = [];
        $contactArr = [];
        $documentsArr = [];
        if ($request->get('app_id')) {
            $appData     = ISPLicenseIssue::find(Encryption::decodeId($app_id));
            $processData = ProcessList::where([
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id' => $appData->id
            ])->first();
        } else {
            $appData     = new ISPLicenseIssue();
            $processData = new ProcessList();
        }

        $appData = $this->storeLicenseData($appData, $request);

        if ($appData->id) {
            // Store ShareHolder Person
            if (intval($request->get('shareholderDataCount'))) {
                $shareHolderArr = CommonFunction::storeShareHolderPerson($request, $this->process_type_id, $appData->id);
            }

            // Store Contact Person
            $contactArr = CommonFunction::storeContactPerson($request, $this->process_type_id, $appData->id);

            // Store Equipment list
            $this->storeISPEquipment($appData->id, $request);

            // Store TariffChart list
            $this->storeISPTariffChart($appData->id, $request);

            //Dynamic Document Store
            $documentsArr = DocumentsController::storeAppDocuments($currentInstance->process_type_id, $request->doc_type_key, $appData->id, $request);
            // Store Process list Data
            $processData = $this->storeProcessListData($request, $processData, $appData);
        };

        //  Required Documents for attachment
        DocumentsController::storeAppDocuments($this->process_type_id, $request->doc_type_key, $appData->id, $request);

        //=================================================payment code==========================
        $check_payment_type = false;
        if ((isset($request->payment_type) || $processData->status_id != $this->re_submit_status_id) && !empty($appData->isp_license_type)) {
            $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee($appData->isp_license_type, 1);
            $contact_info         = [
                'contact_name' => $request->get('contact_name'),
                'contact_email' => $request->get('contact_email'),
                'contact_no' => $request->get('contact_no'),
                'contact_address' => $request->get('contact_address'),
            ];
            $check_payment_type   = (!empty($request->get('payment_type')) && $request->get('payment_type') === 'pay_order');
            $payment_id           = !$check_payment_type ? $this->storeSubmissionFeeData($appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('ISP', $this->process_type_id, $processData->id, $this->table, 'ISS', $appData->id);
        }

        /** if application submitted and status is equal to draft then generate tracking number and payment initiate  ***/
        if ($request->get('actionBtn') == 'submit' && $processData->status_id == $this->draft_status_id)  {
            if (empty($appData->tracking_no)) {
//                $trackingPrefix = 'ISP-' . date('Ymd') . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
                #CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
                #CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table);
//                CommonFunction::generateUniqueTrackingNumber('ISP',$this->process_type_id, $processData->id, $this->table, 'ISS',$appData->id);
            }

            /*
            //Preparing E-nothi Data Start
            $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();

              commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
               */

            if ($request->get('payment_type') !== 'pay_order') {
                DB::commit();

                // redirect to Sonali Payment Portal
                return SonaliPaymentController::RedirectToPaymentPortal(Encryption::encodeId($payment_id));
            }

        }


        // Send Email for application re-submission
        if ($processData->status_id == $this->re_submit_status_id) {
          /*
            //Preparing E-nothi Data Start
            $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();

            commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
            */
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'ISP License Issue', '{$trackingNumber}' => $processData->tracking_no], $userMobile);
            CommonFunction::sendEmailForReSubmission($processData);
        }

        // for Pay Order
        if ($check_payment_type && in_array($request->get('actionBtn'), ['submit', 'Re-submit'])) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $request->get('pay_amount'), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => $request->get('vat_on_pay_amount'), // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
            $contact_info         = [
                'contact_name' => $request->get('contact_name'),
                'contact_email' => $request->get('contact_email'),
                'contact_no' => $request->get('contact_no'),
                'contact_address' => $request->get('contact_address'),
            ];
            $this->storeSubmissionFeeDataV2($appData->id, 1, $contact_info, $unfixed_amount_array, $request);
        }

        DB::commit();

       if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
           CommonFunction::DNothiRequest($processData->id, $request->get( 'actionBtn' ));

       }

        if ($processData->status_id == $this->draft_status_id) {
            Session::flash('success', 'Successfully updated the Application!');
        } elseif ($processData->status_id == $this->submitted_status_id) {
            Session::flash('success', 'Successfully Application Submitted !');
        } elseif ($processData->status_id == $this->re_submit_status_id) {
            Session::flash('success', 'Successfully Application Re-Submitted !');
        } else {
            Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]');
        }

        return redirect('client/isp-license-issue/list/' . Encryption::encodeId($this->process_type_id));
    }

    public function viewForm($processTypeId, $appId): JsonResponse
    {
        $this->process_type_id = $processTypeId;
        $decodedAppId          = Encryption::decodeId($appId);
        $process_type_id       = $this->process_type_id;

        $data['process_type_id'] = $process_type_id;


        $data['appInfo'] = ProcessList::leftJoin('isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                $join->on('sfp.app_id', '=', 'process_list.ref_id');
                $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('pay_order_payment as pop', function ($join) use ($process_type_id) {
                $join->on('pop.app_id', '=', 'process_list.ref_id');
                $join->on('pop.process_type_id', '=', DB::raw($process_type_id));
            })
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
            ->leftJoin('area_info as contact_district', 'contact_district.area_id', '=', 'apps.cntct_prsn_district')
            ->leftJoin('area_info as contact_thana', 'contact_thana.area_id', '=', 'apps.cntct_prsn_upazila')
            ->leftJoin('area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division')
            ->leftJoin('area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district')
            ->leftJoin('area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila')
            ->leftJoin('area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district')
            ->leftJoin('area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana')
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
                'process_list.bulk_status',
                'ps.status_name',
                'process_type.form_url',
                'reg_office_district.area_nm as reg_office_district_en',
                'reg_office_thana.area_nm as reg_office_thana_en',
                'op_office_district.area_nm as op_office_district_en',
                'op_office_thana.area_nm as op_office_thana_en',
                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',
                'contact_district.area_nm as contact_dis_nm',
                'contact_thana.area_nm as contact_thana_nm',
                'apps.*',

                'isp_license_division_info.area_nm as isp_license_division',
                'isp_license_district_info.area_nm as isp_license_district',
                'isp_license_upazila_info.area_nm as isp_license_upazila',

                'location_of_ins_district.area_nm as location_of_ins_district_en',
                'location_of_ins_thana.area_nm as location_of_ins_thana_en',

                'sfp.contact_name as sfp_contact_name',
                'sfp.id as payment_id',
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
            ]);

        $data['appShareholderInfo'] = Shareholder::where(['app_id' => $decodedAppId, 'process_type_id' => $this->process_type_id])->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where('process_type_id', $process_type_id)->where('ref_id', $decodedAppId)->whereNotNull('uploaded_path')->orderBy('created_at', 'desc')->get();

//       Fetch data from isp_license_equipment_list
        $data['isp_equipment_list'] = ISPLicenseEquipmentList::where(['isp_license_issue_id' => $data['appInfo']['id']])->get();

//         Fetch data from isp_license_tariff_chart
        $data['isp_tariff_chart_list'] = ISPLicenseTariffChart::where(['isp_license_issue_id' => $data['appInfo']['id']])->get();
        $data['contact_person']        = ContactPerson::where([
            'app_id' => $decodedAppId,
            'process_type_id' => $this->process_type_id
        ])->get();


        foreach ($data['contact_person'] as $key => $item) {

            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');

            $data['contact_person'][$key]['contact_upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        if(empty($data['appInfo']['bulk_status'])) {
            if ($data['appInfo']->status_id == 15) { // 15 = Approved for license payment
            $data['payment_step_id'] = 2;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        } elseif ($data['appInfo']->status_id == 65) { // 25 = generate license then eligible for second year annual fee
            $data['payment_step_id'] = 3;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        } elseif ($data['appInfo']->status_id == 54) { // 54 = success second annual payment
            $data['payment_step_id'] = 4;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        } elseif ($data['appInfo']->status_id == 55) { // 55 = success fourth year annual payment
            $data['payment_step_id'] = 5;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        } elseif ($data['appInfo']->status_id == 56) { // 56 = success fifth year annual payment
            $data['payment_step_id'] = 6;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        } elseif ($data['appInfo']->status_id == 46) {
            $data['payment_step_id']                         = 2;
            $data['unfixed_amounts']                         = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
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
        } elseif ($data['appInfo']->status_id == 64) {
                $data['payment_step_id']  = 7;
                $data['unfixed_amounts']  = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);

                $data['payment_info'] = SonaliPayment::where([
                    'app_id' => $data['appInfo']->id,
                    'process_type_id' => $processTypeId,
                    'payment_step_id' => 7,
                    'is_bg' => 1
                ])->first();
                if(!empty($data['payment_info']->bg_expire_date)) {
                    $data['payment_info']->bg_expire_date = date_format(date_create($data['payment_info']->bg_expire_date), 'Y-m-d');
                }
            } elseif ($data['appInfo']->status_id == 60) {
                $data['payment_step_id'] = 7;
                $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        }
//            elseif ($data['appInfo']->status_id == 65) {
//                $data['payment_step_id']                         = 7;
//                $data['unfixed_amounts']                         = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
//                $data['pay_order_info']= DB::table('pay_order_info')
//                    ->where('pay_order_info_id', $data['appInfo']->payment_id)
//                    ->get();
//
//                $data['payment_info'] = SonaliPayment::where([
//                    'app_id' => $data['appInfo']->id,
//                    'process_type_id' => $processTypeId,
//                    'payment_step_id' => 2
//                ])->first();
//                if(!empty($data['payment_info']->bg_expire_date)) {
//                    $data['payment_info']->bg_expire_date = date_format(date_create($data['payment_info']->bg_expire_date), 'Y-m-d');
//                }
//            }
        }
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();
        $public_html = (string)view('REUSELicenseIssue::ISP.Issue.masterView', $data);

        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function editForm($decoded_process_type_id, $applicationId): JsonResponse
    {
        $data['process_type_id'] = $decoded_process_type_id;
        $data['vat_percentage']  = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');
        $applicationId           = Encryption::decodeId($applicationId);
        $process_type_id         = $decoded_process_type_id;


        $data['divisions'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thana']   = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['appInfo'] = ProcessList::leftJoin('isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                $join->on('sfp.app_id', '=', 'process_list.ref_id');
                $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('pay_order_payment as pop', function ($join) use ($process_type_id) {
                $join->on('pop.app_id', '=', 'process_list.ref_id');
                $join->on('pop.process_type_id', '=', DB::raw($process_type_id));
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
                'sfp.id as payment_id',
                'sfp.contact_name as pop_name',
                'sfp.contact_email as pop_email',
                'sfp.contact_no as pop_mobile',
                'sfp.address as pop_address',
                'sfp.is_pay_order_verified',
                'sfp.payment_type'
            ]);

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $shareholders_data = Shareholder::where([
            'app_id' => $applicationId,
            'process_type_id' => $process_type_id
        ])
            ->get([
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
                $value->image_real_path    = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        /** Fetch data from isp_license_equipment_list */
        $data['isp_equipment_list'] = ISPLicenseEquipmentList::where(['isp_license_issue_id' => $data['appInfo']['id']])->get();

        /** Fetch data from isp_license_tariff_chart */
        $data['isp_tariff_chart_list'] = ISPLicenseTariffChart::where(['isp_license_issue_id' => $data['appInfo']['id']])->get();

        /** Fetch data from isp_license_contact_person */
//        $data['contact_person'] = ContactPerson::where( [
//            'app_id'          => $applicationId,
//            'process_type_id' => $process_type_id
//        ] )->get();

        $contact_data = ContactPerson::where([
            'app_id' => $applicationId,
            'process_type_id' => $process_type_id
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

        $data['pay_order_info']= DB::table('pay_order_info')
                                   ->where('pay_order_info_id', $data['appInfo']->payment_id)
                                   ->get();
        $data['payment_info'] = SonaliPayment::find($data['appInfo']->payment_id);
        $public_html = (string)view('REUSELicenseIssue::ISP.Issue.masterEdit', $data);

        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    private function storeLicenseData($LicenseIssueObj, $request)
    {

        try {
            $LicenseIssueObj->org_nm   = $request->get('company_name');
            $LicenseIssueObj->org_type = $request->get('company_type');
            $LicenseIssueObj->incorporation_num   = $request->get('incorporation_num');
            $LicenseIssueObj->incorporation_date = $request->get('incorporation_date');
            $LicenseIssueObj->reg_office_district = $request->get('reg_office_district');
            $LicenseIssueObj->reg_office_thana    = $request->get('reg_office_thana');
            $LicenseIssueObj->reg_office_address  = $request->get('reg_office_address');
            $LicenseIssueObj->reg_office_address2 = $request->get('reg_office_address2');
            $LicenseIssueObj->op_office_district  = $request->get('op_office_district');
            $LicenseIssueObj->op_office_thana     = $request->get('op_office_thana');
            $LicenseIssueObj->op_office_address   = $request->get('op_office_address');
            $LicenseIssueObj->op_office_address2  = $request->get('op_office_address2');

            $LicenseIssueObj->applicant_name      = $request->get('applicant_name');
            $LicenseIssueObj->applicant_mobile    = $request->get('applicant_mobile');
            $LicenseIssueObj->applicant_telephone = $request->get('applicant_telephone');
            $LicenseIssueObj->applicant_email     = $request->get('applicant_email');
            $LicenseIssueObj->applicant_district  = $request->get('applicant_district');
            $LicenseIssueObj->applicant_thana     = $request->get('applicant_thana');
            $LicenseIssueObj->applicant_address   = $request->get('applicant_address');
            $LicenseIssueObj->applicant_address2  = $request->get('applicant_address2');


            $typeOfIspLicense                      = $request->get('type_of_isp_licensese');
            $LicenseIssueObj->isp_license_type     = $typeOfIspLicense;
            $LicenseIssueObj->isp_license_division = $request->get('isp_licensese_area_division');
            $LicenseIssueObj->isp_license_district = $request->get('isp_licensese_area_district');
            $LicenseIssueObj->isp_license_upazila  = $request->get('isp_licensese_area_thana');

            $LicenseIssueObj->location_of_ins_district = $request->get('location_of_ins_district');
            $LicenseIssueObj->location_of_ins_thana    = $request->get('location_of_ins_thana');
            $LicenseIssueObj->location_of_ins_address  = $request->get('location_of_ins_address');
            $LicenseIssueObj->location_of_ins_address2 = $request->get('location_of_ins_address2');

            $LicenseIssueObj->home       = $request->get('home');
            $LicenseIssueObj->cyber_cafe = $request->get('cyber_cafe');
            $LicenseIssueObj->office     = $request->get('office');
            $LicenseIssueObj->others     = $request->get('others');

            $LicenseIssueObj->corporate_user = $request->get('corporate_user');
            $LicenseIssueObj->personal_user  = $request->get('personal_user');
            $LicenseIssueObj->branch_user    = $request->get('branch_user');
            // list_of_clients
            if ($request->hasFile('list_of_clients')) {
                $yearMonth = date('Y') . '/' . date('m') . '/';
                $path      = 'uploads/isp-license-issue/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('list_of_clients');
                $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $LicenseIssueObj->list_of_clients = $path . $file_path;
            }


            if ($typeOfIspLicense == 1) {
                $LicenseIssueObj->isp_license_division = null;
                $LicenseIssueObj->isp_license_district = null;
                $LicenseIssueObj->isp_license_upazila  = null;
            } elseif ($typeOfIspLicense == 2) {
                $LicenseIssueObj->isp_license_district = null;
                $LicenseIssueObj->isp_license_upazila  = null;
            } elseif ($typeOfIspLicense == 3) {
                $LicenseIssueObj->isp_license_upazila = null;
            }

            $LicenseIssueObj->business_plan       = $request->get('business_plan');
            $LicenseIssueObj->declaration_q1      = $request->get('declaration_q1');
            $LicenseIssueObj->declaration_q1_text = $request->get('declaration_q1_text');
            $LicenseIssueObj->declaration_q2      = $request->get('declaration_q2');
            $LicenseIssueObj->declaration_q2_text = $request->get('declaration_q2_text');
            $LicenseIssueObj->declaration_q3      = $request->get('declaration_q3');
            $LicenseIssueObj->status              = 1;
            $LicenseIssueObj->updated_at = Carbon::now();
            $LicenseIssueObj->company_id          = CommonFunction::getUserCompanyWithZero();
            $LicenseIssueObj->total_no_of_share   = $request->get('total_no_of_share');
            $LicenseIssueObj->total_share_value   = $request->get('total_share_value');
            //images
            if ($request->hasFile('declaration_q3_images')) {
                $yearMonth = date('Y') . '/' . date('m') . '/';
                $path      = 'uploads/isp-license-issue/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('declaration_q3_images');
                $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $LicenseIssueObj->declaration_q3_doc = $path . $file_path;
            }
            $LicenseIssueObj->save();
        }catch (\Exception $e) {
            dd($e->getMessage());
        }

        return $LicenseIssueObj;
    }

    private function storeProcessListData($request, $processListObj, $appData)
    {
        $processListObj->company_id = CommonFunction::getUserCompanyWithZero();
        //Set category id for process differentiation
        $processListObj->cat_id = 1;
        if ($request->get('actionBtn') === 'draft') {
            $processListObj->status_id = $this->draft_status_id;
            $processListObj->desk_id   = 0;
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
            $resubmission_data              = $this->getProcessDeskStatus('resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param);
            $processListObj->status_id      = $resubmission_data['process_starting_status'];
            $processListObj->desk_id        = $resubmission_data['process_starting_desk'];
            $processListObj->user_id        = $processListObj->shortfall_resubmit_to;
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
            $processListObj->hash_value    = Encryption::encode($resultData);

        } else {
            $processListObj->status_id = $this->draft_status_id;
            $processListObj->desk_id   = 0;
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
        }

        $processListObj->ref_id          = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->office_id       = 0;
        $jsonData['Applicant Name']      = Auth::user()->user_first_name;
        $jsonData['Company Name']        = $request->company_name;
        $jsonData['Email']               = Auth::user()->user_email;
        $jsonData['Phone']               = Auth::user()->user_mobile;
        $processListObj['json_object']   = json_encode($jsonData);

        $processListObj->save();

        return $processListObj;

    }

    private function storeISPEquipment($appDataId, $request)
    {
        if (isset($request->equipment_name) && count($request->equipment_name) > 0) {
            ISPLicenseEquipmentList::where('isp_license_issue_id', $appDataId)->delete();

            foreach ($request->equipment_name as $index => $value) {
                $equipObj                       = new ISPLicenseEquipmentList();
                $equipObj->isp_license_issue_id = $appDataId;
                $equipObj->name                 = $value;
                $equipObj->brand_model          = $request->equipment_brand_model[$index];
                $equipObj->quantity             = $request->equipment_quantity[$index];
                $equipObj->remarks              = $request->equipment_remarks[$index];
                $equipObj->created_at           = date('Y-m-d H:i:s');
                $equipObj->save();
            }
        }
    }

    private function storeISPTariffChart($appDataId, $request)
    {
        if (isset($request->tariffChart_package_name_plan) && count($request->tariffChart_package_name_plan) > 0) {
            ISPLicenseTariffChart::where('isp_license_issue_id', $appDataId)->delete();

            foreach ($request->tariffChart_package_name_plan as $index => $value) {
                $tariffObj                       = new ISPLicenseTariffChart();
                $tariffObj->isp_license_issue_id = $appDataId;
                $tariffObj->package_name_plan    = $value;
                $tariffObj->bandwidth_package    = $request->tariffChart_bandwidth_package[$index];
                $tariffObj->price                = $request->tariffChart_price[$index];
                $tariffObj->duration             = $request->tariffChart_duration[$index];
                $tariffObj->remarks              = $request->tariffChart_remarks[$index];
                $tariffObj->created_at           = date('Y-m-d H:i:s');
                $tariffObj->save();
            }
        }

    }

//    private function unfixedAmountsForGovtServiceFee( $isp_license_type, $payment_step_id ) {
//        $vat_percentage = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
//        if ( empty( $vat_percentage ) ) {
//            DB::rollback();
//            Session::flash( 'error', 'Please, configure the value for VAT.[INR-1026]' );
//
//            return redirect()->back()->withInput();
//        }
//
//        $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
//            'process_type_id' => $this->process_type_id,
//            'payment_step_id' => $payment_step_id,
//            'license_type_id' => $isp_license_type,
//            'status'          => 1,
//        ] )->first();
//
//        $unfixed_amount_array = [
//            1 => 0, // Vendor-Service-Fee
//            2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
//            3 => 0, // Govt. Application Fee
//            4 => 0, // Vendor-Vat-Fee
//            5 => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
//            6 => 0 //govt-vendor-vat-fee
//        ];
//
//        return $unfixed_amount_array;
//    }

    public function unfixedAmountsForGovtServiceFee($isp_license_type, $payment_step_id, $app_id = 0, $process_type_id = 0) {
        date_default_timezone_set("Asia/Dhaka");
        if (empty($this->process_type_id)) $this->process_type_id = $process_type_id;

        $vat_percentage = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');
        if (empty($vat_percentage)) {
            DB::rollback();
            Session::flash('error', 'Please, configure the value for VAT.[INR-1026]');
            return redirect()->back()->withInput();
        }

        $SpPaymentAmountConfData = SpPaymentAmountConf::where([
            'process_type_id' => $this->process_type_id,
            'payment_step_id' => $payment_step_id,
            'license_type_id' => $isp_license_type,
            'status' => 1,
        ])->first();

        $unfixed_amount_array = [];

        if ($payment_step_id == 1) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => round($SpPaymentAmountConfData->pay_amount,2), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => 0, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => 0, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
        } elseif ($payment_step_id == 2) {

            $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where([
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 3,
                'license_type_id' => $isp_license_type,
                'status' => 1,
            ])->first();

            //TODO:: delay fee calculation
            $submissionPaymentData = SonaliPayment::where([
                'app_id' => $app_id,
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1,
                'payment_status' => 1
            ])->first(['updated_at']); // Submission payment date

            $delay_fee                 = 0;
            $delay_vat_fee             = 0;
            $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date('Y-m-d', strtotime($submissionPaymentData->updated_at)) : date('Y-m-d');
            $currentDateTime           = date('Y-m-d', strtotime('-1 year'));

            if ($currentDateTime > $submissionPaymentDateTime) {
                $yarly_delay_fee = (($SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount) * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = $yarly_delay_fee / 365;
                $date_diff       = date_diff(date_create($currentDateTime), date_create($submissionPaymentDateTime));
                $delay_day_count = abs($date_diff->format('%r%a'));
                $delay_fee       = $delay_day_count * $daily_delay_fee;
                $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
            }

            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => round($SpPaymentAmountConfData->pay_amount,2), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => round($spPaymentAmountforAnnualFee->pay_amount,2), //1st year govt-annual-fee
                8 => round($delay_fee,2), //govt-delay-fee
                9 => round(($spPaymentAmountforAnnualFee->pay_amount * $vat_percentage) / 100,2), //govt-annual-vat-fee
                10 => round($delay_vat_fee,2) //govt-delay-vat-fee
            ];

        } elseif (in_array($payment_step_id, [3, 4, 5, 6])) {
            //TODO::Delay fee calculation
            $annualFeeData = AnnualFeeInfo::where([
                'process_type_id' => $this->process_type_id,
                'app_id' => $app_id,
                'status' => 0
            ])->first();

            $delay_fee       = 0;
            $delay_vat_fee   = 0;
            $paymentLastDate = strval($annualFeeData->payment_due_date);
            $currentDateTime = date('Y-m-d');
            if ($currentDateTime > $paymentLastDate) {
                $yarly_delay_fee = ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = $yarly_delay_fee / 365;
                $date_diff       = date_diff(date_create($currentDateTime), date_create($paymentLastDate));
                $delay_day_count = abs($date_diff->format('%r%a'));
                $delay_fee       = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
            }

            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => round($SpPaymentAmountConfData->pay_amount,2), //govt-annual-fee
                8 => round($delay_fee,2), //govt-delay-fee
                9 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), //govt-annual-vat-fee
                10 => round($delay_vat_fee,2) //govt-delay-vat-fee
            ];
        } elseif ($payment_step_id == 7) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => round($SpPaymentAmountConfData->pay_amount,2), //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
        }


        return $unfixed_amount_array;
    }

}
