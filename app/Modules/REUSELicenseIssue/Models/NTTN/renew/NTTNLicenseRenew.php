<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\NTTN\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;

use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NTTN\issue\NTTNLicenseIssue;
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

class NTTNLicenseRenew extends Model implements FormInterface
{
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'nttn_license_renew';
    protected $guarded = ['id'];
    protected $process_type_id;
    private $issue_process_type_id = 50;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = -1;
    private $submitted_status_id = 3;


    public function createForm($currentInstance): string
    {
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        $data['process_type_id'] = $currentInstance->process_type_id;
        $data['acl_name'] = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where('id', $currentInstance->process_type_id)->value('name');
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['bank_list'] = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        return strval(view('REUSELicenseIssue::NTTN.Renew.form', $data));
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $license_no = $request->get('license_no');
        if (empty($license_no)) {
            Session::flash('error', 'Invalid License No [NTTNR-006]');
            return redirect()->back()->withInput();
        }

        if ($request->get('app_id')) {
            $appData = self::find(Encryption::decodeId($request->get('app_id')));
            $processData = ProcessList::where([
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id' => $appData->id
            ])->first();
        } else {
            $appData = new NTTNLicenseRenew();
            $processData = new ProcessList();
        }

        $appData = $this->storeLicenseData($appData, $request);

        if ($appData->id) {
            // Store ShareHolder Person
            if (intval($request->get('shareholderDataCount'))) {
                CommonFunction::storeShareHolderPerson($request, $this->process_type_id, $appData->id);
            }

            // Store Contact Person
            CommonFunction::storeContactPerson($request, $this->process_type_id, $appData->id);

            //Dynamic Document Store
            DocumentsController::storeAppDocuments($currentInstance->process_type_id, $request->doc_type_key, $appData->id, $request);

            // Store Process list Data
            $processData = $this->storeProcessListData($request, $processData, $appData);

        }
        //=================================================payment code==========================
        $check_payment_type = false;
        if ( ( isset( $request->payment_type ) || $processData->status_id != $this->re_submit_status_id )) {

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
            $check_payment_type   = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );
            $payment_id           = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
        }


