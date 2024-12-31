<?php

namespace App\Modules\REUSELicenseIssue\Models\Special\amendment;

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
use App\Modules\Settings\Models\DynamicProcess;
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


class SpecialLicenseAmendment extends Model implements FormInterface
{

    protected $table = 'special_license_amendment';
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

    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {
        
    }
    

    public function viewForm($processTypeId, $appId): JsonResponse
    {
        
        $this->process_type_id = $processTypeId;
        $decodedAppId          = Encryption::decodeId($appId);
        $process_type_id       = $this->process_type_id;
        $application_data = SpecialLicenseAmendment::find($decodedAppId);
        $data['process_type_id'] = $process_type_id;
        $dynamic_form = DynamicProcess::where('process_type_id',$process_type_id)->first();

        $data['appInfo'] = ProcessList::leftJoin('special_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('dynamic_service_payment as sfp', function ($join) use ($process_type_id) {
                $join->on('sfp.app_id', '=', 'process_list.ref_id');
                $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
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
               
                'apps.*',
                'sfp.payment_status as sfp_payment_status',
                'sfp.pay_order_number',
                'sfp.pay_order_copy',
                
            ]);

        $data['contact_person']        = ContactPerson::where([
            'app_id' => $decodedAppId,
            'process_type_id' => $this->process_type_id
        ])->get();

        $data['appDynamicDocInfo']  = collect(json_decode($application_data->json_object))->reject(function($item, $key){
            if (strpos($key,'doc_') !== false) {
                return false;
            } else {
                return true;
            }
        })->toArray();

        $data['doc_labels']= json_decode($dynamic_form->dynamic_data,true)[0]['attachments'] ;
        $data['dynamic_form_data']= json_decode($dynamic_form->dynamic_data,true);
        $data['application_data']= json_decode($application_data->json_object);
        unset($data['dynamic_form_data'][0]['attachments']); 
        $data['input_array']= $data['dynamic_form_data'] ;

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

        }
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();


        $public_html = (string)view('REUSELicenseIssue::Special.Issue.masterView', $data);

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
                7 => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                8 => $delay_fee, //govt-delay-fee
                9 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, //govt-annual-vat-fee
                10 => $delay_vat_fee //govt-delay-vat-fee
            ];
        } elseif ($payment_step_id == 7) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
        }


        return $unfixed_amount_array;
    }

}