        /** if application submitted and status is equal to draft then generate tracking number and payment initiate  ***/
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'NTTNR-' . date( 'Ymd' ) . '-';
                CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
            }
            if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
                DB::commit();

                // redirect to Sonali Payment Portal
                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }
        }


        // Send Email for application re-submission
        if ( $processData->status_id == $this->re_submit_status_id ) {
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            $trackingNumber = self::where('id','=', $processData->ref_id)->value('tracking_no');
            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'NTTN License Renew', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'NTTN License Renew',
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

        if ( $processData->status_id == $this->draft_status_id ) {
            Session::flash( 'success', 'Successfully updated the Application!' );
        } elseif ( $processData->status_id == $this->submitted_status_id ) {
            Session::flash( 'success', 'Successfully Application Submitted !' );
        } elseif ( $processData->status_id == $this->re_submit_status_id ) {
            Session::flash( 'success', 'Successfully Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]' );
        }
        return redirect('/nttn-license-renew/list/' . Encryption::encodeId($this->process_type_id));
    }

    public function viewForm($processTypeId, $applicationId): JsonResponse
    {
        $this->process_type_id = $processTypeId;
        $decodedAppId = Encryption::decodeId($applicationId);
        $process_type_id = $this->process_type_id;
        $processList = ProcessList::where('ref_id', $decodedAppId)
            ->where('process_type_id', $process_type_id)
            ->first(['company_id']);
        $compId = $processList->company_id;
        $data['process_type_id'] = $process_type_id;

        $data['appInfo'] = ProcessList::leftJoin('nttn_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin( 'company_info', 'company_info.id', '=', DB::raw($compId) )
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
                'company_info.incorporation_num',
                'company_info.incorporation_date',
            ]);


        $data['appShareholderInfo'] = Shareholder::where(['app_id' => $decodedAppId, 'process_type_id' => $this->process_type_id])->get();
        $data['appDynamicDocInfo'] = ApplicationDocuments::where('process_type_id', $process_type_id)->where('ref_id', $decodedAppId)->whereNotNull('uploaded_path')->get();

        $data['contact_person'] = ContactPerson::where([
            'app_id' => $decodedAppId,
            'process_type_id' => $this->process_type_id
        ])->get();

        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['contact_upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }


        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();
        $public_html = (string)view('REUSELicenseIssue::NTTN.Renew.view', $data);

        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    /**
     * @param $processTypeId
     * @param $applicationId
     * @return JsonResponse
     */
    public function editForm($processTypeId, $applicationId): JsonResponse
    {
        $data['process_type_id'] = $processTypeId;
        $data['vat_percentage'] = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');
        $applicationId = Encryption::decodeId($applicationId);
        $process_type_id = $processTypeId;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();


        $data['divisions'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thana']     = ['' => 'Select'] + Area::where('area_type', 3)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['appInfo'] = ProcessList::leftJoin('nttn_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
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
                'pop.is_pay_order_verified'
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
                $value->image_real_path = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where([
            'app_id' => $applicationId,
            'process_type_id' => $process_type_id
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }

        $data['contact_person'] = $contact_data;

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
        $data['bank_list'] = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();

        if ($data['appInfo']->id) {
//            dd($data['appInfo']->id);
            $public_html = (string)view('REUSELicenseIssue::NTTN.Renew.form-edit', $data);
        } else {
            $public_html = (string)view('REUSELicenseIssue::NTTN.Renew.form-edit-v2', $data);
        }

        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function fetchData($request, $currentInstance): JsonResponse
    {
        $data['license_no'] = $request->license_no;
        $data['process_type_id'] = $currentInstance->process_type_id;

        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();
        if (empty($data['license_no'])) {
            return response()->json(['responseCode' => -1, 'msg' => 'Please provide valid license no']);
        }

        $issue_company_id      = NTTNLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        if ( $companyId != $issue_company_id ) {
            return response()->json(['responseCode' => -1, 'msg' => 'Try with valid Owner']);
        }

        $data['vat_percentage'] = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');

        $data['appInfo'] = ProcessList::leftJoin('nttn_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('nttn_license_master as ms', function ($join) {
                $join->on('ms.issue_tracking_no', '=', 'apps.tracking_no');
            })
            ->leftJoin('process_status as ps', function ($join) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($this->issue_process_type_id));
            })
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
            ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
            ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
            ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
            ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
            ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
            ->where('ms.license_no', $request->license_no)
            ->where('ms.status', 1)// approved status can be renew
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
//                'process_type.form_url',

                'reg_office_district.area_nm as reg_office_district_en',
                'reg_office_thana.area_nm as reg_office_thana_en',
                'op_office_district.area_nm as op_office_district_en',
                'op_office_thana.area_nm as op_office_thana_en',
                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',

                'apps.*',
            ]);

        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        if (empty($data['appInfo'])) {
            $companyId = CommonFunction::getUserCompanyWithZero();
            $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

            $data['process_type_id'] = $currentInstance->process_type_id;
            $data['acl_name'] = $currentInstance->acl_name;
            $data['application_type'] = ProcessType::Where('id', $currentInstance->process_type_id)->value('name');
            $data['bank_list'] = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();

            $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                    ->orderby('nationality')->pluck('nationality', 'id')->toArray();
            $public_html = strval(view('REUSELicenseIssue::NTTN.Renew.search-blank', $data));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $shareholders_data = Shareholder::where([
            'app_id' => $data['appInfo']['id'],
            'process_type_id' => $this->issue_process_type_id
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
                $value->image_real_path = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where([
            'app_id' => $data['appInfo']['id'],
            'process_type_id' => $this->issue_process_type_id
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }

        $data['contact_person'] = $contact_data;

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
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
        $data['bank_list'] = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();

        $public_html = (string)view('REUSELicenseIssue::NTTN.Renew.search', $data);
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    private function storeLicenseData($LicenseIssueObj, $request)
    {
        $LicenseIssueObj->org_nm = $request->get('company_name');
        $LicenseIssueObj->org_type = $request->get('company_type');
        $LicenseIssueObj->license_no          = $request->get( 'license_no', null );
        if(!empty($request->get('issue_date')) && !empty($request->has('expiry_date'))){
            $issue_date = Carbon::createFromFormat('d-M-Y', $request->get('issue_date'));
            $formattedIssueDate = $issue_date->format('Y-m-d');
            $expire_date = Carbon::createFromFormat('d-M-Y', $request->get('expiry_date'));
            $formattedExpireDate = $expire_date->format('Y-m-d');
            $LicenseIssueObj->license_issue_date = $formattedIssueDate ?? null;
            $LicenseIssueObj->expiry_date        = $formattedExpireDate ?? null;
        }
        $LicenseIssueObj->issue_tracking_no = $request->get('issue_tracking_no');
        $LicenseIssueObj->reg_office_district = $request->get('reg_office_district');
        $LicenseIssueObj->reg_office_thana = $request->get('reg_office_thana');
        $LicenseIssueObj->reg_office_address = $request->get('reg_office_address');
        $LicenseIssueObj->op_office_district = $request->get('op_office_district');
        $LicenseIssueObj->op_office_thana = $request->get('op_office_thana');
        $LicenseIssueObj->op_office_address = $request->get('op_office_address');
        $LicenseIssueObj->applicant_name = $request->get('applicant_name');
        $LicenseIssueObj->applicant_mobile = $request->get('applicant_mobile');
        $LicenseIssueObj->applicant_telephone = $request->get('applicant_telephone');
        $LicenseIssueObj->applicant_email = $request->get('applicant_email');
        $LicenseIssueObj->applicant_district = $request->get('applicant_district');
        $LicenseIssueObj->applicant_thana = $request->get('applicant_thana');
        $LicenseIssueObj->applicant_address = $request->get('applicant_address');
        $LicenseIssueObj->status = 1;
        $LicenseIssueObj->updated_at = Carbon::now();
        $LicenseIssueObj->company_id = CommonFunction::getUserCompanyWithZero();
        $LicenseIssueObj->total_no_of_share = $request->get('total_no_of_share');
        $LicenseIssueObj->total_share_value = $request->get('total_share_value');

        //Updated Information for Resubmit application
        //trade file
        $LicenseIssueObj->trade_license_number          = $request->get('trade_license_number');
        $LicenseIssueObj->current_trade_license_number  = $request->get('current_trade_license_number');
        $LicenseIssueObj->trade_validity                = $request->get('trade_validity');
        $LicenseIssueObj->trade_address                 = $request->get('trade_address');
        $LicenseIssueObj->tax_clearance                 = $request->get('tax_clearance');
        $LicenseIssueObj->current_tax_clearance         = $request->get('current_tax_clearance');
        $LicenseIssueObj->current_trade_validity        = $request->get('current_trade_validity');
        $LicenseIssueObj->current_trade_address         = $request->get('current_trade_address');

        if ( $request->hasFile( 'trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->tax_clearance_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_tax_clearance_attachment = $path . $simple_file_name;
        }


        //House rental
        $LicenseIssueObj->house_rental_address              = $request->get('house_rental_address');
        $LicenseIssueObj->house_rental_validity             = $request->get('house_rental_validity');
        $LicenseIssueObj->current_house_rental_address      = $request->get('current_house_rental_address');
        $LicenseIssueObj->current_house_rental_validity     = $request->get('current_house_rental_validity');


        if ( $request->hasFile( 'house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->house_rental_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_house_rental_attachment = $path . $simple_file_name;
        }

        //ISPAB
        $LicenseIssueObj->ispab_validity                = $request->get('ispab_validity');
        $LicenseIssueObj->current_ispab_validity        = $request->get('current_ispab_validity');

        if ( $request->hasFile( 'ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'ispab_attachment' );
            $simple_file_name = trim( uniqid( 'ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->ispab_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_ispab_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_ispab_attachment = $path . $simple_file_name;
        }

        //Shareholder
        $LicenseIssueObj->number_of_share                   = $request->get('number_of_share');
        $LicenseIssueObj->shareholders_name                 = $request->get('shareholders_name');
        $LicenseIssueObj->shareholders_nid_passport         = $request->get('shareholders_nid_passport');
        $LicenseIssueObj->current_number_of_share           = $request->get('current_number_of_share');
        $LicenseIssueObj->current_shareholders_name         = $request->get('current_shareholders_name');
        $LicenseIssueObj->current_shareholders_nid_passport = $request->get('current_shareholders_nid_passport');
        if ( $request->hasFile( 'shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->shareholders_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_shareholders_attachment = $path . $simple_file_name;
        }
        $LicenseIssueObj->save();

        return $LicenseIssueObj;
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
            $processListObj->status_id = 3;
            $processListObj->desk_id = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
        }

        $processListObj->ref_id = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->office_id = 0;
        $jsonData['Applicant Name'] = Auth::user()->user_first_name;
        $jsonData['Company Name'] = $request->company_name;
        $jsonData['Email'] = Auth::user()->user_email;
        $jsonData['Phone'] = Auth::user()->user_mobile;
        $processListObj['json_object'] = json_encode($jsonData);
        $processListObj->save();

        return $processListObj;

    }

    public function unfixedAmountsForGovtServiceFee($nttn_license_type, $payment_step_id, $app_id = 0, $process_type_id = 0)
    {
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
            'license_type_id' => $nttn_license_type,
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
                'license_type_id' => $nttn_license_type,
                'status' => 1,
            ])->first();

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
                $date_diff = date_diff(date_create($currentDateTime), date_create($submissionPaymentDateTime));
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

        } elseif (in_array($payment_step_id, [3, 4, 5, 6])) {
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
                $date_diff = date_diff(date_create($currentDateTime), date_create($paymentLastDate));
                $delay_day_count = abs(intval($date_diff->format('%r%a')));
                $delay_fee = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                $delay_vat_fee = ($delay_fee * intval($vat_percentage)) / 100; // 15% vat over delay fee
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
        }


        return $unfixed_amount_array;
    }
}
