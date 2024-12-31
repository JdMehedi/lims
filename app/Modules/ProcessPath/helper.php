<?php

use App\Libraries\CommonFunction;
use App\Models\User;
use App\Models\ConfigSetting;
use App\Models\IndRRCommonPool;
use App\Modules\CertificateGeneration\Http\Controllers\CertificateGenerationController;
use App\Modules\IGWLicenseIssue\Models\IGWLicenseIssue;
use App\Modules\IGWLicenseIssue\Models\IGWLicenseMaster;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterMaster;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\BWA\BWALicenseMaster;
use App\Modules\REUSELicenseIssue\Models\BWA\issue\BWALicenseIssue;
use App\Modules\REUSELicenseIssue\Models\BWA\renew\BWALicenseRenew;
use App\Modules\REUSELicenseIssue\Models\BWA\surrender\BWALicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ICX\renew\ICXLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IIG\surrender\IIGLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssueMaster;
use App\Modules\REUSELicenseIssue\Models\IPTSP\amendment\IPTSPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IPTSP\amendment\IPTSPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\IPTSP\renew\IPTSPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IPTSP\surrender\IPTSPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ISP\amendment\ISPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\MNOLicenseIssue\Models\MNOLicenseIssue;
use App\Modules\MNPLicenseIssue\Models\MNPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\CallCenterRenew;
use App\Modules\REUSELicenseIssue\Models\BPO\Amendment\Amendment;
use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ITC\issue\ITCLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ITC\issue\ITCLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ITC\renew\ITCLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ITC\surrender\ITCLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\MNP\renew\MNPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\MNP\surrender\MNPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\SS\issue\SSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SS\renew\SSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NIX\amendment\NIXLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\NIX\surrender\NIXLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\NTTN\amendment\NTTNLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\NTTN\issue\NTTNLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NTTN\NTTNLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\NTTN\renew\NTTNLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NTTN\surrender\NTTNLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\SS\surrender\SSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\TC\amendment\TCLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\TC\renew\TCLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\TVAS\amendment\TVASLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\TVAS\renew\TVASLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\TVAS\surrender\TVASLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\VSAT\surrender\VSATLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\VTS\amendment\VTSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\VTS\renew\VTSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\VTS\surrender\VTSLicenseSurrender;
use App\Modules\SCSLicenseIssue\Models\SCSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\ISPLicenseMaster;
use App\Modules\SCSLicenseIssue\Models\SCSLicenseMaster;
use App\Modules\MNPLicenseIssue\Models\MNPLicenseMaster;
use App\Modules\MNOLicenseIssue\Models\MNOLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\TVAS\issue\TVASLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\NIX\renew\NIXLicenseRenew;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServiceInfo;
use App\Modules\REUSELicenseIssue\Models\TVAS\issue\TVASLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IIG\issue\IIGLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IIG\issue\IIGLicenseMaster;
use App\Modules\SonaliPayment\Http\Controllers\PaymentPanelController;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\SSLicenseIssue\Models\SSLicenseMaster;
use App\Modules\TCLicenseIssue\Models\TCLicenseIssue;
use App\Modules\TCLicenseIssue\Models\TCLicenseMaster;
use App\Modules\Users\Models\Users;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATLicenseRenew;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseMaster;
use App\Modules\SonaliPayment\Models\PayOrderPayment;
use App\Modules\REUSELicenseIssue\Models\BPO\surrender\CallCenterSurrender;
use App\Modules\REUSELicenseIssue\Models\ICX\amendment\ICXLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IGW\renew\IGWLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IGW\surrender\IGWLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IIG\amendment\IIGLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IIG\renew\IIGLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\MNO\amendment\MNOLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\SCS\renew\SCSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\SCS\amendment\SCSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\SCS\surrender\SCSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\MNP\amendment\MNPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ITC\amendment\ITCLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\BWA\amendment\BWALicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ICX\surrender\ICXLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\MNO\surrender\MNOLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\TC\surrender\TCLicenseSurrender;
use Illuminate\Support\Facades\Log;

function snakeToCamel($input): string
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
}

function getCategoryCodeIspLicenseIssue($service_category): array
{
    $service_category_code = '';
    if (in_array($service_category, [1, 2])) {
        $service_category_code = '.45';
        $file_no = 100;
    } elseif ($service_category == 3) {
        $service_category_code = '.46';
        $file_no = 001;
    } elseif ($service_category == 4) {
        $service_category_code = '.47';
        $file_no = 001;
    }
    return [$service_category_code, $file_no];
}

function generateLicenseNo($process_type_id, $table_name, $file_no = '', $service_category_id = '', $license_type_id = '', $license_type_col = '')
{
    try {
        $processInfoCount = ProcessList::query()
            ->leftJoin($table_name, $table_name . '.id', '=', 'process_list.ref_id')
            ->when($license_type_id != '' && $license_type_col != '', function ($q) use ($table_name, $license_type_col, $license_type_id) {
                $q->where($table_name . '.' . $license_type_col, $license_type_id);
            })
            ->whereYear('submitted_at', date('Y'))
//            ->whereNotNull($table_name.'.license_no')
            ->where([
                // 'process_list.status_id' => 1,
                ['process_list.status_id', '>', 0],
                'process_list.process_type_id' => $process_type_id
            ])->count(); // get count data from target table to calculate file_no
        $licenseNo = '14.32.0000.702'; // base license id
        if (empty($license_type_id) && !empty($service_category_id)) {  // service code - for who have no license_type_id
            $licenseNo .= '.' . $service_category_id;
        }
        if (!empty($license_type_id) && empty($service_category_id)) { // service code - only for when license_type_id exist - like nationwide/division, district, thana
//            $callback = 'getCategoryCode' . snakeToCamel($table_name);
            if (in_array($license_type_id, [1, 2])) {
                $service_category_code = '.45';
                $file_no = 100;
            } elseif ($license_type_id == 3) {
                $service_category_code = '.46';
                $file_no = 001;
            } elseif ($license_type_id == 4) {
                $service_category_code = '.47';
                $file_no = 001;
            }
//            list($service_category_code, $file_no) = $callback($license_type_id);
            $licenseNo .= $service_category_code;
//            dd($file_no, $processInfoCount);
            $file_no = $file_no + $processInfoCount; // get file no from condition license_type_id

            $licenseNo .= '.' . str_pad($file_no, 3, '0', STR_PAD_LEFT); // file no
        }
        if (empty($license_type_id) && !empty($file_no)) { // get file_no form function
            $file_no = $file_no + $processInfoCount;
            $licenseNo .= '.' . str_pad($file_no, 3, '0', STR_PAD_LEFT); // file no
        }
        $licenseNo .= '.' . date('y'); // year
        $licenseNo .= '.001'; // sharok no
        return $licenseNo;
    } catch (\Exception $e) {
        Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
    }
}

/**
 * @param $json
 *
 * @return string
 */
function getDataFromJson($json): string
{
    $jsonDecoded = json_decode($json);
    $string = '';
    foreach ($jsonDecoded as $key => $data) {
        $string .= $key . ": " . $data . ', ';
    }

    return $string;
}

/**
 * Here are the extras that work on a specific process type application or a specific status of the application.
 * Such as sending mail / sms to specific status, generating certificates in the final status or
 * updating any data in the specified status
 *
 * @param $process_list_id
 * @param $status_id
 * @param int $approver_desk_id
 * @param $requestData
 *
 * @return bool
 * @throws Throwable
 */
function CertificateMailOtherData($process_list_id = 0, $status_id = 0, $approver_desk_id = 0, $requestData = [], $flag = 0): bool
{
    $processInfo = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
        ->where('process_list.id', $process_list_id)
        ->first([
          //  'process_list.id as process_list_id',
            'process_list.tracking_no',
            'process_type.id',
            'process_type.name as process_type_name',
            'process_list.ref_id',
            'process_list.company_id',
            'process_list.process_type_id',
            'process_list.office_id',
            'process_list.process_desc',
            'process_list.user_id',
            'process_list.desk_id',
        ]);
    $processType= ProcessType::find($processInfo->process_type_id);
    $companyName = DB::table('company_info')->where('id', '=', $processInfo->company_id)->value('org_nm');
    $appInfo = [
        'app_id' => $processInfo->ref_id,
        'status_id' => $status_id,
        'process_type_id' => $processInfo->process_type_id,
        'tracking_no' => $processInfo->tracking_no,
        'process_type_name' => $processInfo->process_type_name,
        'remarks' => $requestData['remarks'],
        'org_nm' => $companyName
    ];

    if ($status_id == 5) {
//        CommonFunction::sendEmailSMS( 'APP_SHORTFALL', $appInfo, $receiverInfo );
    } elseif ($status_id == 6) {
        //SMS send for application reject
//        $userMobile = $user_mobile;
//        $loginControllerInstance = new LoginController();
//        $loginControllerInstance->SendSmsService('APP_REJECT', ['{$trackingNumber}' => $processInfo->tracking_no], $userMobile);

        //Send email to the applicant for the application rejection
//        CommonFunction::sendEmailSMS( 'APP_REJECT', $appInfo, $receiverInformation );
    }
    $service_name = ProcessType::where('id', $processInfo->process_type_id)->value('name');

    if( $processType->is_special==1){
        return true;
    }
    switch ($processInfo->process_type_id) {
        case 1: // ISP license issue
            $model = ISPLicenseIssue::class;
            $masterModel = ISPLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            $isp_license_type_value = ISPLicenseIssue::where('id', $appInfo['app_id'])->value('isp_license_type');

            if (in_array($status_id, ['25'])) { // approve and certificate
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
            if($flag== 0){
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
            }else{
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id,$old_cancel=0,$flag);
            }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                // old application cancel
                $license= ISPLicenseIssue::find($processInfo->ref_id);
                $user_company =  $license->company_id;

                $old_licenseData= ISPLicenseMaster::where('company_id',$user_company)
                ->where('issue_tracking_no','!=',$processInfo->tracking_no)
                ->whereNotNull('license_no')
                ->whereNull('cancellation_tracking_no')->get();

                $trackingPrefix = 'ISP-S-' . date( 'Ymd' ) . '-';
                $result = DB::select(DB::raw("SELECT id FROM isp_license_issue ORDER BY id DESC LIMIT 1"));
                $lastApplicationId = $result[0]->id;
                $applicationCount = strval($lastApplicationId);
                $currentDate = date('d-m-Y');
                $timestamp = str_replace("-", "", $currentDate);
                $paddedNumber = str_pad($applicationCount, 7, "0", STR_PAD_LEFT);
                $trackingNumber = $trackingPrefix."-".$timestamp. "-".'ISS'."-".$paddedNumber;

                //
                ISPLicenseMaster::where('company_id',$user_company)
                ->whereNull('cancellation_tracking_no')
                ->whereNotNull('license_no')
                ->where('issue_tracking_no','!=',$processInfo->tracking_no)
                ->update([
                    'cancellation_tracking_no' => $trackingNumber
                ]);

                //generate PDF for every license cancelled
                if(!empty($old_licenseData)){
                    foreach($old_licenseData as $license){
                        $licenseObj     = ISPLicenseSurrender::where('issue_tracking_no',$license->tracking_no)->first();
                        if ( is_null($licenseObj) ) {
                            $licenseObj     = new ISPLicenseSurrender();
                        }

                        $licenseObj->org_nm              = $license->org_nm;
                        $licenseObj->org_type            = $license->org_type;
                        $licenseObj->license_no          = $license->license_no;
                        $licenseObj->surrender_date      = Carbon::now();
                        $licenseObj->reason_of_surrender = 'Auto Cancellation';
                        $licenseObj->issue_tracking_no   = $license->issue_tracking_no;
                        $licenseObj->renew_tracking_no   = $license->renew_tracking_no;
                        // $licenseObj->reg_office_district = $license->reg_office_district;
                        // $licenseObj->reg_office_thana    = $license->reg_office_thana;
                        // $licenseObj->reg_office_address  = $license->reg_office_address;
                        // $licenseObj->op_office_district  = $license->op_office_district;
                        // $licenseObj->op_office_thana     = $license->op_office_thana;
                        // $licenseObj->op_office_address   = $license->op_office_address;
                        // $licenseObj->applicant_name      = $license->applicant_name;
                        // $licenseObj->applicant_mobile    = $license->applicant_mobile;
                        // $licenseObj->applicant_telephone = $license->applicant_telephone;
                        // $licenseObj->applicant_email     = $license->applicant_email;
                        // $licenseObj->applicant_district  = $license->applicant_district;
                        // $licenseObj->applicant_thana     = $license->applicant_thana;
                        // $licenseObj->applicant_address   = $license->applicant_address;

                        $licenseObj->isp_license_type     = $license->isp_license_type;
                        $licenseObj->isp_license_division = $license->isp_license_division;
                        $licenseObj->isp_license_district = $license->isp_license_district;
                        $licenseObj->isp_license_upazila  = $license->isp_license_upazila;

                        // $licenseObj->location_of_ins_district = $license->location_of_ins_district;
                        // $licenseObj->location_of_ins_thana    =$license->location_of_ins_thana;
                        // $licenseObj->location_of_ins_address  = $license->location_of_ins_address;

                        // $licenseObj->home       = $license->home;
                        // $licenseObj->cyber_cafe = $license->cyber_cafe;
                        // $licenseObj->office     = $license->office;
                        // $licenseObj->others     = $license->others;

                        // $licenseObj->corporate_user = $license->corporate_user;
                        // $licenseObj->personal_user  = $license->personal_user;
                        // $licenseObj->branch_user    = $license->branch_user;
                        // list_of_clients
                        // if ( ! empty( $license->list_of_clients ) ) {
                        //     $licenseObj->list_of_clients = $license->list_of_clients;
                        // // }

                        // $licenseObj->business_plan       = $license->business_plan;
                        // $licenseObj->declaration_q1      = $license->declaration_q1;
                        // $licenseObj->declaration_q1_text = $license->declaration_q1_text;
                        // $licenseObj->declaration_q2      = $license->declaration_q2;
                        // $licenseObj->declaration_q2_text = $license->declaration_q2_text;
                        // $licenseObj->declaration_q3      = $license->declaration_q3;
                        $licenseObj->status              = 1; //approve
                        $licenseObj->created_at          = Carbon::now();
                        $licenseObj->company_id          = $license->company_id;
                        // $licenseObj->total_no_of_share   = $license->total_no_of_share;
                        // $licenseObj->total_share_value   = $license->total_share_value;
                        $licenseObj->auto_cancel   = 1;
                        $jsonData=[];
                        $jsonData['master_id'] = $license->id;
                        $jsonData['company_id']          = $license->company_id;
                        $licenseObj->auto_cancel_info   = json_encode( $jsonData );
                        $licenseObj->save();

                        $processObj   = new ProcessList();
                        $processObj->ref_id=   $licenseObj->id;
                        $processObj->desk_id=   0;
                        $processObj->company_id=  $license->company_id;
                        $processObj->cat_id=  1;
                        $processObj->license_no=   $license->license_no;
                        $processObj->process_type_id = 4;
                        $processObj->status_id  = 25;
                        $processObj->save();

                        CommonFunction::generateUniqueTrackingNumber('ISP', 4, $processObj->id, 'isp_license_surrender', 'SUR', $licenseObj->id);

                        /*
                        $processInfo = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                        ->where([
                            'process_list.ref_id' => $license->id,
                            'process_list.process_type_id' => 1
                        ])->first([
                        //    'process_list.id',
                            'process_list.company_id',
                            'process_type.service_code',
                            'process_list.status_id',
                            'process_list.tracking_no'
                        ]);

                        if($processInfo){
                            $url_store = PdfPrintRequestQueue::firstOrNew([
                                'process_type_id' => 1,
                                'app_id' => $license->id,
                                'pdf_diff' => pdf_diff($processInfo->status_id)
                            ]);
                        }


                        $pdf_info = PdfServiceInfo::where('certificate_name', 'isp-license-surrender')->first([
                            'pdf_server_url',
                            'reg_key',
                            'pdf_type',
                            'certificate_name',
                            'table_name',
                            'field_name'
                        ]);

                        if (empty($pdf_info)) {
                            dd('no pdf info found');
                        }
                        $tableName = $pdf_info->table_name;
                        $fieldName = $pdf_info->field_name;

                        $url_store->process_type_id = 1;
                        $url_store->app_id = $license->id;
                       // $url_store->process_list_id = $processInfo->id;
                        $url_store->pdf_server_url = $pdf_info->pdf_server_url;
                        $url_store->reg_key = $pdf_info->reg_key;
                        $url_store->pdf_type = $pdf_info->pdf_type;
                        $url_store->certificate_name = $pdf_info->certificate_name;
                        $url_store->prepared_json = 0;
                        $url_store->table_name = $tableName;
                        $url_store->field_name = $fieldName;
                        $url_store->url_requests = '';
                        //        $url_store->status = 0;
                        $url_store->job_sending_status = 0;
                        $url_store->no_of_try_job_sending = 0;
                        $url_store->job_receiving_status = 0;
                        $url_store->no_of_try_job_receving = 0;
                        $url_store->signatory = Auth::user()->id;



                        $pdf_type_value = \App\Modules\Settings\Models\PdfSignatureQrcode::where([
                            'app_id' => $license->id,
                            'process_type_id' => 1
                        ])->where('pdf_type', '>', 0)->latest()->value('pdf_type');

                        if(isset($old_cancel) && $old_cancel=='old-cancel'){
                            $pdf_type_value += 1;
                            $data = \App\Modules\Settings\Models\PdfSignatureQrcode::where([
                                'app_id' => $license->id,
                                'process_type_id' => 1
                            ])->where('pdf_type', '>', 0)->latest()->first();
                            if(isset($data) && $data){
                                $data->pdf_type = $pdf_type_value;
                                $data->status_id = $status_id;
                                $data->save();
                            }


                        }

                        if($processInfo){
                            $url_store->pdf_diff = pdf_diff($processInfo->status_id);
                        }

                        $url_store->updated_at = date('Y-m-d H:i:s');
                        $url_store->save();

                        $processData =  ProcessList::where(['ref_id' => $license->id , 'process_type_id' => 1])->first();
                        if($processData){
                            $userJson = json_decode($processData->json_object, true);
                            $receivers =[
                                array(
                                    'user_mobile' => $userJson['Phone'],
                                    'user_email' => $userJson['Email']
                                )
                            ];
                            $certificateControllerInstance = new CertificateGenerationController();
                        // $generateCertificateStatus = $certificateControllerInstance->generateCertificate();
                        $pdfLink = PdfPrintRequestQueue::where('process_type_id', '=', $processInfo->process_type_id)->where('app_id', '=', $processInfo->ref_id)->where('pdf_diff', '=', 1)->value('certificate_link');
                        CommonFunction::sendEmailSMS('APP_CANCEL', $appInfo, $receivers, $pdfLink);
        //SMS for Shortfall
                        $userMobile = $receivers[0]['user_mobile'];
                        $service_name = ProcessType::where('id', $processInfo->process_type_id)->value('name');
                        $loginControllerInstance = new LoginController();
                        $loginControllerInstance->SendSmsService('APP_CANCEL', ['{$serviceName}' => $service_name, ' {$trackingNumber}' => $appInfo['tracking_no']], $userMobile);

                        }

                        */

                    }
                }


                ////generate PDF for every license cancelled END////

                // generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Surrender', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-surrender');
                //old application cancel end

            }
            elseif (in_array($status_id, ['5'])) {
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue', $flag);

            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                updateApprovalMemoMinistry($requestData, $processInfo, $model);//update approval Memo for Payment Request
                requestForPayment($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, 'ISP License Issue', $approver_desk_id, 'isp_license_master', 'isp-license-issue', 2, 1, $flag);
            }
            elseif (in_array($status_id, ['60'])) {
//                dd(1,$requestData);
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                if($flag==0){
                    certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id, $old_cancel=0,$flag);
                }
                //Generate pdf for request for annual or bg payment
                generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue', $process_list_id, $status_id, $flag);
            }
            elseif (in_array($status_id, ['65'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 7,
                    'process_type_id' => $processInfo->process_type_id,
                    'is_bg' => 1
                ])->update(['payment_status' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                if($flag == 0){
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                }else{
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id, $old_cancel= 0, $flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;
        case 54: // ITC license issue
            $model = ITCLicenseIssue::class;
            $masterModel = ITCLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

//            $isp_license_type_value = ITCLicenseIssue::where('id', $appInfo['app_id'])->value('isp_license_type');



            if (in_array($status_id, ['25'])) { // approve and certificate
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
                //updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'itc_license_issue', '001', '48', '', '');

                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'itc_license_master', 'itc-license-issue', $approver_desk_id, 'generate', $process_list_id);

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['5'])) {
//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue');

            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'itc_license_issue', '001', '48', '', '');
//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
//                updateApprovalMemoMinistry($requestData, $processInfo, $model);//update approval Memo for Payment Request
                requestForPayment($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, 'ITC License Issue', $approver_desk_id, 'itc_license_master', 'itc-license-issue', 2, 1);
            }
            elseif (in_array($status_id, ['60'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                //Generate pdf for request for annual or bg payment
                generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue', $process_list_id, $status_id);
            }
            elseif (in_array($status_id, ['65'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 7,
                    'process_type_id' => $processInfo->process_type_id,
                    'is_bg' => 1
                ])->update(['payment_status' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;
        case 55: // ITC license Renew
            $model = ITCLicenseRenew::class;
            $masterModel = ITCLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

//            $isp_license_type_value = ITCLicenseIssue::where('id', $appInfo['app_id'])->value('isp_license_type');



            if (in_array($status_id, ['25'])) { // approve and certificate
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');

                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'itc_license_master', 'itc-license-renewal', $approver_desk_id, 'generate', $process_list_id);

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['5'])) {
//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue');

            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {

//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
//                updateApprovalMemoMinistry($requestData, $processInfo, $model);//update approval Memo for Payment Request
//                requestForPayment($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, 'ITC License Issue', $approver_desk_id, 'itc_license_master', 'itc-license-issue', 2, 1);
            }
            elseif (in_array($status_id, ['60'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                //Generate pdf for request for annual or bg payment
                generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue', $process_list_id, $status_id);
            }
            elseif (in_array($status_id, ['65'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 7,
                    'process_type_id' => $processInfo->process_type_id,
                    'is_bg' => 1
                ])->update(['payment_status' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;

        case 2: // ISP renew
            $model = ISPLicenseRenew::class;
            $masterModel = ISPLicenseMaster::class;
            $isp_license_type_value = ISPLicenseRenew::find($appInfo['app_id'])->value("isp_license_type");
            $issue_tracking_number = ISPLicenseRenew::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);

                //Update sharok number
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_renew', '', '', $isp_license_type_value, 'isp_license_type');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-renew', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-renew', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            }
            elseif (in_array($status_id, ['5'])) {
                //Update sharok number
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_renew', '', '', $isp_license_type_value, 'isp_license_type');

                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Renew', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-renew', $flag);
            }
            elseif (in_array($status_id, ['30'])) {

                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }


                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {

                shortfallPayOrderVerification($requestData, $processInfo, 1);

                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {
                //Update sharok number
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_renew', '', '', $isp_license_type_value, 'isp_license_type');

                updateApprovalMemoMinistry($requestData, $processInfo, $model);//update approval Memo for Payment Request

                requestForPayment($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, 'ISP License Renew', $approver_desk_id, 'isp_license_master', 'isp-license-renew', 2, 1);
            }
            elseif (in_array($status_id, ['60'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_renew', '', '', $isp_license_type_value, 'isp_license_type');
                if($flag==0){
                    certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-renew', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-renew', $approver_desk_id, 'generate', $process_list_id, $old_cancel= 0, $flag);
                }
                //Generate pdf for request for annual or bg payment
                generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Renew', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-renew', $process_list_id, $status_id, $flag);
            }
            elseif (in_array($status_id, ['65'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 7,
                    'process_type_id' => $processInfo->process_type_id,
                    'is_bg' => 1
                ])->update(['payment_status' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_renew', '', '', $isp_license_type_value, 'isp_license_type');
                if($flag == 0){
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-renew', $approver_desk_id, 'generate', $process_list_id);
                }else{
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-renew', $approver_desk_id, 'generate', $process_list_id, $old_cancel= 0, $flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;
        case 3: // ISP Amendment
            $model = ISPLicenseAmendment::class;
            $masterModel = ISPLicenseMaster::class;
            $isp_license_type_value = ISPLicenseAmendment::find($appInfo['app_id'])->value("isp_license_type");
            $issue_tracking_number = ISPLicenseMaster::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //updated sharok no
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_amendment', '', '', $isp_license_type_value, 'isp_license_type');
                if($flag== 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-ammendment', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-ammendment', $approver_desk_id,$old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            }
            elseif (in_array($status_id, ['5'])) {
//                if ( isset( $requestData['pay_order_verification'] ) ) {
//                    $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
//                    $pay_status       = $pay_order_status ? 1 : - 1;
//                    PayOrderPayment::where( [ 'app_id' => $processInfo->ref_id, 'payment_step_id' => 1 ] )
//                                   ->update( [
//                                       'is_pay_order_verified' => $pay_order_status,
//                                       'payment_status'        => $pay_status
//                                   ] );
//                }
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_amendment', '', '', $isp_license_type_value, 'isp_license_type');
                //generate pdf and send email and sms for application shortfal
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Amendment', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-ammendment', $flag);
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 4: // ISP Surrender
            $model = ISPLicenseSurrender::class;
            $masterModel = ISPLicenseMaster::class;
//            $data = ISPLicenseMaster::find($appInfo['app_id']);
            $isp_license_type_value = ISPLicenseSurrender::find($appInfo['app_id'])->value("isp_license_type");
            $issue_tracking_number = ISPLicenseMaster::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_surrender', '', '', $isp_license_type_value, 'isp_license_type');
                if($flag== 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-surrender', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-surrender', $approver_desk_id,$old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }


            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['5'])) {
                //updated sharok no
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'isp_license_amendment', '', '', $isp_license_type_value, 'isp_license_type');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Surrender', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-surrender', $flag);
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 81: // SS Surrender
            $model = SSLicenseSurrender::class;
            # SET STATUS
            define("SHORTFALL_STATUS", 47);
            define("OTHER_NUMBER_OF_LICENSE_STATUS", 25);
            define("SEND_TO_DD_STATUS", 30);

            if ($status_id == constant("SHORTFALL_STATUS")) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            } elseif ($status_id == constant("OTHER_NUMBER_OF_LICENSE_STATUS")) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'ss_license_master', 'ss-license-surrender', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            } elseif ($status_id == constant("SEND_TO_DD_STATUS")) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    $model::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    $model::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    $model::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }

            return true;
            break;
        case 24: // ISP Surrender
            $model = IPTSPLicenseSurrender::class;
            $masterModel = IPTSPLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            $issue_tracking_number = IPTSPLicenseMaster::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptsp-license-cancellation', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptsp-license-cancellation', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['5'])) {

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'IPTSP License Surrender', $processInfo, $approver_desk_id, 'iptsp_license_master', 'iptsp-license-cancellation', $flag);
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 28: // TVAS License Surrender

            $model = TVASLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tvas_license_master', 'tvas-license-cancellation', $approver_desk_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'TVAS License Surrender', $processInfo, $approver_desk_id, 'tvas_license_master', 'tvas-license-cancellation');
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 9: // NIX license issue
            $model = NIXLicenseIssue::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 2,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_type' => 'pay_order'
                ])->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix-license-issue', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix-license-issue', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'NIX License Issue', $processInfo, $approver_desk_id, 'nix_license_master', 'nix-license-issue', $flag);
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['47'])) {

//                shortfallPayOrderVerification($requestData, $processInfo, 1);

                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason

            } elseif (in_array($status_id, ['80'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 2,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // First year payment status
            } elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            } elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            } elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            } elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            } elseif (in_array($status_id, ['69'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 7,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // sixth year payment status
            } elseif (in_array($status_id, ['70'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 8,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // seventh year payment status
            } elseif (in_array($status_id, ['71'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 9,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eighth year payment status
            } elseif (in_array($status_id, ['72'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 10,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // nineth year payment status
            } elseif (in_array($status_id, ['73'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 11,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // tenth year payment status
            } elseif (in_array($status_id, ['74'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 12,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eleventh year payment status
            } elseif (in_array($status_id, ['75'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 13,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Twelveth year payment status
            } elseif (in_array($status_id, ['76'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 14,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Thirteenth year payment status
            } elseif (in_array($status_id, ['77'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 15,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fourteenth year payment status
            } elseif (in_array($status_id, ['78'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 16,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fifteenth year payment status
            } elseif (in_array($status_id, ['15'])) {

                //Have to calculate fixed amount
                $total_amount = DB::table('sp_payment_configuration')->where([
                    'process_type_id' => 9,
                    'payment_step_id' => 1
                ])->value('amount');

                $appInfo['total_amount'] = $total_amount;

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $service_name, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            }

            return true;
            break;
        case 10: // NIX license renew
            $model = NIXLicenseRenew::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix_license_renew', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix_license_renew', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['47'])) {

//                shortfallPayOrderVerification($requestData, $processInfo, 1);

                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason

            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'NIX License Renew', $processInfo, $approver_desk_id, 'nix_license_master', 'nix-license-renew', $flag);
            } elseif (in_array($status_id, ['80'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 2,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // First year payment status
            } elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            } elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            } elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            } elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            } elseif (in_array($status_id, ['69'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 7,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // sixth year payment status
            } elseif (in_array($status_id, ['70'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 8,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // seventh year payment status
            } elseif (in_array($status_id, ['71'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 9,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eighth year payment status
            } elseif (in_array($status_id, ['72'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 10,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // nineth year payment status
            } elseif (in_array($status_id, ['73'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 11,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // tenth year payment status
            } elseif (in_array($status_id, ['74'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 12,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eleventh year payment status
            } elseif (in_array($status_id, ['75'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 13,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Twelveth year payment status
            } elseif (in_array($status_id, ['76'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 14,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Thirteenth year payment status
            } elseif (in_array($status_id, ['77'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 15,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fourteenth year payment status
            } elseif (in_array($status_id, ['78'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 16,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fifteenth year payment status
            } elseif (in_array($status_id, ['15'])) {

                //Have to calculate fixed amount
                $total_amount = DB::table('sp_payment_configuration')->where([
                    'process_type_id' => 10,
                    'payment_step_id' => 1
                ])->value('amount');

                $appInfo['total_amount'] = $total_amount;

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $service_name, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            }

            return true;
            break;
        case 11:
            $model = NIXLicenseAmendment::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix-license-amendment', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix-license-amendment', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }

            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, $service_name, $processInfo, $approver_desk_id, 'nix_license_master', 'nix-license-amendment', $flag);
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 12:
            $model = NIXLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix-license-surrender', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nix_license_master', 'nix-license-surrender', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NIXLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }

            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, $service_name, $processInfo, $approver_desk_id, 'nix_license_master', 'nix-license-surrender', $flag);
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 5: // BPO license issue
            $model = CallCenterNew::class;
            $masterModel = CallCenterMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {


                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'call_center_issue', '001', '48', '', '');

//                updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'call_center_master', 'call_center_license_issue', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'call_center_master', 'call_center_license_issue', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }

                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    CallCenterNew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['5'])) {
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'call_center_issue', '001', '48', '', '');
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'BPO/ Call Center Registration Issue', $processInfo, $approver_desk_id, 'call_center_master', 'call_center_license_issue', $flag);
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'call_center_issue', '001', '48', '', '');
                //updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'call_center_master', 'call_center_license_issue', 1, 1);
            }

            return true;
            break;

        case 6: // BPO license Renew
            $model = CallCenterRenew::class;
            $masterModel = CallCenterMaster::class;
            $process_info_for_sharok = $processInfo->toArray();
            $issue_tracking_number = CallCenterRenew::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                //updateSarokNo($processInfo, $model, 'call_center_renew', '001', '48', '', '');
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'call_center_renew', '001','48','','');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'call_center_master', 'call_center_license_renew', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'call_center_master', 'call_center_license_renew', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    CallCenterRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'call_center_renew', '001','48','','');
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'BPO/ Call Center Registration Renew', $processInfo, $approver_desk_id, 'call_center_master', 'call_center_license_renew', $flag);
            }
            elseif (in_array($status_id, ['47'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'call_center_renew', '001','48','',''); //update sharok_no for Payment Request renew
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'call_center_master', 'call_center_license_renew', 1, 1);
            }

            return true;
            break;

        case 7: // BPO license Amendment
            $model = Amendment::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'call_center_master', 'call-center-license-ammendment', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'call_center_master', 'call-center-license-ammendment', $approver_desk_id, $old_cancel=0,$flag);
                }

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    Amendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'BPO/ Call Center Registration Amendment', $processInfo, $approver_desk_id, 'call_center_master', 'call-center-license-ammendment', $flag);
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 8: // BPO Surrender
            $model = CallCenterSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'call-center-license-surrender', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'call-center-license-surrender', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    CallCenterSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'BPO/ Call Center Registration Surrender', $processInfo, $approver_desk_id, 'call_center_master', 'call-center-license-surrender', $flag);
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;

        case 13: // VSAT license issue
            $model = VSATLicenseIssue::class;
            $masterModel = VSATLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'vsat_license_issue', '100', '51', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vsat_license_master', 'vsat-license-issue', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vsat_license_master', 'vsat-license-issue', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['15'])) {

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'vsat_license_issue', '100', '51', '', '');

                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['5'])) {
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'vsat_license_issue', '100', '51', '', '');
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'VSAT License Issue', $processInfo, $approver_desk_id, 'vsat_license_master', 'vsat-license-issue', $flag);
            }

            return true;
            break;
        case 14: // VSAT license renew

            $model = VSATLicenseRenew::class;
            $masterModel = VSATLicenseMaster::class;
            $issue_tracking_number = VSATLicenseRenew::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'vsat_license_renew', '100', '51', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vsat_license_master', 'vsat-license-renew', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vsat_license_master', 'vsat-license-renew', $approver_desk_id, $old_cancel=0,$flag);
                }

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['15'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['5'])) {
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'vsat_license_renew', '100', '51', '', '');
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'VSAT License Renew', $processInfo, $approver_desk_id, 'vsat_license_master', 'vsat-license-renew', $flag);
            }elseif (in_array($status_id, ['47'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }

            return true;
            break;

        case 15: //VSAT License Amendment
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vsat_license_master', 'vsat-license-ammendment', $approver_desk_id, 'generate');

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['15'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }

            return true;
            break;
        case 16: // VSAT Surrender
            $model = VSATLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vsat_license_master', 'vsat_license_cancellation', $approver_desk_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VSATLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'VSAT License Surrender', $processInfo, $approver_desk_id, 'vsat_license_master', 'vsat_license_cancellation');
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 17: // IIG license issue
            $model = IIGLicenseIssue::class;
            $masterModel = IIGLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iig_license_issue', '001', '48', '', '');

                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iig_license_master', 'iig-license-issue', $approver_desk_id, 'generate',$process_list_id);

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IIGLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IIGLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IIGLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IIGLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['15'])) {
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iig_license_issue', '001', '48', '', '');
                //updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'iig_license_master', 'iig_license_issue', 1, 1);

                //Have to calculate fixed amount
//                $total_amount = DB::table('sp_payment_configuration')->where([
//                    'process_type_id' => 17,
//                    'payment_step_id' => 2
//                ])->value('amount');
//                $appInfo['total_amount'] = $total_amount;
//                //SMS send for request for payment
//                $userMobile = $receiverInformation[0]['user_mobile'];
//                $loginControllerInstance = new LoginController();
//                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'IIG License Issue', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
//                //Send email for request for payment.
//                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            }
            elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'IIG License Issue', $processInfo, $approver_desk_id, 'iig_license_master', 'iig-license-issue');
            } elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 18:
                $model = IIGLicenseRenew::class;
                $masterModel = IIGLicenseMaster::class;
                $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

    //            $isp_license_type_value = ITCLicenseIssue::where('id', $appInfo['app_id'])->value('isp_license_type');



                if (in_array($status_id, ['25'])) { // approve and certificate
                    ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                    approvedPayOrderVerification($processInfo, 2);
    //                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');

                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iig_license_master', 'iig-license-renew', $approver_desk_id, 'generate', $process_list_id);
                    if (!$certificateGenerate) {
                        \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                        return false;
                    }
                }
                elseif (in_array($status_id, ['5'])) {
    //                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                    //generate pdf and send email and sms for application shortfall
                    generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue');

                }
                elseif (in_array($status_id, ['30'])) {
                    if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                        SonaliPayment::where([
                            'app_id' => $processInfo->ref_id,
                            'payment_step_id' => 1,
                            'process_type_id' => $processInfo->process_type_id,
                            'payment_type' => 'pay_order'
                        ])
                            ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                    } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                        SonaliPayment::where([
                            'app_id' => $processInfo->ref_id,
                            'payment_step_id' => 1,
                            'process_type_id' => $processInfo->process_type_id,
                            'payment_type' => 'pay_order'
                        ])
                            ->update(['is_pay_order_verified' => 0]);
                    }
                    if (isset($requestData['dd_file_1'])) {
                        $file_one = $requestData['dd_file_1'];
                        $original_file = $file_one->getClientOriginalName();
                        $file_one->move('uploads/', time() . $original_file);
                        IIGLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                    }
                    if (isset($requestData['dd_file_2'])) {
                        $file_one = $requestData['dd_file_2'];
                        $original_file = $file_one->getClientOriginalName();
                        $file_one->move('uploads/', time() . $original_file);
                        IIGLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                    }
                    if (isset($requestData['dd_file_3'])) {
                        $file_one = $requestData['dd_file_3'];
                        $original_file = $file_one->getClientOriginalName();
                        $file_one->move('uploads/', time() . $original_file);
                        IIGLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                    }
                }
                elseif (in_array($status_id, ['54'])) {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_step_id' => 3,
                        'payment_status' => -1,
                        'payment_type' => 'pay_order'
                    ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
                }
                elseif (in_array($status_id, ['55'])) {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_step_id' => 4,
                        'payment_status' => -1,
                        'payment_type' => 'pay_order'
                    ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
                }
                elseif (in_array($status_id, ['56'])) {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_step_id' => 5,
                        'payment_status' => -1,
                        'payment_type' => 'pay_order'
                    ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
                }
                elseif (in_array($status_id, ['57'])) {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_step_id' => 6,
                        'payment_status' => -1,
                        'payment_type' => 'pay_order'
                    ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
                }
                elseif (in_array($status_id, ['47'])) {
                    shortfallPayOrderVerification($requestData, $processInfo, 1);
                    updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
                }
                elseif (in_array($status_id, ['15'])) {

                }
                elseif (in_array($status_id, ['60'])) {
                    if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                        SonaliPayment::where([
                            'app_id' => $processInfo->ref_id,
                            'payment_step_id' => 1,
                            'process_type_id' => $processInfo->process_type_id,
                            'payment_type' => 'pay_order'
                        ])
                            ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                    } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                        SonaliPayment::where([
                            'app_id' => $processInfo->ref_id,
                            'payment_step_id' => 1,
                            'process_type_id' => $processInfo->process_type_id,
                            'payment_type' => 'pay_order'
                        ])
                            ->update(['is_pay_order_verified' => 0]);
                    }
                    // updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iig_license_renew', '', '', $isp_license_type_value, 'isp_license_type');
                    certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iig_license_master', 'iig-license-renew', $approver_desk_id, 'generate', $process_list_id);
                    //Generate pdf for request for annual or bg payment
                    generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'IIG License Issue', $processInfo, $approver_desk_id, 'iig_license_master', 'iig-license-issue', $process_list_id, $status_id);
                }
                elseif (in_array($status_id, ['65'])) {
                    ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 7,
                        'process_type_id' => $processInfo->process_type_id,
                        'is_bg' => 1
                    ])->update(['payment_status' => 1]);
                    // updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iig_license_master', 'iig-license-renew', $approver_desk_id, 'generate', $process_list_id);
                    if (!$certificateGenerate) {
                        \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                        return false;
                    }
                }

                return true;
            break;
        case 19: // iig ammendment
                if (in_array($status_id, ['25'])) {
                    ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iig_license_master', 'iig-license-ammendment', $approver_desk_id);

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                }

                return true;
            break;
        case 20: // iig surrender
            $model = IIGLicenseSurrender::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iig_license_master', 'iig-license-cancellation', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }


            return true;
            break;
        case 23:

            $model = IPTSPLicenseAmendment::class;
            $masterModel = IPTSPLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            $issue_tracking_number = IPTSPLicenseMaster::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptstp-license-ammendment', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptstp-license-ammendment', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['5'])) {

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');

                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'IPTSP License Amendment', $processInfo, $approver_desk_id, 'iptsp_license_master', 'iptstp-license-ammendment', $flag);
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 25: // TVAS license issue

            $model = TVASLicenseIssue::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);


            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tvas_license_master', 'tvas-license-issue', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tvas_license_master', 'tvas-license-issue', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['15'])) {

                //Have to calculate fixed amount
                $total_amount = DB::table('sp_payment_configuration')->where([
                    'process_type_id' => 25,
                    'payment_step_id' => 2
                ])->value('amount');

                $appInfo['total_amount'] = $total_amount;

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'TVAS License Issue', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'TVAS License Issue', $processInfo, $approver_desk_id, 'tvas_license_master', 'tvas-license-issue', $flag);
            } elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 26: // TVAS license renew

            $model = TVASLicenseRenew::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tvas_license_master', 'tvas-license-renew', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tvas_license_master', 'tvas-license-renew', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }

//                //SMS send for application approved
//                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $appInfo, $processInfo, 'TVAS License Renew', $model);

            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'TVAS License Renew', $processInfo, $approver_desk_id, 'tvas_license_master', 'tvas-license-renew', $flag);

            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                }

                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }

            } elseif (in_array($status_id, ['15'])) {

                //Have to calculate fixed amount
                $total_amount = DB::table('sp_payment_configuration')->where([
                    'process_type_id' => 26,
                    'payment_step_id' => 2
                ])->value('amount');

                $appInfo['total_amount'] = $total_amount;

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'TVAS License Renew', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            } elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 27: // TVAS license amendment

            $model = TVASLicenseAmendment::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tvas_license_master', 'tvas-license-ammendment', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tvas_license_master', 'tvas-license-ammendment', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TVASLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'TVAS License Amendment', $processInfo, $approver_desk_id, 'tvas_license_master', 'tvas-license-ammendment', $flag);
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 21: // IPTSP license issue
            $model = IPTSPLicenseIssue::class;
            $masterModel = IPTSPLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptsp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptsp-license-issue', $approver_desk_id, $old_cancel=0,$flag);
                }

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['5'])) {
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Renew', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-renew', $flag);
            }
            elseif (in_array($status_id, ['30'])) {

                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }


                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['69'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 7,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // sixth year payment status
            }
            elseif (in_array($status_id, ['70'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 8,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // seventh year payment status
            }
            elseif (in_array($status_id, ['71'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 9,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eighth year payment status
            }
            elseif (in_array($status_id, ['72'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 10,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // nineth year payment status
            }
            elseif (in_array($status_id, ['73'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 11,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // tenth year payment status
            }
            elseif (in_array($status_id, ['74'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 12,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eleventh year payment status
            }
            elseif (in_array($status_id, ['75'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 13,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Twelveth year payment status
            }
            elseif (in_array($status_id, ['76'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 14,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Thirteenth year payment status
            }
            elseif (in_array($status_id, ['77'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 15,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fourteenth year payment status
            }
            elseif (in_array($status_id, ['78'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 16,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fifteenth year payment status
            }
            elseif (in_array($status_id, ['15'])) {

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');

                requestForPaymentIPTSP($receiverInformation[0]['user_mobile'], $processInfo, $model, $appInfo, $receiverInformation, 'IPTSP License Issue');
            }

            return true;
            break;
        case 22: // IPTSP license renew
            $model = IPTSPLicenseRenew::class;
            $masterModel = IPTSPLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptsp-license-renew', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'iptsp_license_master', 'iptsp-license-renew', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            }
            elseif (in_array($status_id, ['30'])) {

                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }


                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    IPTSPLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }

            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['69'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 7,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // sixth year payment status
            }
            elseif (in_array($status_id, ['70'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 8,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // seventh year payment status
            }
            elseif (in_array($status_id, ['71'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 9,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eighth year payment status
            }
            elseif (in_array($status_id, ['72'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 10,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // nineth year payment status
            }
            elseif (in_array($status_id, ['73'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 11,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // tenth year payment status
            }
            elseif (in_array($status_id, ['74'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 12,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eleventh year payment status
            }
            elseif (in_array($status_id, ['75'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 13,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Twelveth year payment status
            }
            elseif (in_array($status_id, ['76'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 14,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Thirteenth year payment status
            }
            elseif (in_array($status_id, ['77'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 15,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fourteenth year payment status
            }
            elseif (in_array($status_id, ['78'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 16,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fifteenth year payment status
            }
            elseif (in_array($status_id, ['15'])) {

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');

                requestForPaymentIPTSP($receiverInformation[0]['user_mobile'], $processInfo, $model, $appInfo, $receiverInformation, 'IPTSP License Renew');
            }
            elseif (in_array($status_id, ['5'])) {

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'iptsp_license_issue', '200', '41', '', '');

                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'IPTSP License Renew', $processInfo, $approver_desk_id, 'iptsp_license_master', 'iptsp-license-renew', $flag);
            }

            return true;
            break;
        case 29:

            $model = VTSLicenseIssue::class;
            $masterModel = VTSLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                approvedPayOrderVerification($processInfo, 2);

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'vts_license_issue', '49', '001', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vts_license_master', 'vts-license-issue', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vts_license_master', 'vts-license-issue', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {

                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }

                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['5'])) {

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'vts_license_issue', '49', '001', '', '');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'VTS License Issue', $processInfo, $approver_desk_id, 'vts_license_master', 'vts-license-issue', $flag);
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'vts_license_issue', '49', '001', '', '');

                $total_amount = fixedPyamentCalculation($processInfo->process_type_id, 2);

                $appInfo['total_amount'] = $total_amount;

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'VTS License Issue', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            }

            return true;
            break;
        case 30:
            $model = VTSLicenseRenew::class;
            $masterModel = VTSLicenseMaster::class;
            $issue_tracking_number = VTSLicenseRenew::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                approvedPayOrderVerification($processInfo, 2);

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'vts_license_issue', '49', '001', '', '');
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vts_license_master', 'vts-license-renew', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vts_license_master', 'vts-license-renew', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {

                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }


                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }

            }
            elseif (in_array($status_id, ['5'])) {

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'vts_license_issue', '49', '001', '', '');

                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'VTS License Renew', $processInfo, $approver_desk_id, 'vts_license_master', 'vts-license-renew', $flag);
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {
                $total_amount = fixedPyamentCalculation($processInfo->process_type_id, 2);
                $appInfo['total_amount'] = $total_amount;

                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'vts_license_issue', '49', '001', '', '');

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'VTS License Renew', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            }

            return true;
            break;
        case 83:
            $model = VTSLicenseAmendment::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vts_license_master', 'vts-license-amendment', $approver_desk_id, 'generate');
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vts_license_master', 'vts-license-amendment', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }

            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, $service_name, $processInfo, $approver_desk_id, 'vts_license_master', 'vts-license-amendment', $flag);
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 84:
            $model = VTSLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'vts_license_master', 'vts-license-surrender', $approver_desk_id, 'generate');
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    VTSLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }

            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, $service_name, $processInfo, $approver_desk_id, 'vts_license_master', 'vts-license-surrender');
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 37: // IGW license issue
            $model = IGWLicenseIssue::class;
            $masterModel = IGWLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'igw_license_issue', '001', '48', '', '');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'igw_license_master', 'igw-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    \App\Modules\REUSELicenseIssue\Models\IGW\issue\IGWLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    \App\Modules\REUSELicenseIssue\Models\IGW\issue\IGWLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    \App\Modules\REUSELicenseIssue\Models\IGW\issue\IGWLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    \App\Modules\REUSELicenseIssue\Models\IGW\issue\IGWLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'call_center_issue', '001', '48', '', '');
                //updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'call_center_master', 'call_center_license_issue', 1, 1);

//                //Have to calculate fixed amount
//                $total_amount = DB::table('sp_payment_configuration')->where([
//                    'process_type_id' => 25,
//                    'payment_step_id' => 2
//                ])->value('amount');
//
//                $appInfo['total_amount'] = $total_amount;
//
//                //SMS send for request for payment
//                $userMobile = $receiverInformation[0]['user_mobile'];
//                $loginControllerInstance = new LoginController();
//                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'IGW License Issue', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
//                //Send email for request for payment.
//                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'IGW License Issue', $processInfo, $approver_desk_id, 'igw_license_master', 'igw-license-issue');
            } elseif (in_array($status_id, ['47'])) {

    //                shortfallPayOrderVerification($requestData, $processInfo, 1);
    //                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 38: // IGW Renew
            $model = IGWLicenseRenew::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'igw_license_master', 'igw-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }


                return true;
            break;
        case 40: // IGW Surrender
            $model = IGWLicenseSurrender::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'igw_license_master', 'igw-license-cancellation', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }


                return true;
            break;

            case 57: // IGW Renew
            $model =ITCLicenseSurrender::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'itc_license_master', 'itc_license_cancellation', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }


                return true;
            break;
        case 50: // NTTN license issue
            $model = NTTNLicenseIssue::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 2,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_type' => 'pay_order'
                ])->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-issue', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-issue', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'NTTN License Issue', $processInfo, $approver_desk_id, 'nttn_license_master', 'nttn-license-issue', $flag);
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['47'])) {

                shortfallPayOrderVerification($requestData, $processInfo, 1);

                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason

            } elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            } elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            } elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            } elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            } elseif (in_array($status_id, ['69'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 7,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // sixth year payment status
            } elseif (in_array($status_id, ['70'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 8,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // seventh year payment status
            } elseif (in_array($status_id, ['71'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 9,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eighth year payment status
            } elseif (in_array($status_id, ['72'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 10,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // nineth year payment status
            } elseif (in_array($status_id, ['73'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 11,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // tenth year payment status
            } elseif (in_array($status_id, ['74'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 12,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eleventh year payment status
            } elseif (in_array($status_id, ['75'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 13,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Twelveth year payment status
            } elseif (in_array($status_id, ['76'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 14,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Thirteenth year payment status
            } elseif (in_array($status_id, ['77'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 15,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fourteenth year payment status
            } elseif (in_array($status_id, ['78'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 16,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fifteenth year payment status
            } elseif (in_array($status_id, ['15'])) {

                //Have to calculate fixed amount
                $total_amount = DB::table('sp_payment_configuration')->where([
                    'process_type_id' => 50,
                    'payment_step_id' => 2
                ])->value('amount');

                $appInfo['total_amount'] = $total_amount;

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $service_name, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            }

            return true;
            break;
        case 51:
            $model = NTTNLicenseRenew::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-renew', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-renew', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'NTTN License Renew', $processInfo, $approver_desk_id, 'nttn_license_master', 'nttn-license-renew', $flag);
            } elseif (in_array($status_id, ['47'])) {

                shortfallPayOrderVerification($requestData, $processInfo, 1);

                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            } elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            } elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            } elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            } elseif (in_array($status_id, ['69'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 7,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // sixth year payment status
            } elseif (in_array($status_id, ['70'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 8,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // seventh year payment status
            } elseif (in_array($status_id, ['71'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 9,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eighth year payment status
            } elseif (in_array($status_id, ['72'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 10,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // nineth year payment status
            } elseif (in_array($status_id, ['73'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 11,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // tenth year payment status
            } elseif (in_array($status_id, ['74'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 12,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // eleventh year payment status
            } elseif (in_array($status_id, ['75'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 13,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Twelveth year payment status
            } elseif (in_array($status_id, ['76'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 14,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Thirteenth year payment status
            } elseif (in_array($status_id, ['77'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 15,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fourteenth year payment status
            } elseif (in_array($status_id, ['78'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 16,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // Fifteenth year payment status
            } elseif (in_array($status_id, ['15'])) {

                //Have to calculate fixed amount
                $total_amount = DB::table('sp_payment_configuration')->where([
                    'process_type_id' => 51,
                    'payment_step_id' => 2
                ])->value('amount');

                $appInfo['total_amount'] = $total_amount;

                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $service_name, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            }

            return true;
            break;
        case 52: // NTTN license amendment

            $model = NTTNLicenseAmendment::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-amendment', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-amendment', $approver_desk_id, $old_cancel=0,$flag);
                }

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }

                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';

//                //SMS send for application approved
//                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $appInfo, $processInfo, 'TVAS License Amendment', $model);

            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseAmendment::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'NTTN License Amendment', $processInfo, $approver_desk_id, 'nttn_license_master', 'nttn-license-ammendment', $flag);
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 53: // NTTN License Surrender

            $model = NTTNLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                if($flag == 0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-cancellation', $approver_desk_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'nttn_license_master', 'nttn-license-cancellation', $approver_desk_id, $old_cancel=0,$flag);
                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }

                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    NTTNLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'NTTN License Surrender', $processInfo, $approver_desk_id, 'nttn_license_master', 'nttn-license-cancellation', $flag);
            } elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 74: // BWA Issue
            $model = BWALicenseIssue::class;
            $masterModel = BWALicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 2])
                    ->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'bwa_license_issue', '001', '48', '', '');

                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'bwa_license_master', 'bwa-license-issue', $approver_desk_id, 'generate', $process_list_id);

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                if (isset($requestData['pay_order_verification'])) {
                    $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
                    $pay_status = $pay_order_status ? 1 : -1;
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update([
                            'is_pay_order_verified' => $pay_order_status,
                            'payment_status' => $pay_status
                        ]);
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'bwa_license_issue', '001', '48', '', '');
                //updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'bwa_license_master', 'bwa_license_issue', 1, 1);
            }

            return true;
            break;
        case 75: // BWA Renew
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 2])
                    ->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'bwa_license_master', 'bwa-license-renew', $approver_desk_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                if (isset($requestData['pay_order_verification'])) {
                    $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
                    $pay_status = $pay_order_status ? 1 : -1;
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update([
                            'is_pay_order_verified' => $pay_order_status,
                            'payment_status' => $pay_status
                        ]);
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }

            return true;
            break;
        case 78:
            $model =SSLicenseIssue::class;
            $masterModel = SSLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'ss_license_issue', '001', '48', '', '');
                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'ss_license_master', 'ss-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                   SSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    SSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    SSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    SSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            } elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'ss_license_issue', '001', '48', '', '');
                //updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                //Have to calculate fixed amount
                $total_amount = DB::table('sp_payment_configuration')->where([
                    'process_type_id' => 25,
                    'payment_step_id' => 2
                ])->value('amount');
                $appInfo['total_amount'] = $total_amount;
                //SMS send for request for payment
                $userMobile = $receiverInformation[0]['user_mobile'];
                $loginControllerInstance = new LoginController();
                $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'SS License Issue', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                //Send email for request for payment.
                CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'SS License Issue', $processInfo, $approver_desk_id, 'ss_license_master', 'ss-license-issue');
            } elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 79: // ssLicenseRenew
                $model = SSLicenseRenew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
                if (in_array($status_id, ['25'])) {
                    ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                    //Verify pay order information
                    approvedPayOrderVerification($processInfo, 2);
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'ss_license_master', 'ss-license-renew', $approver_desk_id, 'generate');
                    if (!$certificateGenerate) {
                        \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                        return false;
                    }

                } elseif (in_array($status_id, ['5'])) {
                    //generate pdf and send email and sms for application shortfall
                    generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'SS License Renew', $processInfo, $approver_desk_id, 'ss_license_master', 'ss-license-renew');

                } elseif (in_array($status_id, ['30'])) {
                    if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                        SonaliPayment::where([
                            'app_id' => $processInfo->ref_id,
                            'payment_step_id' => 1,
                            'process_type_id' => $processInfo->process_type_id,
                            'payment_type' => 'pay_order'
                        ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                    }

                    if (isset($requestData['dd_file_1'])) {
                        $file_one = $requestData['dd_file_1'];
                        $original_file = $file_one->getClientOriginalName();
                        $file_one->move('uploads/', time() . $original_file);
                        SSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                    }
                    if (isset($requestData['dd_file_2'])) {
                        $file_one = $requestData['dd_file_2'];
                        $original_file = $file_one->getClientOriginalName();
                        $file_one->move('uploads/', time() . $original_file);
                        SSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                    }
                    if (isset($requestData['dd_file_3'])) {
                        $file_one = $requestData['dd_file_3'];
                        $original_file = $file_one->getClientOriginalName();
                        $file_one->move('uploads/', time() . $original_file);
                        SSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                    }
                    if (isset($requestData['dd_file_4'])) {
                        $file_one = $requestData['dd_file_4'];
                        $original_file = $file_one->getClientOriginalName();
                        $file_one->move('uploads/', time() . $original_file);
                        SSLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                    }

                } elseif (in_array($status_id, ['15'])) {

                    //Have to calculate fixed amount
                    $total_amount = DB::table('sp_payment_configuration')->where([
                        'process_type_id' => 26,
                        'payment_step_id' => 2
                    ])->value('amount');

                    $appInfo['total_amount'] = $total_amount;

                    //SMS send for request for payment
                    $userMobile = $receiverInformation[0]['user_mobile'];
                    $loginControllerInstance = new LoginController();
                    $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => 'SS License Renew', '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $total_amount], $userMobile);
                    //Send email for request for payment.
                    CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);
                } elseif (in_array($status_id, ['47'])) {
                    shortfallPayOrderVerification($requestData, $processInfo, 1);
                    updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
                }
                return true;
            break;
        case 66: // Tower Share
            $model = TCLicenseIssue::class;
            $masterModel = TCLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 2])
                    ->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'tc_license_issue', '001', '48', '', '');;
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tc_license_master', 'tc-license-issue', $approver_desk_id,'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                if (isset($requestData['pay_order_verification'])) {
                    $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
                    $pay_status = $pay_order_status ? 1 : -1;
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update([
                            'is_pay_order_verified' => $pay_order_status,
                            'payment_status' => $pay_status
                        ]);
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ISPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'tc_license_issue', '001', '48', '', '');
                //updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'call_center_master', 'call_center_license_issue', 1, 1);
            }

            return true;
            break;
        case 67: // TC
                if (in_array($status_id, ['25'])) {

                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 2])
                               ->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);

                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tc_license_master', 'tc-license-renew', $approver_desk_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                if (isset($requestData['pay_order_verification'])) {
                    $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
                    $pay_status = $pay_order_status ? 1 : -1;
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                                   ->update([
                                       'is_pay_order_verified' => $pay_order_status,
                                       'payment_status' => $pay_status
                                   ]);
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                                   ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                                   ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TCLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TCLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    TCLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            return true;
        break;
        case 68:
            $model = TCLicenseAmendment::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tc_license_master', 'tc-license-amendment', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
                return true;
            break;
        case 69: // TC suren
            $model = TCLicenseSurrender::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'tc_license_master', 'tc-license-cancellation', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
            return true;
        break;
        case 58: // MNO license issue
            $model = MNOLicenseIssue::class;
            $masterModel = MNOLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 2])
                    ->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);

                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'mno_license_issue', '001', '48', '', '');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mno_license_master', 'mno-license-issue', $approver_desk_id, 'generate', $process_list_id);

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                if (isset($requestData['pay_order_verification'])) {
                    $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
                    $pay_status = $pay_order_status ? 1 : -1;
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update([
                            'is_pay_order_verified' => $pay_order_status,
                            'payment_status' => $pay_status
                        ]);
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNOLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNOLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNOLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'mno_license_issue', '001', '48', '', '');
                //updateSarokNo($processInfo, $model, 'call_center_issue', '001', '48', '', ''); //update sharok_no for Payment Request
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'mno_license_master', 'mno_license_issue', 1, 1);
            }

            return true;
            break;
        case 59: // MNo license Renew
            $model = \App\Modules\REUSELicenseIssue\Models\MNO\renew\MNOLicenseRenew::class;
            $masterModel = \App\Modules\REUSELicenseIssue\Models\MNO\issue\MNOLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) { // approve and certificate
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);

                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mno_license_master', 'mno-license-renew', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'MNO License Renew', $processInfo, $approver_desk_id, 'mno_license_master', 'mno-license-renew');

            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {

            }
            elseif (in_array($status_id, ['60'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'mno_license_renew');
                certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mno_license_master', 'mno-license-issue', $approver_desk_id, 'generate', $process_list_id);
                //Generate pdf for request for annual or bg payment
                generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'MNO License Issue', $processInfo, $approver_desk_id, 'mno_license_master', 'mno-license-issue', $process_list_id, $status_id);
            }
            elseif (in_array($status_id, ['65'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 7,
                    'process_type_id' => $processInfo->process_type_id,
                    'is_bg' => 1
                ])->update(['payment_status' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'mno_license_issue', '');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mno_license_master', 'mno-license-renew', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;
        case 60: // MNO ammenment
            $model = MNOLicenseAmendment::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mnp_license_master', 'mnp-license-amendment', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;
        case 61: // MNO Surrender

            $model = MNOLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mno_license_master', 'mno-license-surrender', $approver_desk_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNOLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'MNO License Surrender', $processInfo, $approver_desk_id, 'mno_license_master', 'mno-license-surrender');
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 33: // ICX license issue
            $model = ICXLicenseIssue::class;
            $masterModel = ICXLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'icx_license_issue', '001', '48', '', '');
            if($flag == 0){
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'icx_license_master', 'icx-license-issue', $approver_desk_id, 'generate', $process_list_id, $flag);
            }else{
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'icx_license_master', 'icx-license-issue', $approver_desk_id, 'generate', $process_list_id, $old_cancel = 0, $flag);

            }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'icx_license_issue', '001', '48', '', '');
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'icx_license_master', 'icx-license-issue', 1, 1, $flag);
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
               generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ICX License Issue', $processInfo, $approver_desk_id, 'icx_license_master', 'icx-license-issue', $flag);
            } elseif (in_array($status_id, ['47'])) {

                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 34: // ICX license Renew
            $model = ICXLicenseRenew::class;
            $masterModel = ICXLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) { // approve and certificate
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
                if($flag==0){
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'icx_license_master', 'icx-license-renew', $approver_desk_id, 'generate', $process_list_id);
                }else{
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'icx_license_master', 'icx-license-renew', $approver_desk_id, 'generate', $process_list_id,$old_cancel=0,$flag);

                }
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['5'])) {
//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ICX License Renew', $processInfo, $approver_desk_id, 'icx_license_master', 'icx-license-renew');

            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseRenew::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {

            }
            elseif (in_array($status_id, ['60'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'icx_license_renew');
                certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'icx_license_master', 'icx-license-renew', $approver_desk_id, 'generate', $process_list_id);
                //Generate pdf for request for annual or bg payment
                generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ICX License Renew', $processInfo, $approver_desk_id, 'icx_license_master', 'icx-license-renew', $process_list_id, $status_id);
            }
            elseif (in_array($status_id, ['65'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 7,
                    'process_type_id' => $processInfo->process_type_id,
                    'is_bg' => 1
                ])->update(['payment_status' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'icx_license_renew');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'icx_license_master', 'icx-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
        case 35: // ICX license amendment
            $model = ICXLicenseAmendment::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mnp_license_master', 'mnp-license-amendment', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;

        case 36: // ICX Surrender

            $model = ICXLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'icx_license_master', 'icx-license-surrender', $approver_desk_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    ICXLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ICX License Surrender', $processInfo, $approver_desk_id, 'icx_license_master', 'icx-license-surrender');
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;



        case 70: // MNP license issue
            $model = MNPLicenseIssue::class;
            $masterModel = MNPLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);


            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);

                //Verify pay order information
                approvedPayOrderVerification($processInfo, 2);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'mnp_license_issue', '001', '48', '', '');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mnp_license_master', 'mnp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_4'])) {
                    $file_one = $requestData['dd_file_4'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_4' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'mnp_license_issue', '001', '48', '', '');
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'mnp_license_master', 'mnp_license_issue', 1, 1);
            } elseif (in_array($status_id, ['5'])) {
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'MNP License Issue', $processInfo, $approver_desk_id, 'mnp_license_master', 'mnp-license-issue');
            } elseif (in_array($status_id, ['47'])) {

//                shortfallPayOrderVerification($requestData, $processInfo, 1);
//                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            return true;
            break;
        case 71: // MNP license Renew
            $model = MNPLicenseRenew::class;
            $masterModel = MNPLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

//            $isp_license_type_value = ITCLicenseIssue::where('id', $appInfo['app_id'])->value('isp_license_type');



            if (in_array($status_id, ['25'])) { // approve and certificate
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                approvedPayOrderVerification($processInfo, 2);
//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');

                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mnp_license_master', 'mnp-license-renew', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            }
            elseif (in_array($status_id, ['5'])) {
//                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                //generate pdf and send email and sms for application shortfall
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue');

            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            elseif (in_array($status_id, ['54'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 3,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // second year payment status
            }
            elseif (in_array($status_id, ['55'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 4,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // third year payment status
            }
            elseif (in_array($status_id, ['56'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 5,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fourth year payment status
            }
            elseif (in_array($status_id, ['57'])) {
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'process_type_id' => $processInfo->process_type_id,
                    'payment_step_id' => 6,
                    'payment_status' => -1,
                    'payment_type' => 'pay_order'
                ])->update(['is_pay_order_verified' => 1, 'payment_status' => 1]); // fifth year payment status
            }
            elseif (in_array($status_id, ['47'])) {
                shortfallPayOrderVerification($requestData, $processInfo, 1);
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            elseif (in_array($status_id, ['15'])) {

            }
            elseif (in_array($status_id, ['60'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    SonaliPayment::where([
                        'app_id' => $processInfo->ref_id,
                        'payment_step_id' => 1,
                        'process_type_id' => $processInfo->process_type_id,
                        'payment_type' => 'pay_order'
                    ])
                        ->update(['is_pay_order_verified' => 0]);
                }
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                //Generate pdf for request for annual or bg payment
                generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'ISP License Issue', $processInfo, $approver_desk_id, 'isp_license_master', 'isp-license-issue', $process_list_id, $status_id);
            }
            elseif (in_array($status_id, ['65'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                SonaliPayment::where([
                    'app_id' => $processInfo->ref_id,
                    'payment_step_id' => 7,
                    'process_type_id' => $processInfo->process_type_id,
                    'is_bg' => 1
                ])->update(['payment_status' => 1]);
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'isp_license_issue', '', '', $isp_license_type_value, 'isp_license_type');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'isp_license_master', 'isp-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;

        case 77:
            $model = BWALicenseSurrender::class;
            # SET STATUS
            define("SHORTFALL_STATUS", 47);
            define("OTHER_NUMBER_OF_LICENSE_STATUS", 25);
            define("SEND_TO_DD_STATUS", 30);

            if ($status_id == constant("SHORTFALL_STATUS")) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }elseif ($status_id == constant("OTHER_NUMBER_OF_LICENSE_STATUS")) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'bwa_license_master', 'pstn-license-cancellation', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }elseif ($status_id == constant("SEND_TO_DD_STATUS")) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    BWALicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }
            return true;
            break;
        case 80: // SS
            $model = \App\Modules\REUSELicenseIssue\Models\SS\amendment\SSLicenseAmendment::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'ss_license_master', 'ss-license-amendment', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;
        case 56: // ITC
            $model =ITCLicenseAmendment::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'itc_license_master', 'itc-license-amendment', $approver_desk_id, 'generate', $process_list_id);

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;



        case 72: // MNP
            $model = MNPLicenseAmendment::class;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mnp_license_master', 'mnp-license-amendment', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }

            return true;
            break;
        case 73: // MNP Surrender
            $model = MNPLicenseSurrender::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'mnp_license_master', 'mnp-license-surrender', $approver_desk_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    MNPLicenseSurrender::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'MNP License Surrender', $processInfo, $approver_desk_id, 'mnp_license_master', 'mnp-license-surrender');
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;
        case 76: // BWA
            $model = BWALicenseAmendment::class;
            $masterModel = BWALicenseMaster::class;
            $issue_tracking_number = BWALicenseMaster::where('id', $appInfo['app_id'])->value("issue_tracking_no");
            $process_info_for_sharok = $processInfo->toArray();
            $process_info_for_sharok['tracking_no'] = $issue_tracking_number;
            $process_info_for_sharok = (object)$process_info_for_sharok;
            if (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                updateSarokNoV2($process_info_for_sharok, $masterModel, 'issue_tracking_no', $model, 'bwa_license_amendment', '001', '48', '', '');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'bwa_license_master', 'bwa-license-amendment', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                    return false;
                }
            }
            return true;
            break;
        case 62: // SCS license issue
            $model = SCSLicenseIssue::class;
            $masterModel = SCSLicenseMaster::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);

            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 2])
                    ->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);
                    updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'scs_license_issue', '001', '48', '', '');
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'scs_license_master', 'scs-license-issue', $approver_desk_id, 'generate', $process_list_id);
                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
            } elseif (in_array($status_id, ['5'])) {
                if (isset($requestData['pay_order_verification'])) {
                    $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
                    $pay_status = $pay_order_status ? 1 : -1;
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update([
                            'is_pay_order_verified' => $pay_order_status,
                            'payment_status' => $pay_status
                        ]);
                }
            } elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'YES') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 1, 'payment_status' => 1]);
                } elseif (isset($requestData['pay_order_verification']) && $requestData['pay_order_verification'] === 'NO') {
                    PayOrderPayment::where(['app_id' => $processInfo->ref_id, 'payment_step_id' => 1])
                        ->update(['is_pay_order_verified' => 0]);
                }
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    SCSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_2'])) {
                    $file_one = $requestData['dd_file_2'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    SCSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_2' => 'uploads/' . time() . $original_file]);
                }
                if (isset($requestData['dd_file_3'])) {
                    $file_one = $requestData['dd_file_3'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    SCSLicenseIssue::where('id', $processInfo->ref_id)->update(['dd_file_3' => 'uploads/' . time() . $original_file]);
                }
            }elseif (in_array($status_id, ['15'])) {
                //updated Sarok No
                updateSarokNoV2($processInfo, $masterModel, 'issue_tracking_no', $model, 'scs_license_issue', '001', '48', '', '');
                requestForPaymentV2($receiverInformation[0]['user_mobile'], $processInfo, $appInfo, $receiverInformation, $service_name, $approver_desk_id, 'scs_license_master', 'scs_license_issue', 1, 1);
            }

            return true;
            break;
            case 63: // SCS renew

                $model = SCSLicenseRenew::class;
                if (in_array($status_id, ['47'])) {
                    updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
                }
                if (in_array($status_id, ['25'])) {
                    ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'scs_license_master', 'scs-license-renew', $approver_desk_id, 'generate', $process_list_id);
                    if (!$certificateGenerate) {
                        \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                        return false;
                    }
                }
                    return true;
                break;
            case 64: // SCS
                $model = SCSLicenseAmendment::class;
                if (in_array($status_id, ['47'])) {
                    updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
                }
                if (in_array($status_id, ['25'])) {
                    ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'scs_license_master', 'scs_license_amendment', $approver_desk_id, 'generate', $process_list_id);
                    if (!$certificateGenerate) {
                        \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                        return false;
                    }
                }
                return true;
            break;
            case 65: // SCS
                $model = SCSLicenseSurrender::class;
                if (in_array($status_id, ['47'])) {
                    updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
                }
                if (in_array($status_id, ['25'])) {
                    ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                    $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'scs_license_master', 'scs_license_surrender', $approver_desk_id, 'generate', $process_list_id);
                    if (!$certificateGenerate) {
                        \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');
                        return false;
                    }
                }
                return true;
            break;
            case 39: // IGW Amendment
            $model = \App\Modules\REUSELicenseIssue\Models\IGW\amendment\IGWLicenseAmendment::class;
            $receiverInformation = CommonFunction::getReceiverInfo($model, $processInfo);
            if (in_array($status_id, ['25'])) {
                ProcessList::where('id', $process_list_id)->update(['completed_date' => date('Y-m-d H:i:s')]);
                $certificateGenerate = certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, 'igw_license_master', 'igw-license-ammendment', $approver_desk_id, 'generate');

                if (!$certificateGenerate) {
                    \Session::flash('error', 'Sorry! an unknown error occurred in the certificate generation process! [PPC-1218]');

                    return false;
                }
                $appInfo['attachment_certificate_name'] = 'ind_reg_apps.certificate_link';
            }
            elseif (in_array($status_id, ['30'])) {
                if (isset($requestData['dd_file_1'])) {
                    $file_one = $requestData['dd_file_1'];
                    $original_file = $file_one->getClientOriginalName();
                    $file_one->move('uploads/', time() . $original_file);
                    Amendment::where('id', $processInfo->ref_id)->update(['dd_file_1' => 'uploads/' . time() . $original_file]);
                }
            }
            else if (in_array($status_id, ['5'])) {
                generatePDFLinkAndSendSMSEmailForShortfall($receiverInformation[0]['user_mobile'], $appInfo, $receiverInformation, 'IGW License Amendment', $processInfo, $approver_desk_id, 'igw_license_master', 'igw-license-ammendment');
            }
            elseif (in_array($status_id, ['47'])) {
                updateShortfallReason($requestData['shortfall_reason'], $processInfo, $model);//update shortfall Reason
            }

            return true;
            break;

        default:
            \Session::flash('error', 'Unknown process type for Certificate and Others. [PPC-1200]');

            return false;
            break;


    }
}

function pdf_diff($status_id, $pdf_gen_number = 0)
{
    $pdf_diff_value = 0;
    if ($status_id == 5) {
        $pdf_diff_value = 1;
    } elseif ($status_id == 15) {
        $pdf_diff_value = 2;
    } elseif ($status_id == 25 && $pdf_gen_number == 1) {
        $pdf_diff_value = 3;
    } elseif($status_id == 60 && $pdf_gen_number == 1){
        $pdf_diff_value = 4;
    }

    return $pdf_diff_value;
}

/**
 * @param $app_id
 * @param $process_type_id
 * @param int $approver_desk_id
 * @param string $certificate_type
 * @param string $certificate_name
 * @param $master_table
 *
 * @return bool
 */
function certificateGenerationRequest($app_id, $process_type_id, $master_table, $certificate_name, $approver_desk_id = 0, $certificate_type = 'generate', $process_list_id = '',$old_cancel=0, $flag = 0): bool
{
    try {
        // Generating service wise new license
        $processInfo = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where([
                'process_list.ref_id' => $app_id,
                'process_list.process_type_id' => $process_type_id
            ])->first([
            //    'process_list.id',
                'process_list.company_id',
                'process_type.service_code',
                'process_list.status_id',
                'process_list.tracking_no'
            ]);


        $service_code = $processInfo->service_code;
        $company_id = $processInfo->company_id;
        $licenseInfo = DB::table("$master_table as apps")->where('apps.company_id', $company_id)
            ->first([
                DB::raw("CONCAT('14.32.0000.702.',LPAD('$service_code',2,'0'),'.',LPAD($company_id,3,'0'),'.',DATE_FORMAT(NOW(), '%y'),'.',COUNT(apps.id)+1) AS license_no")
            ]);
        switch ($process_type_id) {

            case 1: // ISP License Issue
                //============================
                if (in_array($processInfo->status_id, [5, 15, 25, 65])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = \Carbon\Carbon::now()->add(5, 'years');
                $license_expire_date = $license_expire_date->subDay()->format('Y-m-d h:i:sa');
                $ispLicenseIssue = ISPLicenseIssue::find($app_id);
//                $isplicense_no = generateLicenseNo($process_type_id, 'isp_license_issue', '', '', $ispLicenseIssue->isp_license_type, 'isp_license_type');

                $ispLicenseMaster = ISPLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($ispLicenseMaster) && empty($ispLicenseMaster)) {
                    $ispLicenseMaster = new ISPLicenseMaster();
                }

                $process_list = ProcessList::find($process_list_id);

                // ISP license master data isnert
                $ispLicenseMaster->issue_tracking_no = $ispLicenseIssue->tracking_no;
                $ispLicenseMaster->company_id = $ispLicenseIssue->company_id;
                $ispLicenseMaster->org_nm = $ispLicenseIssue->org_nm;
                $ispLicenseMaster->org_type = $ispLicenseIssue->org_type;
                $ispLicenseMaster->org_mobile = $ispLicenseIssue->org_mobile;
                $ispLicenseMaster->org_phone = $ispLicenseIssue->org_phone;
                $ispLicenseMaster->org_email = $ispLicenseIssue->org_email;
                $ispLicenseMaster->org_district = $ispLicenseIssue->reg_office_district;
                $ispLicenseMaster->org_upazila = $ispLicenseIssue->reg_office_thana;
                $ispLicenseMaster->org_address = $ispLicenseIssue->reg_office_address;
                $ispLicenseMaster->org_website = $ispLicenseIssue->org_website;
                $ispLicenseMaster->isp_license_type = $ispLicenseIssue->isp_license_type;
                $ispLicenseMaster->isp_license_division = $ispLicenseIssue->isp_license_division;
                $ispLicenseMaster->isp_license_district = $ispLicenseIssue->isp_license_district;
                $ispLicenseMaster->isp_license_upazila = $ispLicenseIssue->isp_license_upazila;
                $ispLicenseMaster->status = 1;
                $ispLicenseMaster->created_at = Carbon::now();

                // license info
                $ispLicenseMaster->license_no = $ispLicenseMaster->sharok_no;
                $ispLicenseMaster->license_issue_date = $license_issue_date;
                $ispLicenseMaster->expiry_date = $license_expire_date;

                $ispLicenseMaster->save();

                // license info
                $ispLicenseIssue->license_no = $ispLicenseMaster->sharok_no;
                $ispLicenseIssue->license_issue_date = $license_issue_date;
                $ispLicenseIssue->expiry_date = $license_expire_date;
                $ispLicenseIssue->save();

                // process list
                if(isset( $process_list) && $process_list){
                    $process_list->license_no = $ispLicenseMaster->sharok_no;
                    $process_list->save();
                }
                return true;

                break;

            case 2: // ISP License Renew
                if (in_array($processInfo->status_id, [5, 15, 25, 65])) {
                    break;
                }
                $ispLicenseRenewData = ISPLicenseRenew::find($app_id);
                if (empty($ispLicenseRenewData->issue_tracking_no)) {
                    $ispLicenseMaster = new ISPLicenseMaster();

                    $ispLicenseMaster->renew_tracking_no = $ispLicenseRenewData->tracking_no;
                    $ispLicenseMaster->company_id = $ispLicenseRenewData->company_id;
                    $ispLicenseMaster->org_nm = $ispLicenseRenewData->org_nm;
                    $ispLicenseMaster->org_type = $ispLicenseRenewData->org_type;
                    $ispLicenseMaster->org_mobile = $ispLicenseRenewData->applicant_mobile;
                    $ispLicenseMaster->org_phone = $ispLicenseRenewData->applicant_telephone;
                    $ispLicenseMaster->org_email = $ispLicenseRenewData->applicant_email;
                    $ispLicenseMaster->org_district = $ispLicenseRenewData->applicant_district;
                    $ispLicenseMaster->org_upazila = $ispLicenseRenewData->applicant_thana;
                    $ispLicenseMaster->org_address = $ispLicenseRenewData->applicant_address;
                    $ispLicenseMaster->isp_license_type = $ispLicenseRenewData->isp_license_type;
                    $ispLicenseMaster->isp_license_division = $ispLicenseRenewData->isp_license_division;
                    $ispLicenseMaster->isp_license_district = $ispLicenseRenewData->isp_license_district;
                    $ispLicenseMaster->isp_license_upazila = $ispLicenseRenewData->isp_license_upazila;
                    $ispLicenseMaster->status = 1;
                    $ispLicenseMaster->created_at = Carbon::now();

                    // license info
                    $ispLicenseMaster->license_no = $ispLicenseRenewData->license_no;
                    $ispLicenseMaster->license_issue_date = $ispLicenseRenewData->issue_date;
                    $ispLicenseMaster->expiry_date = $ispLicenseRenewData->expiry_date;
                    $ispLicenseMaster->save();
                } else {
                    // update license expiry date
                    $ISPLicenseRenewData = ISPLicenseRenew::Join('isp_license_master as master', 'master.issue_tracking_no', '=', 'isp_license_renew.issue_tracking_no')
                        ->where(['isp_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'isp_license_renew.tracking_no as renew_tracking_no'
                        ]);

                    if (empty($ISPLicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($ISPLicenseRenewData->expiry_date . '+5 years'));
                    ISPLicenseRenew::find($app_id)
                        ->update([
                            'expiry_date' => $new_exp_date,
                        ]);

                    ISPLicenseMaster::where('id', $ISPLicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $ISPLicenseRenewData->renew_tracking_no,
                    ]);
                }
                break;

            case 3: // ISP License Amendment
                $LicenseData = ISPLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = ISPLicenseAmendment::Join('isp_license_master as master', 'master.license_no', '=', 'isp_license_amendment.license_no')
                    ->where(['isp_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'isp_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = ISPLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            case 4: // ISP License Surrender
                $licenseData = ISPLicenseSurrender::Join('isp_license_master as master', 'master.license_no', '=', 'isp_license_surrender.license_no')
                    ->where(['isp_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'isp_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                ISPLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'isp_license_type' => null,
                    'isp_license_division' => null,
                    'isp_license_district' => null,
                    'isp_license_upazila' => null,
                    'status' => 0,
                ]);
                break;
            case 81:
                if ($processInfo->status_id === 5) {
                    break;
                }
                $licenseData = SSLicenseSurrender::Join('ss_license_master as master', 'master.license_no', '=', 'ss_license_surrender.license_no')
                                                 ->where(['ss_license_surrender.id' => $app_id])
                                                 ->first([
                                                     'master.*',
                                                     'ss_license_surrender.tracking_no'
                                                 ]);
                if (empty($licenseData)) {
                    return false;
                }
                SSLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;
            case 24: // ISP License Surrender
                $licenseData = IPTSPLicenseSurrender::Join('iptsp_license_master as master', 'master.license_no', '=', 'iptsp_license_surrender.license_no')
                    ->where(['iptsp_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'iptsp_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                IPTSPLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;
            case 28: // ISP License Surrender
                $licenseData = TVASLicenseSurrender::Join('tvas_license_master as master', 'master.license_no', '=', 'tvas_license_surrender.license_no')
                    ->where(['tvas_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'tvas_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                TVASLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;

            case 5: // bpo issue
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = \Carbon\Carbon::now()->add(5, 'years');
                $license_expire_date = $license_expire_date->subDay()->format('Y-m-d h:i:sa');
                //  $license_no = generateLicenseNo($process_type_id, 'call_center_issue', '001', '48');

                $callCenterLicenseData = CallCenterNew::find($app_id);

                $process_list = ProcessList::find($process_list_id);

                $callCenterMaster = CallCenterMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();


                if (!isset($callCenterMaster) && empty($callCenterMaster)) {
                    $callCenterMaster = new CallCenterMaster();
                }

                $callCenterMaster->company_id = $callCenterLicenseData->company_id;
                $callCenterMaster->org_nm = $callCenterLicenseData->company_name;
                $callCenterMaster->issue_tracking_no = $callCenterLicenseData->tracking_no;
                $callCenterMaster->org_type = $callCenterLicenseData->company_type;
                $callCenterMaster->org_mobile = $callCenterLicenseData->applicant_mobile;
                $callCenterMaster->org_phone = $callCenterLicenseData->applicant_telephone;
                $callCenterMaster->org_email = $callCenterLicenseData->applicant_email;
//                $callCenterMaster->proposal_service_type = $callCenterLicenseData->proposal_service_type;


                // license info
                $callCenterMaster->license_no = $callCenterMaster->sharok_no;
                $callCenterMaster->license_issue_date = $license_issue_date;
                $callCenterMaster->expiry_date = $license_expire_date;

                $callCenterMaster->org_district = $callCenterLicenseData->applicant_district;
                $callCenterMaster->org_upazila = $callCenterLicenseData->applicant_thana;
                $callCenterMaster->org_address = $callCenterLicenseData->applicant_address;
                $callCenterMaster->org_website = $callCenterLicenseData->applicant_website;
                $callCenterMaster->status = 1;
                $callCenterMaster->created_at = \Carbon\Carbon::now();
                $callCenterMaster->save();


                // license info
                $callCenterLicenseData->license_no = $callCenterMaster->sharok_no;
                $callCenterLicenseData->license_issue_date = $license_issue_date;
                $callCenterLicenseData->expiry_date = $license_expire_date;
                $callCenterLicenseData->save();

                // process list
                $process_list->license_no = $callCenterMaster->sharok_no;
                $process_list->save();
                break;

            case 6: // bpo renew
                $LicenseData = CallCenterRenew::find($app_id);
                if (empty($LicenseData->issue_tracking_no)) {
                    $callCenterMaster = new CallCenterMaster();
                    $callCenterMaster->company_id = $LicenseData->company_id;
                    $callCenterMaster->org_nm = $LicenseData->company_name;
                    $callCenterMaster->renew_tracking_no = $LicenseData->tracking_no;
                    $callCenterMaster->org_type = $LicenseData->company_type;
                    $callCenterMaster->org_mobile = $LicenseData->applicant_mobile;
                    $callCenterMaster->org_phone = $LicenseData->applicant_telephone;
                    $callCenterMaster->org_email = $LicenseData->applicant_email;

                    // license info
                    $callCenterMaster->license_no = $LicenseData->license_no;
                    $callCenterMaster->license_issue_date = $LicenseData->issue_date;
                    $callCenterMaster->expiry_date = $LicenseData->expiry_date;

                    $callCenterMaster->org_district = $LicenseData->applicant_district;
                    $callCenterMaster->org_upazila = $LicenseData->applicant_thana;
                    $callCenterMaster->org_address = $LicenseData->applicant_address;
                    $callCenterMaster->org_website = $LicenseData->applicant_website;
                    $callCenterMaster->status = 1;
                    $callCenterMaster->created_at = \Carbon\Carbon::now();
                    $callCenterMaster->save();
                } else {

                    // update license expiry date
                    $LicenseRenewData = CallCenterRenew::Join('call_center_master as master', 'master.issue_tracking_no', '=', 'call_center_renew.issue_tracking_no')
                        ->where(['call_center_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'call_center_renew.tracking_no as renew_tracking_no'
                        ]);
                    if (empty($LicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($LicenseRenewData->expiry_date . '+5 years -1 day'));
                    $license_renew_date=date('Y-m-d', strtotime($LicenseRenewData->expiry_date));

                    CallCenterRenew::find($app_id)
                        ->update([
                            'expiry_date' => $new_exp_date,
                            'license_renew_date'=>$license_renew_date,
                        ]);

                    CallCenterMaster::where('id', $LicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $LicenseRenewData->renew_tracking_no,
                        'license_renew_date'=>$license_renew_date,
                    ]);
                }
                break;

            case 7: // bpo amendment
                $LicenseData = Amendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = Amendment::Join('call_center_master as master', 'master.license_no', '=', 'call_center_amendment.license_no')
                    ->where(['call_center_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'call_center_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = CallCenterMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            case 8: // Call Center License Surrender
                $licenseData = CallCenterSurrender::Join('call_center_master as master', 'master.license_no', '=', 'call_center_surrender.license_no')
                    ->where(['call_center_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'call_center_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                CallCenterMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;

             case 57: // ITC License Surrender
                $licenseData = ITCLicenseSurrender::Join('itc_license_master as master', 'master.license_no', '=', 'itc_license_cancellation.license_no')
                    ->where(['itc_license_cancellation.id' => $app_id])
                    ->first([
                        'master.*',
                        'itc_license_cancellation.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                ITCLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;

            case 9:
                if ($processInfo->status_id === 5) {
                    break;
                }
                // NIX License Issue
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+3 years'));
                $nix_license_no = generateLicenseNo($process_type_id, 'nix_license_issue', '400', '42');

                $nixLicenseData = NIXLicenseIssue::find($app_id);

                $nixMaster = new NIXLicenseMaster();
                $nixMaster->company_id = $nixLicenseData->company_id;
                $nixMaster->org_nm = $nixLicenseData->company_name;
                $nixMaster->issue_tracking_no = $nixLicenseData->tracking_no;
                $nixMaster->org_type = $nixLicenseData->company_type;
                $nixMaster->org_mobile = $nixLicenseData->applicant_mobile;
                $nixMaster->org_phone = $nixLicenseData->applicant_telephone;
                $nixMaster->org_email = $nixLicenseData->applicant_email;

                // license info
                $nixMaster->license_no = $nix_license_no;
                $nixMaster->license_issue_date = $license_issue_date;
                $nixMaster->expiry_date = $license_expire_date;

                $nixMaster->org_district = $nixLicenseData->applicant_district;
                $nixMaster->org_upazila = $nixLicenseData->applicant_thana;
                $nixMaster->org_address = $nixLicenseData->applicant_address;
                $nixMaster->org_website = $nixLicenseData->applicant_website;
                $nixMaster->status = 1;
                $nixMaster->created_at = \Carbon\Carbon::now();
                $nixMaster->save();


                // license info
                $nixLicenseData->license_no = $nix_license_no;
                $nixLicenseData->license_issue_date = $license_issue_date;
                $nixLicenseData->expiry_date = $license_expire_date;
                $nixLicenseData->save();

                break;

            case 10: // NIX License Renew

                $nixLicenseData = NIXLicenseRenew::find($app_id);
                if (empty($nixLicenseData->issue_tracking_no)) {
                    $nixMaster = new NIXLicenseMaster();
                    $nixMaster->company_id = $nixLicenseData->company_id;
                    $nixMaster->org_nm = $nixLicenseData->company_name;
                    $nixMaster->renew_tracking_no = $nixLicenseData->tracking_no;
                    $nixMaster->org_type = $nixLicenseData->company_type;
                    $nixMaster->org_mobile = $nixLicenseData->applicant_mobile;
                    $nixMaster->org_phone = $nixLicenseData->applicant_telephone;
                    $nixMaster->org_email = $nixLicenseData->applicant_email;

                    // license info
                    $nixMaster->license_no = $nixLicenseData->license_no;
                    $nixMaster->license_issue_date = $nixLicenseData->issue_date;
                    $nixMaster->expiry_date = $nixLicenseData->expiry_date;

                    $nixMaster->org_district = $nixLicenseData->applicant_district;
                    $nixMaster->org_upazila = $nixLicenseData->applicant_thana;
                    $nixMaster->org_address = $nixLicenseData->applicant_address;
                    $nixMaster->org_website = $nixLicenseData->applicant_website;
                    $nixMaster->status = 1;
                    $nixMaster->created_at = \Carbon\Carbon::now();
                    $nixMaster->save();
                } else {
                    // update license expiry date
                    $NixLicenseRenewData = NIXLicenseRenew::Join('nix_license_master as master', 'master.issue_tracking_no', '=', 'nix_license_renew.issue_tracking_no')
                        ->where(['nix_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'nix_license_renew.tracking_no as renew_tracking_no'
                        ]);
                    if (empty($NixLicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($NixLicenseRenewData->expiry_date . '+5 years'));


                    NIXLicenseRenew::find($app_id)
                        ->update([
                            'expiry_date' => $new_exp_date,
                        ]);

                    NIXLicenseMaster::where('id', $NixLicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $NixLicenseRenewData->renew_tracking_no,
                    ]);
                }

                break;

            case 11: // NIX License Amendment
                $LicenseRenewData = NIXLicenseAmendment::Join('nix_license_master as master', 'master.license_no', '=', 'nix_license_amendment.license_no')
                    ->where(['nix_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'nix_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = NIXLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;

            case 12:
                $licenseData = NIXLicenseSurrender::Join('nix_license_master as master', 'master.license_no', '=', 'nix_license_surrender.license_no')
                    ->where(['nix_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'nix_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                NIXLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;

            case 13: // VSAT License Issue

                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }

                $current_date = \Carbon\Carbon::now();
                $expiry_date = date('Y-m-d h:i:sa', strtotime($current_date . '+3 years'));


                $vsatLicenseData = VSATLicenseIssue::find($app_id);
//
                $vsatMaster = VSATLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($vsatMaster) && empty($vsatMaster)) {
                    $vsatMaster = new VSATLicenseMaster();
                }

                $process_list = ProcessList::find($process_list_id);

                // VSAT license master data insert
                $vsatMaster->company_id = $vsatLicenseData->company_id;
                $vsatMaster->issue_tracking_no = $vsatLicenseData->tracking_no;
                $vsatMaster->license_no = $vsatMaster->sharok_no;
                $vsatMaster->license_issue_date = $current_date;
                $vsatMaster->expiry_date = $expiry_date;
                $vsatMaster->license_category = $vsatLicenseData->license_category;
                $vsatMaster->sattelite_type = $vsatLicenseData->sattelite_type;
                $vsatMaster->org_nm = $vsatLicenseData->org_nm;
                $vsatMaster->org_type = $vsatLicenseData->org_type;
                $vsatMaster->org_district = $vsatLicenseData->org_district;
                $vsatMaster->org_upazila = $vsatLicenseData->org_upazila;
                $vsatMaster->org_address = $vsatLicenseData->org_address;
                $vsatMaster->status = 1;
                $vsatMaster->created_at = \Carbon\Carbon::now();
                $vsatMaster->save();

                // process list
                $process_list->license_no = $vsatMaster->sharok_no;
                $process_list->save();
                // insert license info
                // license info
                $vsatLicenseData->license_no = $vsatMaster->sharok_no;
                $vsatLicenseData->license_issue_date = $current_date;
                $vsatLicenseData->expiry_date = $expiry_date;
                $vsatLicenseData->save();
                break;

            case 14: // VSAT License Renew

                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $vsatLicenseRenewData = VSATLicenseRenew::find($app_id);
                if (empty($vsatLicenseRenewData->issue_tracking_no)) {
                    $vsatLicenseMaster = new VSATLicenseMaster();

                    $vsatLicenseMaster->company_id = $vsatLicenseRenewData->company_id;
                    $vsatLicenseMaster->issue_tracking_no = $vsatLicenseRenewData->tracking_no;
                    $vsatLicenseMaster->license_no = $vsatLicenseRenewData->sharok_no;
                    $vsatLicenseMaster->license_category = $vsatLicenseRenewData->license_category;
                    $vsatLicenseMaster->sattelite_type = $vsatLicenseRenewData->sattelite_type;
                    $vsatLicenseMaster->org_nm = $vsatLicenseRenewData->org_nm;
                    $vsatLicenseMaster->org_type = $vsatLicenseRenewData->org_type;
                    $vsatLicenseMaster->org_district = $vsatLicenseRenewData->org_district;
                    $vsatLicenseMaster->org_upazila = $vsatLicenseRenewData->org_upazila;
                    $vsatLicenseMaster->org_address = $vsatLicenseRenewData->org_address;
                    $vsatLicenseMaster->status = 1;
                    $vsatLicenseMaster->created_at = \Carbon\Carbon::now();
                    $vsatLicenseMaster->save();

                    // license info
                    $vsatLicenseMaster->license_no = $vsatLicenseRenewData->license_no;
                    $vsatLicenseMaster->license_issue_date = $vsatLicenseRenewData->issue_date;
                    $vsatLicenseMaster->expiry_date = $vsatLicenseRenewData->expiry_date;
                    $vsatLicenseMaster->save();
                } else {
                    // update license expiry date
                    $VSATLicenseRenewData = VSATLicenseRenew::Join('vsat_license_master as master', 'master.issue_tracking_no', '=', 'vsat_license_renew.issue_tracking_no')
                        ->where(['vsat_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'vsat_license_renew.tracking_no as renew_tracking_no'
                        ]);

                    if (empty($VSATLicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($VSATLicenseRenewData->expiry_date . '+5 years'));

                    VSATLicenseRenew::find($app_id)
                        ->update([
                            'expiry_date' => $new_exp_date,
                        ]);

                    DB::table('vsat_license_master')->where('id', $VSATLicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $VSATLicenseRenewData->renew_tracking_no,
                    ]);
                }
                break;
            case 15: // VSAT License Ammentment
                $LicenseData = VSATLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = VSATLicenseAmendment::Join('vsat_license_master as master', 'master.license_no', '=', 'vsat_license_amendment.license_no')
                    ->where(['vsat_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'vsat_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = VSATLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;

            case 16: // VSAT License Surrender

                $licenseData = VSATLicenseSurrender::Join('vsat_license_master as master', 'master.license_no', '=', 'vsat_license_cancellation.license_no')
                    ->where(['vsat_license_cancellation.id' => $app_id])
                    ->first([
                        'master.*',
                        'vsat_license_cancellation.tracking_no'
                    ]);

                if (empty($licenseData)) {
                    return false;
                }
                VSATLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;
            case 17: // IIG License Issue
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
               // $license_expire_date = $license_expire_date->subDay()->format('Y-m-d h:i:sa');

                $itemLicenseData = IIGLicenseIssue::find($app_id);
                $process_list = ProcessList::find($process_list_id);
                $itemMaster = IIGLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();
                if (!isset($itemMaster) && empty($itemMaster)) {
                    $itemMaster = new IIGLicenseMaster();
                }


                $itemMaster->company_id = $itemLicenseData->company_id;
                $itemMaster->org_nm = $itemLicenseData->company_name;
                $itemMaster->issue_tracking_no = $itemLicenseData->tracking_no;
                $itemMaster->org_type = $itemLicenseData->company_type;
                $itemMaster->org_mobile = $itemLicenseData->applicant_mobile;
                $itemMaster->org_phone = $itemLicenseData->applicant_telephone;
                $itemMaster->org_email = $itemLicenseData->applicant_email;

                // license info
                $itemMaster->license_no = $itemMaster->sharok_no;
                $itemMaster->license_issue_date = $license_issue_date;
                $itemMaster->expiry_date = $license_expire_date;

                $itemMaster->org_district = $itemLicenseData->applicant_district;
                $itemMaster->org_upazila = $itemLicenseData->applicant_thana;
                $itemMaster->org_address = $itemLicenseData->applicant_address;
                $itemMaster->org_website = $itemLicenseData->applicant_website;
                $itemMaster->status = 1;
                $itemMaster->created_at = \Carbon\Carbon::now();
                $itemMaster->save();


                // license info
                $itemLicenseData->license_no = $itemMaster->sharok_no;
                $itemLicenseData->license_issue_date = $license_issue_date;
                $itemLicenseData->expiry_date = $license_expire_date;
                $itemLicenseData->save();

                // process list
                if(isset( $process_list) && $process_list){
                $process_list->license_no = $itemMaster->sharok_no;
                $process_list->save();
                }
                break;
            case 18: // IIG License Renew
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'mnp_license_renew', '001', '50');

                $itcLicenseData = IIGLicenseRenew::find($app_id);

                $itcMaster = new IIGLicenseMaster();
                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;

                // license info
                $itcMaster->license_no = $license_no;
                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();
            break;
        case 19: // iig ammendment
            $LicenseData = IIGLicenseAmendment::find($app_id);
            // update license expiry date
            $LicenseRenewData = IIGLicenseAmendment::Join('iig_license_master as master', 'master.license_no', '=', 'iig_license_amendment.license_no')
                ->where(['iig_license_amendment.id' => $app_id])
                ->first([
                    'master.*',
                    'iig_license_amendment.tracking_no'
                ]);
            if (empty($LicenseRenewData)) {
                return false;
            }

            $dd = IIGLicenseMaster::where('id', $LicenseRenewData->id)->update([
                'amendment_tracking_no' => $LicenseRenewData->tracking_no
            ]);
        break;
        case 20: // iig surrender

            if ($processInfo->status_id === 5) {
                break;
            }
            $licenseData = IIGLicenseSurrender::Join('iig_license_master as master', 'master.license_no', '=', 'iig_license_cancellation.license_no')
                ->where(['iig_license_cancellation.id' => $app_id])
                ->first([
                    'master.*',
                    'iig_license_cancellation.tracking_no'
                ]);
            if (empty($licenseData)) {
                return false;
            }
            IIGLicenseMaster::where('id', $licenseData->id)->update([
                'cancellation_tracking_no' => $licenseData->tracking_no,
                'status' => 0,
            ]);
            break;

            case 23: //IPTSP Amendment
                // update license expiry date
                $LicenseAmendmentData = IPTSPLicenseAmendment::Join('iptsp_license_master as master', 'master.license_no', '=', 'iptsp_license_amendment.license_no')
                    ->where(['iptsp_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'iptsp_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseAmendmentData)) {
                    return false;
                }

                $dd = IPTSPLicenseMaster::where('id', $LicenseAmendmentData->id)->update([
                    'amendment_tracking_no' => $LicenseAmendmentData->tracking_no
                ]);
                break;
            case 25: // TVAS License Issue
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'tvas_license_issue', '001', '50');

                $tvasLicenseData = TVASLicenseIssue::find($app_id);
                $tvasMaster = TVASLicenseMaster::where('issue_tracking_no',$processInfo->tracking_no)->latest()->first();
                if(!isset($tvasMaster) && empty($tvasMaster)){
                    $tvasMaster = new TVASLicenseMaster();
                }

                $tvasMaster->company_id = $tvasLicenseData->company_id;
                $tvasMaster->org_nm = $tvasLicenseData->company_name;
                $tvasMaster->issue_tracking_no = $tvasLicenseData->tracking_no;
                $tvasMaster->org_type = $tvasLicenseData->company_type;
                $tvasMaster->org_mobile = $tvasLicenseData->applicant_mobile;
                $tvasMaster->org_phone = $tvasLicenseData->applicant_telephone;
                $tvasMaster->org_email = $tvasLicenseData->applicant_email;
                $tvasMaster->license_no = $license_no;
                $tvasMaster->license_issue_date = $license_issue_date;
                $tvasMaster->expiry_date = $license_expire_date;
                $tvasMaster->org_district = $tvasLicenseData->applicant_district;
                $tvasMaster->org_upazila = $tvasLicenseData->applicant_thana;
                $tvasMaster->org_address = $tvasLicenseData->applicant_address;
                $tvasMaster->org_website = $tvasLicenseData->applicant_website;
                $tvasMaster->status = 1;
                $tvasMaster->created_at = \Carbon\Carbon::now();
                $tvasMaster->save();


                // license info
                $tvasLicenseData->license_no = $license_no;
                $tvasLicenseData->license_issue_date = $license_issue_date;
                $tvasLicenseData->expiry_date = $license_expire_date;
                $tvasLicenseData->save();

                $process_list = ProcessList::find($process_list_id);
                $process_list->license_no = $tvasMaster->license_no;
                $process_list->save();

                break;

            case 26:
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+3 years'));
                $licenseData = TVASLicenseRenew::find($app_id);
                if (empty($licenseData->issue_tracking_no)) {
                    $tvasMaster = new TVASLicenseMaster();
                    $tvasMaster->company_id = $licenseData->company_id;
                    $tvasMaster->org_nm = $licenseData->company_name;
                    $tvasMaster->renew_tracking_no = $licenseData->tracking_no;
                    $tvasMaster->org_type = $licenseData->company_type;
                    $tvasMaster->org_mobile = $licenseData->applicant_mobile;
                    $tvasMaster->org_phone = $licenseData->applicant_telephone;
                    $tvasMaster->org_email = $licenseData->applicant_email;

                    // license info
                    $tvasMaster->license_no = $licenseData->license_no;
                    $tvasMaster->license_issue_date = \Carbon\Carbon::now();
                    $tvasMaster->expiry_date = $license_expire_date;

                    $tvasMaster->org_district = $licenseData->applicant_district;
                    $tvasMaster->org_upazila = $licenseData->applicant_thana;
                    $tvasMaster->org_address = $licenseData->applicant_address;
                    $tvasMaster->org_website = $licenseData->applicant_website;
                    $tvasMaster->status = 1;
                    $tvasMaster->created_at = \Carbon\Carbon::now();
                    $tvasMaster->save();
                } else {
                    // update license expiry date
                    $tvasLicenseRenewData = TVASLicenseRenew::Join('tvas_license_master as master', 'master.issue_tracking_no', '=', 'tvas_license_renew.issue_tracking_no')
                        ->where(['tvas_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'tvas_license_renew.tracking_no as renew_tracking_no'
                        ]);
                    if (empty($tvasLicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($tvasLicenseRenewData->expiry_date . '+3 years'));

                    TVASLicenseMaster::where('id', $tvasLicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $tvasLicenseRenewData->renew_tracking_no,
                    ]);
                }

                break;
            case 27: // TVAS Amendment

                $LicenseData = TVASLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = TVASLicenseAmendment::Join('tvas_license_master as master', 'master.license_no', '=', 'tvas_license_amendment.license_no')
                    ->where(['tvas_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'tvas_license_amendment.tracking_no'
                    ]);
                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = TVASLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            case 29: // VTS License Issue
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = \Carbon\Carbon::now()->add(5, 'years');
                $license_expire_date = $license_expire_date->subDay()->format('Y-m-d h:i:sa');
                $vtsLicenseData = VTSLicenseIssue::find($app_id);
//                $vtsLicenseNo = generateLicenseNo($process_type_id, 'vts_license_issue', 49, '001');

                $vtsMaster = VTSLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($vtsMaster) && empty($vtsMaster)) {
                    $vtsMaster = new VTSLicenseMaster();
                }

                $process_list = ProcessList::find($process_list_id);

                // VTS license master data insert
//                $vtsMaster = new VTSLicenseMaster();
                $vtsMaster->company_id = $vtsLicenseData->company_id;
                $vtsMaster->issue_tracking_no = $vtsLicenseData->tracking_no;
                $vtsMaster->org_nm = $vtsLicenseData->org_nm;
                $vtsMaster->org_type = $vtsLicenseData->org_type;
                $vtsMaster->org_district = $vtsLicenseData->reg_office_district;
                $vtsMaster->org_upazila = $vtsLicenseData->reg_office_thana;
                $vtsMaster->org_address = $vtsLicenseData->reg_office_address;

                $vtsMaster->status = 1;
                $vtsMaster->created_at = \Carbon\Carbon::now();
                $vtsMaster->save();

                // license info
                $vtsMaster->license_no = $vtsMaster->sharok_no;
                $vtsMaster->license_issue_date = $license_issue_date;
                $vtsMaster->expiry_date = $license_expire_date;

                $vtsMaster->save();

                // license info
                $vtsLicenseData->license_no = $vtsMaster->sharok_no;
                $vtsLicenseData->license_issue_date = $license_issue_date;
                $vtsLicenseData->expiry_date = $license_expire_date;
                $vtsLicenseData->save();

                //Insert Process List data

                $process_list->license_no = $vtsMaster->sharok_no;
                $process_list->save();
                break;

            case 30: // VTS License Renew

                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }

                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+3 years'));
                $licenseData = VTSLicenseRenew::find($app_id);
                if (empty($licenseData->issue_tracking_no)) {
                    $master = new VTSLicenseMaster();
                    $master->company_id = $licenseData->company_id;
                    $master->org_nm = $licenseData->company_name;
                    $master->renew_tracking_no = $licenseData->tracking_no;
                    $master->org_type = $licenseData->company_type;
                    $master->org_mobile = $licenseData->applicant_mobile;
                    $master->org_phone = $licenseData->applicant_telephone;
                    $master->org_email = $licenseData->applicant_email;

                    // license info
                    $master->license_no = $licenseData->license_no;
                    $master->license_issue_date = \Carbon\Carbon::now();
                    $master->expiry_date = $license_expire_date;

                    $master->org_district = $licenseData->applicant_district;
                    $master->org_upazila = $licenseData->applicant_thana;
                    $master->org_address = $licenseData->applicant_address;
                    $master->org_website = $licenseData->applicant_website;
                    $master->status = 1;
                    $master->created_at = \Carbon\Carbon::now();
                    $master->save();
                } else {
                    // update license expiry date
                    $licenseRenewData = VTSLicenseRenew::Join('vts_license_master as master', 'master.issue_tracking_no', '=', 'vts_license_renew.issue_tracking_no')
                        ->where(['vts_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'vts_license_renew.tracking_no as renew_tracking_no'
                        ]);
                    if (empty($licenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($licenseRenewData->expiry_date . '+3 years'));

                    VTSLicenseMaster::where('id', $licenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $licenseRenewData->renew_tracking_no,
                    ]);
                }
                break;
            case 54: // ITC License Issue
                //============================
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'itc_license_issue', '001', '50');

                $itcLicenseData = ITCLicenseIssue::find($app_id);
                $process_list = ProcessList::find($process_list_id);
                $itcMaster = ITCLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();


                if (!isset($itcMaster) && empty($itcMaster)) {
                    $itcMaster = new ITCLicenseMaster();
                }

                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;
//                $itcMaster->license_no = $license_no;
                $itcMaster->license_no = $itcMaster->sharok_no;

                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
//                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_no = $itcMaster->sharok_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();


                // process list
                $process_list->license_no =$itcMaster->sharok_no;
                $process_list->save();
                break;
            case 55: // ITC License Renew
                //============================
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'itc_license_issue', '001', '50');

                $itcLicenseData = ITCLicenseRenew::find($app_id);

                $itcMaster = new ITCLicenseMaster();
                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;

                // license info
                $itcMaster->license_no = $license_no;
                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();
                break;
            case 74: // PSTN/BWA License Issue
                //============================
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
//                $license_no = generateLicenseNo($process_type_id, 'bwa_license_issue', '001', '50');

                $itcLicenseData = BWALicenseIssue::find($app_id);

                $process_list = ProcessList::find($process_list_id);

                $itcMaster = BWALicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();


                if (!isset($itcMaster) && empty($itcMaster)) {
                    $itcMaster = new BWALicenseMaster();
                }

                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;

                // license info
//                $itcMaster->license_no = $license_no;
                $itcMaster->license_no = $itcMaster->sharok_no;
                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
//                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_no = $itcMaster->sharok_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();

                // process list update license number
                $process_list->license_no = $itcMaster->sharok_no;
                $process_list->save();

                break;
            case 75: // PSTN/BWA License Issue
                //============================
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'bwa_license_renew', '001', '50');

                $itcLicenseData = BWALicenseRenew::find($app_id);

                $itcMaster = new BWALicenseMaster();
                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;

                // license info
                $itcMaster->license_no = $license_no;
                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();
                break;

            case 83: // VTS License Amendment
                $LicenseRenewData = VTSLicenseAmendment::Join('vts_license_master as master', 'master.license_no', '=', 'vts_license_amendment.license_no')
                    ->where(['vts_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'vts_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = VTSLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            case 84:
                $licenseData = VTSLicenseSurrender::Join('vts_license_master as master', 'master.license_no', '=', 'vts_license_surrender.license_no')
                    ->where(['vts_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'vts_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                VTSLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;
            case 21: // IPTSP License Issue

                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }

                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
//                $license_no = generateLicenseNo($process_type_id, 'iptsp_license_issue', '200', '41');

                $iptspLicenseData = IPTSPLicenseIssue::find($app_id);

                $iptspMaster = IPTSPLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();


                if (!isset($iptspMaster) && empty($iptspMaster)) {
                    $iptspMaster = new IPTSPLicenseMaster();
                }

                $process_list = ProcessList::find($process_list_id);

//                $iptspMaster = new IPTSPLicenseIssueMaster();
                $iptspMaster->company_id = $iptspLicenseData->company_id;
                $iptspMaster->org_nm = $iptspLicenseData->org_nm;
                $iptspMaster->issue_tracking_no = $iptspLicenseData->tracking_no;
                $iptspMaster->org_type = $iptspLicenseData->org_type;
                $iptspMaster->org_mobile = $iptspLicenseData->applicant_mobile;
                $iptspMaster->org_phone = $iptspLicenseData->applicant_telephone;
                $iptspMaster->org_email = $iptspLicenseData->applicant_email;

                // license info
                $iptspMaster->license_no = $iptspMaster->sharok_no;
                $iptspMaster->license_issue_date = $license_issue_date;
                $iptspMaster->expiry_date = $license_expire_date;

                $iptspMaster->org_district = $iptspLicenseData->applicant_district;
                $iptspMaster->org_upazila = $iptspLicenseData->applicant_upazila;
                $iptspMaster->org_address = $iptspLicenseData->applicant_address;
                $iptspMaster->org_website = $iptspLicenseData->applicant_website;
                $iptspMaster->status = 1;
                $iptspMaster->created_at = \Carbon\Carbon::now();
                $iptspMaster->save();


                // license info
                $iptspLicenseData->license_no = $iptspMaster->sharok_no;
                $iptspLicenseData->license_issue_date = $license_issue_date;
                $iptspLicenseData->expiry_date = $license_expire_date;
                $iptspLicenseData->save();

                // process list
                if(isset( $process_list) && $process_list){
                $process_list->license_no = $iptspMaster->sharok_no;
                $process_list->save();
                }

                break;
            case 22: // IPTSP License Renew
                $iptspLicenseData = IPTSPLicenseRenew::find($app_id);
                if (empty($iptspLicenseData->issue_tracking_no)) {
                    $iptspMaster = new IPTSPLicenseMaster();

                    $iptspMaster->renew_tracking_no = $iptspLicenseData->tracking_no;
                    $iptspMaster->company_id = $iptspLicenseData->company_id;
                    $iptspMaster->org_nm = $iptspLicenseData->org_nm;
                    $iptspMaster->org_type = $iptspLicenseData->org_type;
                    $iptspMaster->org_mobile = $iptspLicenseData->applicant_mobile;
                    $iptspMaster->org_phone = $iptspLicenseData->applicant_telephone;
                    $iptspMaster->org_email = $iptspLicenseData->applicant_email;

                    $iptspMaster->org_district = $iptspLicenseData->applicant_district;
                    $iptspMaster->org_upazila = $iptspLicenseData->applicant_upazila;
                    $iptspMaster->org_address = $iptspLicenseData->applicant_address;
                    $iptspMaster->org_website = $iptspLicenseData->applicant_website;
                    $iptspMaster->status = 1;
                    $iptspMaster->created_at = \Carbon\Carbon::now();

                    // license info
                    $iptspMaster->license_no = $iptspLicenseData->license_no;
                    $iptspMaster->license_issue_date = $iptspLicenseData->issue_date;
                    $iptspMaster->expiry_date = $iptspLicenseData->expiry_date;
                    $iptspMaster->save();
                } else {
                    // update license expiry date
                    $IPTSPLicenseRenewData = IPTSPLicenseRenew::Join('iptsp_license_master as master', 'master.issue_tracking_no', '=', 'iptsp_license_renew.issue_tracking_no')
                        ->where(['iptsp_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'iptsp_license_renew.tracking_no as renew_tracking_no'
                        ]);

                    if (empty($IPTSPLicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($IPTSPLicenseRenewData->expiry_date . '+3 years'));
                    IPTSPLicenseRenew::find($app_id)
                        ->update([
                            'expiry_date' => $new_exp_date,
                        ]);

                    IPTSPLicenseMaster::where('id', $IPTSPLicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $IPTSPLicenseRenewData->renew_tracking_no,
                    ]);
                }
                break;
            case 58: // MNO License Issue
                //============================
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));

                $mnoLicenseIssueObj = MNOLicenseIssue::find($app_id);

                $process_list = ProcessList::find($process_list_id);

                $mnoMasterObj = MNOLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($mnoMasterObj) && empty($mnoMasterObj)) {
                    $mnoMasterObj = new MNOLicenseMaster();
                }

                // ISP license master data isnert
                $mnoMasterObj->issue_tracking_no = $mnoLicenseIssueObj->tracking_no;
                $mnoMasterObj->company_id = $mnoLicenseIssueObj->company_id;
                $mnoMasterObj->org_nm = $mnoLicenseIssueObj->org_nm;
                $mnoMasterObj->org_type = $mnoLicenseIssueObj->org_type;
                $mnoMasterObj->org_mobile = $mnoLicenseIssueObj->org_mobile;
                $mnoMasterObj->org_phone = $mnoLicenseIssueObj->org_phone;
                $mnoMasterObj->org_email = $mnoLicenseIssueObj->org_email;
                $mnoMasterObj->org_district = $mnoLicenseIssueObj->org_district;
                $mnoMasterObj->org_upazila = $mnoLicenseIssueObj->org_upazila;
                $mnoMasterObj->org_address = $mnoLicenseIssueObj->org_address;
                $mnoMasterObj->org_website = $mnoLicenseIssueObj->org_website;
                // $mnoMasterObj->isp_license_type = $mnoLicenseIssueObj->isp_license_type;
                // $mnoMasterObj->isp_license_division = $mnoLicenseIssueObj->isp_license_division;
                // $mnoMasterObj->isp_license_district = $mnoLicenseIssueObj->isp_license_district;
                // $mnoMasterObj->isp_license_upazila = $mnoLicenseIssueObj->isp_license_upazila;
                $mnoMasterObj->status = 1;
                $mnoMasterObj->created_at = Carbon::now();

                // license info
                // $mnoMasterObj->license_no = $licenseInfo->license_no;
                $mnoMasterObj->license_no = $mnoMasterObj->sharok_no;
                $mnoMasterObj->license_issue_date = $license_issue_date;
                $mnoMasterObj->expiry_date = $license_expire_date;

                $mnoMasterObj->save();

                // license info
                // $mnoLicenseIssueObj->license_no = $licenseInfo->license_no;
                $mnoLicenseIssueObj->license_no =  $mnoMasterObj->sharok_no;
                $mnoLicenseIssueObj->license_issue_date = $license_issue_date;
                $mnoLicenseIssueObj->expiry_date = $license_expire_date;
                $mnoLicenseIssueObj->save();

                  // process list
                  $process_list->license_no =  $mnoMasterObj->sharok_no;
                  $process_list->save();

                break;

            case 59:
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'mno_license_renew', '001', '50');

                $itcLicenseData = \App\Modules\REUSELicenseIssue\Models\MNO\renew\MNOLicenseRenew::find($app_id);

                $itcMaster = new MNOLicenseMaster();
                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;

                // license info
                $itcMaster->license_no = $license_no;
                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();
                break;

            case 60:
                $LicenseData = MNOLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = MNOLicenseAmendment::Join('mno_license_master as master', 'master.license_no', '=', 'mno_license_amendment.license_no')
                    ->where(['mno_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'mno_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = MNOLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;

            case 61: // MNO surrender

                $licenseData = MNOLicenseSurrender::Join('mno_license_master as master', 'master.license_no', '=', 'mno_license_cancellation.license_no')
                    ->where(['mno_license_cancellation.id' => $app_id])
                    ->first([
                        'master.*',
                        'mno_license_cancellation.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                MNOLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;

            case 33: // ICX License Issue
                //============================
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'icx_license_issue', '001', '50');

                $icxLicenseIssueObj = ICXLicenseIssue::find($app_id);

                $process_list = ProcessList::find($process_list_id);
                $icxLicenseMasterObj = ICXLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($icxLicenseMasterObj) && empty($icxLicenseMasterObj)) {
                    $icxLicenseMasterObj = new ICXLicenseMaster();
                }

                $icxLicenseMasterObj->company_id = $icxLicenseIssueObj->company_id;
                $icxLicenseMasterObj->org_nm = $icxLicenseIssueObj->company_name;
                $icxLicenseMasterObj->issue_tracking_no = $icxLicenseIssueObj->tracking_no;
                $icxLicenseMasterObj->org_type = $icxLicenseIssueObj->company_type;
                $icxLicenseMasterObj->org_mobile = $icxLicenseIssueObj->applicant_mobile;
                $icxLicenseMasterObj->org_phone = $icxLicenseIssueObj->applicant_telephone;
                $icxLicenseMasterObj->org_email = $icxLicenseIssueObj->applicant_email;

                // license info
                $icxLicenseMasterObj->license_no = $icxLicenseMasterObj->sharok_no;
                $icxLicenseMasterObj->license_issue_date = $license_issue_date;
                $icxLicenseMasterObj->expiry_date = $license_expire_date;

                $icxLicenseMasterObj->org_district = $icxLicenseIssueObj->applicant_district;
                $icxLicenseMasterObj->org_upazila = $icxLicenseIssueObj->applicant_thana;
                $icxLicenseMasterObj->org_address = $icxLicenseIssueObj->applicant_address;
                $icxLicenseMasterObj->org_website = $icxLicenseIssueObj->applicant_website;
                $icxLicenseMasterObj->status = 1;
                $icxLicenseMasterObj->created_at = \Carbon\Carbon::now();
                $icxLicenseMasterObj->save();

                // license info
                $icxLicenseIssueObj->license_no = $icxLicenseMasterObj->sharok_no;
                $icxLicenseIssueObj->license_issue_date = $license_issue_date;
                $icxLicenseIssueObj->expiry_date = $license_expire_date;
                $icxLicenseIssueObj->save();

                // process list
                $process_list->license_no = $icxLicenseMasterObj->sharok_no;
                $process_list->save();

                break;

            case 34: // ICX License Renew
                if ($processInfo->status_id === 5) {
                    break;
                }
                $LicenseData = ICXLicenseRenew::find($app_id);

                if (empty($LicenseData->issue_tracking_no)) {
                    $itcMaster = new ICXLicenseMaster();
                    $itcMaster->company_id = $LicenseData->company_id;
                    $itcMaster->org_nm = $LicenseData->company_name;
                    $itcMaster->renew_tracking_no = $LicenseData->tracking_no;
                    $itcMaster->org_type = $LicenseData->company_type;
                    $itcMaster->org_mobile = $LicenseData->applicant_mobile;
                    $itcMaster->org_phone = $LicenseData->applicant_telephone;
                    $itcMaster->org_email = $LicenseData->applicant_email;

                    // license info
                    $itcMaster->license_no = $LicenseData->license_no;
                    $itcMaster->license_issue_date = $LicenseData->issue_date;
                    $itcMaster->expiry_date = $LicenseData->expiry_date;

                    $itcMaster->org_district = $LicenseData->applicant_district;
                    $itcMaster->org_upazila = $LicenseData->applicant_thana;
                    $itcMaster->org_address = $LicenseData->applicant_address;
                    $itcMaster->org_website = $LicenseData->applicant_website;
                    $itcMaster->status = 1;
                    $itcMaster->created_at = \Carbon\Carbon::now();

                    $itcMaster->save();
                } else {

                    // update license expiry date
                    $LicenseRenewData = ICXLicenseRenew::Join('icx_license_master as master', 'master.issue_tracking_no', '=', 'icx_license_renew.issue_tracking_no')
                        ->where(['icx_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'icx_license_renew.tracking_no as renew_tracking_no'
                        ]);
                    if (empty($LicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($LicenseRenewData->expiry_date . '+5 years'));


                    ICXLicenseRenew::find($app_id)
                        ->update([
                            'expiry_date' => $new_exp_date,
                        ]);

                    ICXLicenseMaster::where('id', $LicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $LicenseRenewData->renew_tracking_no,
                    ]);
                }
                break;

            case 35: // ICX Amendment
                $LicenseData = ICXLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = ICXLicenseAmendment::Join('icx_license_master as master', 'master.license_no', '=', 'icx_license_amendment.license_no')
                    ->where(['icx_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'icx_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = ICXLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;

            case 36: // ICX Surrender

                $licenseData = ICXLicenseSurrender::Join('icx_license_master as master', 'master.license_no', '=', 'icx_license_surrender.license_no')
                    ->where(['icx_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'icx_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                ICXLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;


            case 70: // MNP License Issue
                //============================

                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
//                $license_no = generateLicenseNo($process_type_id, 'mnp_license_issue', '001', '50');

                $mnpLicenseIssueObj = MNPLicenseIssue::find($app_id);

                $process_list = ProcessList::find($process_list_id);

                $mnpLicenseMasterObj = MNPLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($mnpLicenseMasterObj) && empty($mnpLicenseMasterObj)) {
                    $mnpLicenseMasterObj = new MNPLicenseMaster();
                }

                $mnpLicenseMasterObj->company_id = $mnpLicenseIssueObj->company_id;
                $mnpLicenseMasterObj->org_nm = $mnpLicenseIssueObj->company_name;
                $mnpLicenseMasterObj->issue_tracking_no = $mnpLicenseIssueObj->tracking_no;
                $mnpLicenseMasterObj->org_type = $mnpLicenseIssueObj->company_type;
                $mnpLicenseMasterObj->org_mobile = $mnpLicenseIssueObj->applicant_mobile;
                $mnpLicenseMasterObj->org_phone = $mnpLicenseIssueObj->applicant_telephone;
                $mnpLicenseMasterObj->org_email = $mnpLicenseIssueObj->applicant_email;

                // license info
                $mnpLicenseMasterObj->license_no = $mnpLicenseMasterObj->sharok_no;
                $mnpLicenseMasterObj->license_issue_date = $license_issue_date;
                $mnpLicenseMasterObj->expiry_date = $license_expire_date;

                $mnpLicenseMasterObj->org_district = $mnpLicenseIssueObj->applicant_district;
                $mnpLicenseMasterObj->org_upazila = $mnpLicenseIssueObj->applicant_thana;
                $mnpLicenseMasterObj->org_address = $mnpLicenseIssueObj->applicant_address;
                $mnpLicenseMasterObj->org_website = $mnpLicenseIssueObj->applicant_website;
                $mnpLicenseMasterObj->status = 1;
                $mnpLicenseMasterObj->created_at = \Carbon\Carbon::now();
                $mnpLicenseMasterObj->save();

                // license info
                $mnpLicenseIssueObj->license_no = $mnpLicenseMasterObj->sharok_no;
                $mnpLicenseIssueObj->license_issue_date = $license_issue_date;
                $mnpLicenseIssueObj->expiry_date = $license_expire_date;
                $mnpLicenseIssueObj->save();

                // process list
                $process_list->license_no = $mnpLicenseMasterObj->sharok_no;
                $process_list->save();

                break;

            case 37: // IGW License Issue
                //============================
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'igw_license_issue', '001', '50');

                $igwLicenseIssueObj = IGWLicenseIssue::find($app_id);

                $process_list = ProcessList::find($process_list_id);

                $igwMasterObj = IGWLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($igwMasterObj) && empty($igwMasterObj)) {
                    $igwMasterObj = new IGWLicenseMaster();
                }

                $igwMasterObj->company_id = $igwLicenseIssueObj->company_id;
                $igwMasterObj->org_nm = $igwLicenseIssueObj->company_name;
                $igwMasterObj->issue_tracking_no = $igwLicenseIssueObj->tracking_no;
                $igwMasterObj->org_type = $igwLicenseIssueObj->company_type;
                $igwMasterObj->org_mobile = $igwLicenseIssueObj->applicant_mobile;
                $igwMasterObj->org_phone = $igwLicenseIssueObj->applicant_telephone;
                $igwMasterObj->org_email = $igwLicenseIssueObj->applicant_email;

                // license info
//                $igwMasterObj->license_no = $license_no;
                $igwMasterObj->license_no = $igwMasterObj->sharok_no;
                $igwMasterObj->license_issue_date = $license_issue_date;
                $igwMasterObj->expiry_date = $license_expire_date;

                $igwMasterObj->org_district = $igwLicenseIssueObj->applicant_district;
                $igwMasterObj->org_upazila = $igwLicenseIssueObj->applicant_thana;
                $igwMasterObj->org_address = $igwLicenseIssueObj->applicant_address;
                $igwMasterObj->org_website = $igwLicenseIssueObj->applicant_website;
                $igwMasterObj->status = 1;
                $igwMasterObj->created_at = \Carbon\Carbon::now();
                $igwMasterObj->save();

                // license info
                $igwLicenseIssueObj->license_no = $igwMasterObj->sharok_no;
                $igwLicenseIssueObj->license_issue_date = $license_issue_date;
                $igwLicenseIssueObj->expiry_date = $license_expire_date;
                $igwLicenseIssueObj->save();

                // process list
                $process_list->license_no = $igwMasterObj->sharok_no;
                $process_list->save();

                break;

            case 38: // IGW License Renew
                //============================

                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'igw_license_renew', '001', '50');

                $tvasLicenseData = IGWLicenseRenew::find($app_id);
                $tvasMaster = new \App\Modules\IGWLicenseIssue\Models\IGWLicenseMaster();
                $tvasMaster->company_id = $tvasLicenseData->company_id;
                $tvasMaster->org_nm = $tvasLicenseData->company_name;
                $tvasMaster->issue_tracking_no = $tvasLicenseData->tracking_no;
                $tvasMaster->org_type = $tvasLicenseData->company_type;
                $tvasMaster->org_mobile = $tvasLicenseData->applicant_mobile;
                $tvasMaster->org_phone = $tvasLicenseData->applicant_telephone;
                $tvasMaster->org_email = $tvasLicenseData->applicant_email;

                // license info
                $tvasMaster->license_no = $license_no;
                $tvasMaster->license_issue_date = $license_issue_date;
                $tvasMaster->expiry_date = $license_expire_date;

                $tvasMaster->org_district = $tvasLicenseData->applicant_district;
                $tvasMaster->org_upazila = $tvasLicenseData->applicant_thana;
                $tvasMaster->org_address = $tvasLicenseData->applicant_address;
                $tvasMaster->org_website = $tvasLicenseData->applicant_website;
                $tvasMaster->status = 1;
                $tvasMaster->created_at = \Carbon\Carbon::now();
                $tvasMaster->save();

                // license info
                $tvasLicenseData->license_no = $license_no;
                $tvasLicenseData->issue_date = $license_issue_date;
                $tvasLicenseData->expiry_date = $license_expire_date;
                $tvasLicenseData->save();

                break;

            case 40: // IGW License Surrender
                //============================

                if ($processInfo->status_id === 5) {
                    break;
                }
                $licenseData = IGWLicenseSurrender::Join('igw_license_master as master', 'master.license_no', '=', 'igw_license_cancellation.license_no')
                    ->where(['igw_license_cancellation.id' => $app_id])
                    ->first([
                        'master.*',
                        'igw_license_cancellation.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                IGWLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;


            case 56: // ITC License Amend
                $LicenseData = ITCLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = ITCLicenseAmendment::Join('itc_license_master as master', 'master.license_no', '=', 'itc_license_amendment.license_no')
                    ->where(['itc_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'itc_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = ITCLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;

           case 68: // TC License Amendment
                //============================
                $LicenseData = TCLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = TCLicenseAmendment::Join('tc_license_master as master', 'master.license_no', '=', 'tc_license_amendment.license_no')
                    ->where(['tc_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'tc_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = TCLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            case 71: // MNP License Renew
                //============================
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'mnp_license_renew', '001', '50');

                $itcLicenseData = MNPLicenseRenew::find($app_id);

                $itcMaster = new MNPLicenseMaster();
                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;

                // license info
                $itcMaster->license_no = $license_no;
                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();
                break;

            case 72: // MNP License Amend
                //============================
                $LicenseData = MNPLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = MNPLicenseAmendment::Join('mnp_license_master as master', 'master.license_no', '=', 'mnp_license_amendment.license_no')
                    ->where(['mnp_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'mnp_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = MNPLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;

            case 73:
                $licenseData = MNPLicenseSurrender::Join('mnp_license_master as master', 'master.license_no', '=', 'mnp_license_surrender.license_no')
                    ->where(['mnp_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'mnp_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                MNPLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;
            case 76: // BWA License ammandment
                $LicenseData = BWALicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = BWALicenseAmendment::Join('bwa_license_master as master', 'master.license_no', '=', 'bwa_license_amendment.license_no')
                    ->where(['bwa_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'bwa_license_amendment.tracking_no'
                    ]);

                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = BWALicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            case 77:
                if ($processInfo->status_id === 5) {
                    break;
                }
                $licenseData = BWALicenseSurrender::Join('bwa_license_master as master', 'master.license_no', '=', 'bwa_license_surrender.license_no')
                    ->where(['bwa_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'bwa_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                BWALicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;
            case 78: // ss issue
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'ss_license_issue', '001', '50');
                $ssLicenseData = \App\Modules\REUSELicenseIssue\Models\SS\issue\SSLicenseIssue::find($app_id);
                $process_list = ProcessList::find($process_list_id);
                $ssMaster = SSLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($ssMaster) && empty($ssMaster)) {
                    $ssMaster = new SSLicenseMaster();
                }
                $ssMaster->company_id = $ssLicenseData->company_id;
                $ssMaster->org_nm = $ssLicenseData->company_name;
                $ssMaster->issue_tracking_no = $ssLicenseData->tracking_no;
                $ssMaster->org_type = $ssLicenseData->company_type;
                $ssMaster->org_mobile = $ssLicenseData->applicant_mobile;
                $ssMaster->org_phone = $ssLicenseData->applicant_telephone;
                $ssMaster->org_email = $ssLicenseData->applicant_email;

                // license info
               // $ssMaster->license_no = $license_no;
                $ssMaster->license_no = $ssMaster->sharok_no;
                $ssMaster->license_issue_date = $license_issue_date;
                $ssMaster->expiry_date = $license_expire_date;

                $ssMaster->org_district = $ssLicenseData->applicant_district;
                $ssMaster->org_upazila = $ssLicenseData->applicant_thana;
                $ssMaster->org_address = $ssLicenseData->applicant_address;
                $ssMaster->org_website = $ssLicenseData->applicant_website;
                $ssMaster->status = 1;
                $ssMaster->created_at = \Carbon\Carbon::now();
                $ssMaster->save();


                // license info
                //$ssLicenseData->license_no = $license_no;
                $ssLicenseData->license_no = $ssMaster->sharok_no;
                $ssLicenseData->license_issue_date = $license_issue_date;
                $ssLicenseData->expiry_date = $license_expire_date;
                $ssLicenseData->save();

                // process list
                $process_list->license_no = $ssMaster->sharok_no;
                $process_list->save();
                break;

            case 79: // SS License Renew
                //============================
                //============================
                if ($processInfo->status_id === 5) {
                    break;
                }

                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'ss_license_renew', '001', '50');
                $itcLicenseData = SSLicenseRenew::find($app_id);

                $itcMaster = new SSLicenseMaster();

                $itcMaster->company_id = $itcLicenseData->company_id;
                $itcMaster->org_nm = $itcLicenseData->company_name;
                $itcMaster->issue_tracking_no = $itcLicenseData->tracking_no;
                $itcMaster->org_type = $itcLicenseData->company_type;
                $itcMaster->org_mobile = $itcLicenseData->applicant_mobile;
                $itcMaster->org_phone = $itcLicenseData->applicant_telephone;
                $itcMaster->org_email = $itcLicenseData->applicant_email;

                // license info
                $itcMaster->license_no = $license_no;
                $itcMaster->license_issue_date = $license_issue_date;
                $itcMaster->expiry_date = $license_expire_date;

                $itcMaster->org_district = $itcLicenseData->applicant_district;
                $itcMaster->org_upazila = $itcLicenseData->applicant_thana;
                $itcMaster->org_address = $itcLicenseData->applicant_address;
                $itcMaster->org_website = $itcLicenseData->applicant_website;
                $itcMaster->status = 1;
                $itcMaster->created_at = \Carbon\Carbon::now();
                $itcMaster->save();


                // license info
                $itcLicenseData->license_no = $license_no;
                $itcLicenseData->license_issue_date = $license_issue_date;
                $itcLicenseData->expiry_date = $license_expire_date;
                $itcLicenseData->save();
                break;
            case 80: // SS License Amend
                    $LicenseData = \App\Modules\REUSELicenseIssue\Models\SS\amendment\SSLicenseAmendment::find($app_id);
                    // update license expiry date
                    $LicenseRenewData = \App\Modules\REUSELicenseIssue\Models\SS\amendment\SSLicenseAmendment::Join('ss_license_master as master', 'master.license_no', '=', 'ss_license_amendment.license_no')
                        ->where(['ss_license_amendment.id' => $app_id])
                        ->first([
                            'master.*',
                            'ss_license_amendment.tracking_no'
                        ]);
                    if (empty($LicenseRenewData)) {
                        return false;
                    }
                    $dd = SSLicenseMaster::where('id', $LicenseRenewData->id)->update([
                        'amendment_tracking_no' => $LicenseRenewData->tracking_no
                    ]);
                    break;
            case 62: // SCS License Issue
                //============================
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'scs_license_issue', '001', '50');

                $tvasLicenseData = SCSLicenseIssue::find($app_id);

                $process_list = ProcessList::find($process_list_id);

                $scsMasterObj = SCSLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();


                if (!isset($scsMasterObj) && empty($scsMasterObj)) {
                    $scsMasterObj = new SCSLicenseMaster();
                }

                $scsMasterObj->company_id = $tvasLicenseData->company_id;
                $scsMasterObj->org_nm = $tvasLicenseData->company_name;
                $scsMasterObj->issue_tracking_no = $tvasLicenseData->tracking_no;
                $scsMasterObj->org_type = $tvasLicenseData->company_type;
                $scsMasterObj->org_mobile = $tvasLicenseData->applicant_mobile;
                $scsMasterObj->org_phone = $tvasLicenseData->applicant_telephone;
                $scsMasterObj->org_email = $tvasLicenseData->applicant_email;

                // license info
                // $scsMasterObj->license_no = $license_no;
                $scsMasterObj->license_no = $scsMasterObj->sharok_no;
                $scsMasterObj->license_issue_date = $license_issue_date;
                $scsMasterObj->expiry_date = $license_expire_date;

                $scsMasterObj->org_district = $tvasLicenseData->applicant_district;
                $scsMasterObj->org_upazila = $tvasLicenseData->applicant_thana;
                $scsMasterObj->org_address = $tvasLicenseData->applicant_address;
                $scsMasterObj->org_website = $tvasLicenseData->applicant_website;
                $scsMasterObj->status = 1;
                $scsMasterObj->created_at = \Carbon\Carbon::now();
                $scsMasterObj->save();

                // license info
                // $tvasLicenseData->license_no = $license_no;
                $tvasLicenseData->license_no = $scsMasterObj->sharok_no;
                $tvasLicenseData->license_issue_date = $license_issue_date;
                $tvasLicenseData->expiry_date = $license_expire_date;
                $tvasLicenseData->save();

                  // process list
                  $process_list->license_no = $scsMasterObj->sharok_no;
                  $process_list->save();


                break;
            case 63: // SCS License Issue
                    //============================

                    $license_issue_date = \Carbon\Carbon::now();
                    $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                    $license_no = generateLicenseNo($process_type_id, 'scs_license_renew', '001', '50');

                    $tvasLicenseData = SCSLicenseRenew::find($app_id);
                    $tvasMaster = new SCSLicenseMaster();
                    $tvasMaster->company_id = $tvasLicenseData->company_id;
                    $tvasMaster->org_nm = $tvasLicenseData->company_name;
                    $tvasMaster->issue_tracking_no = $tvasLicenseData->tracking_no;
                    $tvasMaster->org_type = $tvasLicenseData->company_type;
                    $tvasMaster->org_mobile = $tvasLicenseData->applicant_mobile;
                    $tvasMaster->org_phone = $tvasLicenseData->applicant_telephone;
                    $tvasMaster->org_email = $tvasLicenseData->applicant_email;

                    // license info
                    $tvasMaster->license_no = $license_no;
                    $tvasMaster->license_issue_date = $license_issue_date;
                    $tvasMaster->expiry_date = $license_expire_date;

                    $tvasMaster->org_district = $tvasLicenseData->applicant_district;
                    $tvasMaster->org_upazila = $tvasLicenseData->applicant_thana;
                    $tvasMaster->org_address = $tvasLicenseData->applicant_address;
                    $tvasMaster->org_website = $tvasLicenseData->applicant_website;
                    $tvasMaster->status = 1;
                    $tvasMaster->created_at = \Carbon\Carbon::now();
                    $tvasMaster->save();

                    // license info
                    $tvasLicenseData->license_no = $license_no;
                    $tvasLicenseData->issue_date = $license_issue_date;
                    $tvasLicenseData->expiry_date = $license_expire_date;
                    $tvasLicenseData->save();
                    break;

             case 64: // SCS License Ammendment
                        //============================

                        $license_issue_date = \Carbon\Carbon::now();
                        $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                        $license_no = generateLicenseNo($process_type_id, 'scs_license_amendment', '001', '50');

                        $tvasLicenseData = SCSLicenseAmendment::find($app_id);
                        $tvasMaster = new SCSLicenseMaster();
                        $tvasMaster->company_id = $tvasLicenseData->company_id;
                        $tvasMaster->org_nm = $tvasLicenseData->org_nm;
                        $tvasMaster->issue_tracking_no = $tvasLicenseData->tracking_no;
                        $tvasMaster->org_type = $tvasLicenseData->org_type;
                        $tvasMaster->org_mobile = $tvasLicenseData->applicant_mobile;
                        $tvasMaster->org_phone = $tvasLicenseData->applicant_telephone;
                        $tvasMaster->org_email = $tvasLicenseData->applicant_email;

                        // license info
                        $tvasMaster->license_no = $license_no;
                        $tvasMaster->license_issue_date = $license_issue_date;
                        $tvasMaster->expiry_date = $license_expire_date;

                        $tvasMaster->org_district = $tvasLicenseData->applicant_district;
                        $tvasMaster->org_upazila = $tvasLicenseData->applicant_thana;
                        $tvasMaster->org_address = $tvasLicenseData->applicant_address;
                        $tvasMaster->org_website = $tvasLicenseData->applicant_website;
                        $tvasMaster->status = 1;
                        $tvasMaster->created_at = \Carbon\Carbon::now();
                        $tvasMaster->save();

                        // license info
                        $tvasLicenseData->license_no = $license_no;
                        $tvasLicenseData->license_issue_date = $license_issue_date;
                        $tvasLicenseData->expiry_date = $license_expire_date;
                        $tvasLicenseData->save();
                        break;
             case 65: // SCS License surrender
                            //============================

                            $licenseData = SCSLicenseSurrender::Join('scs_license_master as master', 'master.license_no', '=', 'scs_license_surrender.license_no')
                            ->where(['scs_license_surrender.id' => $app_id])
                            ->first([
                                'master.*',
                                'scs_license_surrender.tracking_no'
                            ]);
                        if (empty($licenseData)) {
                            return false;
                        }
                        SCSLicenseMaster::where('id', $licenseData->id)->update([
                            'cancellation_tracking_no' => $licenseData->tracking_no,
                            'status' => 0,
                        ]);
                        break;
             case 69: // TC License surrender
                            //============================

                            $licenseData = TCLicenseSurrender::Join('tc_license_master as master', 'master.license_no', '=', 'tc_license_cancellation.license_no')
                            ->where(['tc_license_cancellation.id' => $app_id])
                            ->first([
                                'master.*',
                                'tc_license_cancellation.tracking_no'
                            ]);
                        if (empty($licenseData)) {
                            return false;
                        }
                        TCLicenseMaster::where('id', $licenseData->id)->update([
                            'cancellation_tracking_no' => $licenseData->tracking_no,
                            'status' => 0,
                        ]);
                        break;
            case 50: // NTTN License Issue
                if ($processInfo->status_id === 5) {
                    break;
                }
                //============================
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = \Carbon\Carbon::now()->add(15, 'years');
                $license_expire_date = $license_expire_date->subDay()->format('Y-m-d h:i:sa');
                $nttnLicenseIssue = NTTNLicenseIssue::find($app_id);
                $nttnlicense_no = generateLicenseNo($process_type_id, 'nttn_license_issue', '100', '44');
                $nttnLicenseMaster = new NTTNLicenseMaster();

                // ISP license master data isnert
                $nttnLicenseMaster->issue_tracking_no = $nttnLicenseIssue->tracking_no;
                $nttnLicenseMaster->company_id = $nttnLicenseIssue->company_id;
                $nttnLicenseMaster->org_nm = $nttnLicenseIssue->org_nm;
                $nttnLicenseMaster->org_type = $nttnLicenseIssue->org_type;
                $nttnLicenseMaster->org_mobile = $nttnLicenseIssue->org_mobile;
                $nttnLicenseMaster->org_phone = $nttnLicenseIssue->org_phone;
                $nttnLicenseMaster->org_email = $nttnLicenseIssue->org_email;
                $nttnLicenseMaster->org_district = $nttnLicenseIssue->org_district;
                $nttnLicenseMaster->org_upazila = $nttnLicenseIssue->org_upazila;
                $nttnLicenseMaster->org_address = $nttnLicenseIssue->org_address;
                $nttnLicenseMaster->org_website = $nttnLicenseIssue->org_website;
                $nttnLicenseMaster->status = 1;
                $nttnLicenseMaster->created_at = Carbon::now();

                // license info
                $nttnLicenseMaster->license_no = $nttnlicense_no;
                $nttnLicenseMaster->license_issue_date = $license_issue_date;
                $nttnLicenseMaster->expiry_date = $license_expire_date;

                $nttnLicenseMaster->save();

                // license info
                $nttnLicenseIssue->license_no = $nttnlicense_no;
                $nttnLicenseIssue->license_issue_date = $license_issue_date;
                $nttnLicenseIssue->expiry_date = $license_expire_date;
                $nttnLicenseIssue->save();

                break;
            case 51:
                $nttnLicenseIssue = NTTNLicenseRenew::find($app_id);
                if (empty($nttnLicenseIssue->issue_tracking_no)) {
                    $nttnLicenseMaster = new NTTNLicenseMaster();

                    // NTTN license master data isnert
                    $nttnLicenseMaster->renew_tracking_no = $nttnLicenseIssue->tracking_no;
                    $nttnLicenseMaster->company_id = $nttnLicenseIssue->company_id;
                    $nttnLicenseMaster->org_nm = $nttnLicenseIssue->org_nm;
                    $nttnLicenseMaster->org_type = $nttnLicenseIssue->org_type;
                    $nttnLicenseMaster->org_mobile = $nttnLicenseIssue->org_mobile;
                    $nttnLicenseMaster->org_phone = $nttnLicenseIssue->org_phone;
                    $nttnLicenseMaster->org_email = $nttnLicenseIssue->org_email;
                    $nttnLicenseMaster->org_district = $nttnLicenseIssue->org_district;
                    $nttnLicenseMaster->org_upazila = $nttnLicenseIssue->org_upazila;
                    $nttnLicenseMaster->org_address = $nttnLicenseIssue->org_address;
                    $nttnLicenseMaster->org_website = $nttnLicenseIssue->org_website;
                    $nttnLicenseMaster->status = 1;
                    $nttnLicenseMaster->created_at = Carbon::now();

                    // license info
                    $nttnLicenseMaster->license_no = $nttnLicenseIssue->license_no;
                    $nttnLicenseMaster->license_issue_date = $nttnLicenseIssue->issue_date;
                    $nttnLicenseMaster->expiry_date = $nttnLicenseIssue->expiry_date;

                    $nttnLicenseMaster->save();
                } else {
                    // update license expiry date
                    $NTTNLicenseRenewData = NTTNLicenseRenew::Join('nttn_license_master as master', 'master.issue_tracking_no', '=', 'nttn_license_renew.issue_tracking_no')
                        ->where(['nttn_license_renew.id' => $app_id])
                        ->first([
                            'master.expiry_date',
                            'master.id as master_id',
                            'nttn_license_renew.tracking_no as renew_tracking_no'
                        ]);
                    if (empty($NTTNLicenseRenewData)) {
                        return false;
                    }

                    $new_exp_date = date('Y-m-d', strtotime($NTTNLicenseRenewData->expiry_date . '+3 years'));
                    NTTNLicenseRenew::find($app_id)
                        ->update([
                            'expiry_date' => $new_exp_date,
                        ]);

                    NTTNLicenseMaster::where('id', $NTTNLicenseRenewData->master_id)->update([
                        'expiry_date' => $new_exp_date,
                        'renew_tracking_no' => $NTTNLicenseRenewData->renew_tracking_no,
                    ]);
                }
                break;
            case 52: // NTTN Amendment

                $LicenseData = NTTNLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = NTTNLicenseAmendment::Join('nttn_license_master as master', 'master.license_no', '=', 'nttn_license_amendment.license_no')
                    ->where(['nttn_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'nttn_license_amendment.tracking_no'
                    ]);
                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = NTTNLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            case 53: // NTTN License Surrender
                $licenseData = NTTNLicenseSurrender::Join('nttn_license_master as master', 'master.license_no', '=', 'nttn_license_surrender.license_no')
                    ->where(['nttn_license_surrender.id' => $app_id])
                    ->first([
                        'master.*',
                        'nttn_license_surrender.tracking_no'
                    ]);
                if (empty($licenseData)) {
                    return false;
                }
                NTTNLicenseMaster::where('id', $licenseData->id)->update([
                    'cancellation_tracking_no' => $licenseData->tracking_no,
                    'status' => 0,
                ]);
                break;
            case 66:
                if (in_array($processInfo->status_id, [5, 15])) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'tc_license_issue', '001', '50');

                $tcLicenseData = TCLicenseIssue::find($app_id);

                $tcMaster = new TCLicenseMaster();

                $process_list = ProcessList::find($process_list_id);

                $tcMaster = TCLicenseMaster::where([
                    'issue_tracking_no' => $processInfo->tracking_no
                ])->latest()->first();

                if (!isset($tcMaster) && empty($tcMaster)) {
                    $tcMaster = new TCLicenseMaster();
                }

                $tcMaster->company_id = $tcLicenseData->company_id;
                $tcMaster->org_nm = $tcLicenseData->company_name;
                $tcMaster->issue_tracking_no = $tcLicenseData->tracking_no;
                $tcMaster->org_type = $tcLicenseData->company_type;
                $tcMaster->org_mobile = $tcLicenseData->applicant_mobile;
                $tcMaster->org_phone = $tcLicenseData->applicant_telephone;
                $tcMaster->org_email = $tcLicenseData->applicant_email;

                // license info
//                $tcMaster->license_no = $license_no;
                $tcMaster->license_no = $tcMaster->sharok_no;
                $tcMaster->license_issue_date = $license_issue_date;
                $tcMaster->expiry_date = $license_expire_date;

                $tcMaster->org_district = $tcLicenseData->applicant_district;
                $tcMaster->org_upazila = $tcLicenseData->applicant_thana;
                $tcMaster->org_address = $tcLicenseData->applicant_address;
                $tcMaster->org_website = $tcLicenseData->applicant_website;
                $tcMaster->status = 1;
                $tcMaster->created_at = \Carbon\Carbon::now();
                $tcMaster->save();

                // license info
//                $tcLicenseData->license_no = $license_no;
                $tcLicenseData->license_no = $tcMaster->sharok_no;
                $tcLicenseData->license_issue_date = $license_issue_date;
                $tcLicenseData->expiry_date = $license_expire_date;
                $tcLicenseData->save();

                // process list
                $process_list->license_no = $tcMaster->sharok_no;
                $process_list->save();

                break;

            case 67:
                if ($processInfo->status_id === 5) {
                    break;
                }
                $license_issue_date = \Carbon\Carbon::now();
                $license_expire_date = date('Y-m-d h:i:sa', strtotime(\Carbon\Carbon::now() . '+5 years'));
                $license_no = generateLicenseNo($process_type_id, 'tc_license_renew', '001', '50');

                $tcLicenseData = TCLicenseRenew::find($app_id);

                $tcMaster = new TCLicenseMaster();
                $tcMaster->company_id = $tcLicenseData->company_id;
                $tcMaster->org_nm = $tcLicenseData->company_name;
                $tcMaster->issue_tracking_no = $tcLicenseData->tracking_no;
                $tcMaster->org_type = $tcLicenseData->company_type;
                $tcMaster->org_mobile = $tcLicenseData->applicant_mobile;
                $tcMaster->org_phone = $tcLicenseData->applicant_telephone;
                $tcMaster->org_email = $tcLicenseData->applicant_email;

                // license info
                $tcMaster->license_no = $license_no;
                $tcMaster->license_issue_date = $license_issue_date;
                $tcMaster->expiry_date = $license_expire_date;

                $tcMaster->org_district = $tcLicenseData->applicant_district;
                $tcMaster->org_upazila = $tcLicenseData->applicant_thana;
                $tcMaster->org_address = $tcLicenseData->applicant_address;
                $tcMaster->org_website = $tcLicenseData->applicant_website;
                $tcMaster->status = 1;
                $tcMaster->created_at = \Carbon\Carbon::now();
                $tcMaster->save();


                // license info
                $tcLicenseData->license_no = $license_no;
                $tcLicenseData->license_issue_date = $license_issue_date;
                $tcLicenseData->expiry_date = $license_expire_date;
                $tcLicenseData->save();
                break;
            case 39:
                $LicenseData = \App\Modules\REUSELicenseIssue\Models\IGW\amendment\IGWLicenseAmendment::find($app_id);
                // update license expiry date
                $LicenseRenewData = \App\Modules\REUSELicenseIssue\Models\IGW\amendment\IGWLicenseAmendment::Join('igw_license_master as master', 'master.license_no', '=', 'igw_license_amendment.license_no')
                    ->where(['igw_license_amendment.id' => $app_id])
                    ->first([
                        'master.*',
                        'igw_license_amendment.tracking_no'
                    ]);
                if (empty($LicenseRenewData)) {
                    return false;
                }

                $dd = \App\Modules\IGWLicenseIssue\Models\IGWLicenseMaster::where('id', $LicenseRenewData->id)->update([
                    'amendment_tracking_no' => $LicenseRenewData->tracking_no
                ]);
                break;
            default:
                return false;
        }
        if (in_array($process_type_id, [5, 6]) && $processInfo->status_id == 25) {

            $pdf_link_gen_params = [
                "process_type_id" => $process_type_id,
                "app_id" => $app_id,
            //    "process_list_id" => $processInfo->id,
                "processInfo" => $processInfo,
                "certificate_name" => $certificate_name,
                "approver_desk_id" => $approver_desk_id,
                "certificate_type" => $certificate_type,
            ];
            PDFLinkForCertificate($pdf_link_gen_params, 1);
        }

        $url_store = PdfPrintRequestQueue::firstOrNew([
            'process_type_id' => $process_type_id,
            'app_id' => $app_id,
            'pdf_diff' => pdf_diff($processInfo->status_id)
        ]);
        $pdf_info = PdfServiceInfo::where('certificate_name', $certificate_name)->first([
            'pdf_server_url',
            'reg_key',
            'pdf_type',
            'certificate_name',
            'table_name',
            'field_name'
        ]);
        if (empty($pdf_info)) {
            return false;
        }

        $tableName = $pdf_info->table_name;
        $fieldName = $pdf_info->field_name;

        $url_store->process_type_id = $process_type_id;
        $url_store->app_id = $app_id;
       // $url_store->process_list_id = $processInfo->id;
        $url_store->pdf_server_url = $pdf_info->pdf_server_url;
        $url_store->reg_key = $pdf_info->reg_key;
        $url_store->pdf_type = $pdf_info->pdf_type;
        $url_store->certificate_name = $pdf_info->certificate_name;
        $url_store->prepared_json = 0;
        $url_store->table_name = $tableName;
        $url_store->field_name = $fieldName;
        $url_store->url_requests = '';
        //        $url_store->status = 0;
        $url_store->job_sending_status = 0;
        $url_store->no_of_try_job_sending = 0;
        $url_store->job_receiving_status = 0;
        $url_store->no_of_try_job_receving = 0;
        if ($certificate_type == 'generate') {
            if($flag == 0){
            $url_store->signatory = Auth::user()->id;
            }else{
                $nothiUserId = Users::where('id','2014')->first()->id;
                $url_store->signatory = $nothiUserId;
            }

            if( isset($old_cancel) && $old_cancel=='old-cancel'){
                $signature_store_status = storeSignatureQRCode(4, $app_id, 0, $approver_desk_id, 'final', $processInfo->status_id,$old_cancel);
            }else{
                $signature_store_status = storeSignatureQRCode($process_type_id, $app_id, 0, $approver_desk_id, 'final', $processInfo->status_id, $pdf_gen_number = 0,$certificate_name=0,$old_cancel=0, $flag);
            }
            // Store approve information
            if ($signature_store_status === false) {
                return false;
            }
        }
        $url_store->pdf_diff = pdf_diff($processInfo->status_id);
        $url_store->updated_at = date('Y-m-d H:i:s');

        $url_store->save();
         return true;
    } catch (Exception $e) {
        Log::error("Exception occurred at line {$e->getLine()}: {$e->getMessage()}");

        return false;
    }
}

function PDFLinkForCertificate($pdf_link_gen_params, $pdf_gen_number = 0)
{
    $url_store = PdfPrintRequestQueue::firstOrNew([
        'process_type_id' => $pdf_link_gen_params["process_type_id"],
        'app_id' => $pdf_link_gen_params["app_id"],
        'pdf_diff' => pdf_diff($pdf_link_gen_params["processInfo"]->status_id, $pdf_gen_number)
    ]);

    $pdf_info = PdfServiceInfo::where('certificate_name', $pdf_link_gen_params["certificate_name"])->first([
        'pdf_server_url',
        'reg_key',
        'pdf_type',
        'certificate_name',
        'table_name',
        'field_name'
    ]);

    if (empty($pdf_info)) {
        return false;
    }
    $tableName = $pdf_info->table_name;
    $fieldName = $pdf_info->field_name;

    $url_store->process_type_id = $pdf_link_gen_params["process_type_id"];
    $url_store->app_id = $pdf_link_gen_params["app_id"];
  //  $url_store->process_list_id = $pdf_link_gen_params["process_list_id"];
    $url_store->pdf_server_url = $pdf_info->pdf_server_url;
    $url_store->reg_key = $pdf_info->reg_key;
    $url_store->pdf_type = $pdf_info->pdf_type;
    $url_store->certificate_name = $pdf_info->certificate_name;
    $url_store->prepared_json = 0;
    $url_store->table_name = $tableName;
    $url_store->field_name = $fieldName;
    $url_store->url_requests = '';
    //        $url_store->status = 0;
    $url_store->job_sending_status = 0;
    $url_store->no_of_try_job_sending = 0;
    $url_store->job_receiving_status = 0;
    $url_store->no_of_try_job_receving = 0;

    if ($pdf_link_gen_params["certificate_type"] == 'generate') {
        $url_store->signatory = Auth::user()->id;

        // Store approve information
        $signature_store_status = storeSignatureQRCode($pdf_link_gen_params["process_type_id"], $pdf_link_gen_params["app_id"], 0, $pdf_link_gen_params["approver_desk_id"], 'final', $pdf_link_gen_params["processInfo"]->status_id, $pdf_gen_number);
        if ($signature_store_status === false) {
            return false;
        }
    }
    $url_store->pdf_diff = pdf_diff($pdf_link_gen_params["processInfo"]->status_id, $pdf_gen_number);
    $url_store->updated_at = date('Y-m-d H:i:s');
    $url_store->save();

    if ($pdf_gen_number == 1) {
        $certificateControllerInstance = new CertificateGenerationController();
        $generateCertificateStatus = $certificateControllerInstance->generateCertificate();
    }
}

/**
 * @param $process_type_id
 * @param $app_id
 * @param int $user_id
 * @param $approver_desk_id
 * @param string $signature_type
 *
 * @return bool
 */
function storeSignatureQRCode($process_type_id = 0, $app_id = 0, $user_id = 0, $approver_desk_id = 0, $signature_type = 'final', $status_id = 0, $pdf_gen_number = 0,$certificate_name=0,$old_cancel=0, $flag = 0): bool
{
    $pdf_type_value = \App\Modules\Settings\Models\PdfSignatureQrcode::where([
        'app_id' => $app_id,
        'process_type_id' => $process_type_id
    ])->where('pdf_type', '>', 0)->latest()->value('pdf_type');

    if(isset($old_cancel) && $old_cancel=='old-cancel'){
        $pdf_type_value += 1;
        $data = \App\Modules\Settings\Models\PdfSignatureQrcode::where([
            'app_id' => $app_id,
            'process_type_id' => $process_type_id
        ])->where('pdf_type', '>', 0)->latest()->first();
        if(isset($data) && $data){
            $data->pdf_type = $pdf_type_value;
            $data->status_id = $status_id;
            $data->save();
        }

        return true;
    }

    if($process_type_id==1 && $status_id==25){
        $pdf_type_value += 1;
        $data = \App\Modules\Settings\Models\PdfSignatureQrcode::where([
            'app_id' => $app_id,
            'process_type_id' => $process_type_id
        ])->where('pdf_type', '>', 0)->latest()->first();
        if(isset($data) && $data){
            $data->pdf_type = $pdf_type_value;
            $data->status_id = $status_id;
            $data->save();
        }

        return true;
    }

    //Only for shortfall data and request payment update
    if (!empty($pdf_type_value) && in_array($status_id, [5, 15, 60]) || !empty($pdf_type_value) && $pdf_gen_number == 1) {

        $pdf_type_value += 1;
        $data = \App\Modules\Settings\Models\PdfSignatureQrcode::where([
            'app_id' => $app_id,
            'process_type_id' => $process_type_id
        ])->where('pdf_type', '>', 0)->latest()->first();
        $data->pdf_type = $pdf_type_value;
        $data->status_id = $status_id;
        $data->save();
        return true;
    }

    //If there is no data for shortfall and status id is 5 or status id  25
    if ((empty($pdf_type_value) && in_array($status_id, [5, 15, 60])) || $status_id == 25 || $status_id == 65 || $pdf_gen_number == 1) {
        if(empty($pdf_type_value)){
            $pdf_type_value = 0;
        }
        // $pdf_type_value += 1; // mark
        $pdf_sign = \App\Modules\Settings\Models\PdfSignatureQrcode::orderBy('id', 'desc')
                ->firstOrNew([
                    'app_id' => $app_id,
                    'pdf_type' => $pdf_type_value,
                    'process_type_id' => $process_type_id
                ]);
        //$pdf_sign = new \App\Modules\Settings\Models\PdfSignatureQrcode();
        $pdf_sign->signature_type = $signature_type;
        $pdf_sign->app_id = $app_id;
        $pdf_sign->process_type_id = $process_type_id;
        $pdf_sign->status_id = $status_id;
        if (in_array($status_id, [5, 15, 60]) || $pdf_gen_number == 1 ) {
            $pdf_sign->pdf_type = 1;
        }

//            $pdf_sign->pdf_type = 1;
        if ($user_id == 0) {
//            if (empty(Auth::user()->signature_encode)) {
//                return false;
//            }
            if($flag == 0){
                $pdf_sign->signer_user_id = Auth::user()->id;
                $pdf_sign->signer_desk = CommonFunction::getDeskName($approver_desk_id);

                $pdf_sign->signer_name = CommonFunction::getUserFullName();
                $pdf_sign->signer_designation = Auth::user()->designation;
                $pdf_sign->signer_mobile = Auth::user()->user_mobile;
                $pdf_sign->signer_email = Auth::user()->user_email;
                $pdf_sign->signature_encode = Auth::user()->signature_encode;
            }else{
                $pdf_sign->signer_user_id = '';
                $pdf_sign->signer_desk = '';

                $pdf_sign->signer_name = '';
                $pdf_sign->signer_designation = '';
                $pdf_sign->signer_mobile = '';
                $pdf_sign->signer_email = '';
                $pdf_sign->signature_encode = '';
            }
        } else {
            $user_info = Users::where('id', $user_id)->first([
                DB::raw("CONCAT(user_first_name,' ',user_middle_name, ' ',user_last_name) as user_full_name"),
                'designation',
                'user_phone',
                'user_mobile',
                'user_email',
                'signature_encode',
            ]);

            if (empty($user_info->signature_encode)) {
                return false;
            }

            $pdf_sign->signer_user_id = $user_id;
            $pdf_sign->signer_desk = CommonFunction::getDeskName($approver_desk_id);
            $pdf_sign->signer_name = $user_info->user_full_name;
            $pdf_sign->signer_designation = $user_info->designation;
            $pdf_sign->signer_mobile = $user_info->user_mobile;
            $pdf_sign->signer_email = $user_info->user_email;
            $pdf_sign->signature_encode = $user_info->signature_encode;
        }
        $pdf_sign->save();
    }
    return true;
}


function cancellationRequest($process_type_id)
{

    if ($process_type_id == 3) {
        $industryInfo = IndRRCommonPool::where('company_id', Auth::user()->working_company_id)
            ->where('ind_can_tracking_no', null)
            ->get(['id', 'tracking_no', 'project_nm']);

        return $industryInfo;
    }
}

function generatePDFLinkAndSendSMSEmailForShortfall($userMobile, $appInfo, $receiverInformation, $serviceName, $processInfo, $approver_desk_id, $masterTableName, $certificateName, $flag = 0)
{
    //TODO:: call certificate generation function

    if($flag == 0){
    certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, $masterTableName, $certificateName, $approver_desk_id,);
    }else{
    certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, $masterTableName, $certificateName, $approver_desk_id, $certificate_type = 'generate', $process_list_id = '',$old_cancel=0, $flag);
    }
    //TODO:: run cron job for generate certificate
    $certificateControllerInstance = new CertificateGenerationController();
    $generateCertificateStatus = $certificateControllerInstance->generateCertificate();
    $pdfLink = PdfPrintRequestQueue::where('process_type_id', '=', $processInfo->process_type_id)->where('app_id', '=', $processInfo->ref_id)->where('pdf_diff', '=', 1)->value('certificate_link');
    if($flag == 0) {
        CommonFunction::sendEmailSMS('APP_SHORTFALL', $appInfo, $receiverInformation, $pdfLink);
    //SMS for Shortfall
//                $userMobile = $user_mobile;
        $loginControllerInstance = new LoginController();
        $loginControllerInstance->SendSmsService('APP_SHORTFALL', ['{$serviceName}' => $serviceName, ' {$trackingNumber}' => $appInfo['tracking_no']], $userMobile);

        //Send mail to Director General, Director, and Chairman
        $officersInfo = ConfigSetting::whereIn(
            'label', ['chairman','office_copy']
        )->where([
            'status' => 1
        ])->get()->toArray();

        $receiverInformationforDGDC = [];
        foreach ($officersInfo as $key => $officerInfo) {
            $receiverInformation = [
                'user_mobile' => '',
                'user_email' => $officerInfo['value']
            ];
            array_push($receiverInformationforDGDC, $receiverInformation);
        }

        CommonFunction::sendEmailSMS('APP_SHORTFALL', $appInfo, $receiverInformationforDGDC, $pdfLink);
    }


}


function updateShortfallReason($shortFallReason, $processInfo, $modelName)
{
    $modelName::where([
        'id' => $processInfo->ref_id
    ])->update([
        'shortfall_reason' => $shortFallReason
    ]);
}


function generatePDFLinkForRequestPayment($processInfo, $approver_desk_id, $masterTableName, $certificateName, $flag=0)
{

    //TODO:: call certificate generation function
    if($flag == 1){
        certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, $masterTableName, $certificateName, $approver_desk_id, $certificate_type = 'generate', $process_list_id = '',$old_cancel=0, $flag);
    }else{
        certificateGenerationRequest($processInfo->ref_id, $processInfo->process_type_id, $masterTableName, $certificateName, $approver_desk_id);
    }
    //TODO:: run cron job for generate certificate

    $certificateControllerInstance = new CertificateGenerationController();
    $generateCertificateStatus = $certificateControllerInstance->generateCertificate();
    $pdfLink = PdfPrintRequestQueue::where('process_type_id', '=', $processInfo->process_type_id)->where('app_id', '=', $processInfo->ref_id)->where('pdf_diff', '=', 2)->value('certificate_link');
    return $pdfLink;
}

function updateApprovalMemoMinistry($requestData, $processInfo, $modelName)
{
    $data = $modelName::where('id', $processInfo->ref_id)->first();
    $data->approval_memo_no_ministry = $requestData['approval_memo_no_ministry'];
    $data->approval_date_ministry = date("Y-m-d", strtotime($requestData['approval_date_ministry']));
    $data->save();
}

function updateSarokNo($processInfo, $modelName, $tableName, $file_no = '', $service_category_id = '', $license_type_id = '', $license_type_col = '')
{
    $data = $modelName::where('id', $processInfo->ref_id)->first();
    if (!empty($data->sharok_no)) {
        $sharok_no = $data->sharok_no;
        $exp_sharok = explode(".", $sharok_no);
        $inc = intval($exp_sharok[7]);
        $data->sharok_no = substr($sharok_no, 0, -3) . str_pad(++$inc, 3, '0', STR_PAD_LEFT);
    } else {
        $data->sharok_no = generateLicenseNo($processInfo->process_type_id, $tableName, $file_no, $service_category_id, $license_type_id, $license_type_col);
    }
    $data->save();
}

function updateSarokNoV2($processInfo, $masterModel, $master_column_name, $model, $tableName, $file_no = '', $service_category_id = '', $license_type_id = '', $license_type_col = '')
{
    $data = $masterModel::where($master_column_name, $processInfo->tracking_no)->first();
    if (in_array($processInfo->process_type_id, [1, 2, 3, 4])) {
        if (!isset($data) && empty($data)) {
            $data = new ISPLicenseMaster();
        }
    }
    elseif (in_array($processInfo->process_type_id, [5, 6, 7, 8])) {
        if (!isset($data) && empty($data)) {
            $data = new CallCenterMaster();
        }
    }
    elseif (in_array($processInfo->process_type_id, [54,55,56,57])) {
        if (!isset($data) && empty($data)) {
            $data = new ITCLicenseMaster();
        }
    }
    elseif (in_array($processInfo->process_type_id, [21, 22, 23, 24])) {
        if (!isset($data) && empty($data)) {
            $data = new IPTSPLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [78,79,80,81])){
        if (!isset($data) && empty($data)) {
            $data = new SSLicenseMaster();
        }
    }
    elseif (in_array($processInfo->process_type_id, [29,30, 83, 84])) {
        if (!isset($data) && empty($data)) {
            $data = new VTSLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [13,14,15,16])){
        if (!isset($data) && empty($data)) {
            $data = new VSATLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [74,75,76,77])){
        if (!isset($data) && empty($data)) {
            $data = new BWALicenseMaster(); // need to implement your mastet table
        }
    }
    elseif(in_array($processInfo->process_type_id, [17,18,19,20])){
        if (!isset($data) && empty($data)) {
            $data = new IIGLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [66,67,68,69])){
        if (!isset($data) && empty($data)) {
            $data = new TCLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [58,59,60,61])){
        if (!isset($data) && empty($data)) {
            $data = new MNOLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [62,63,64,65])){
        if (!isset($data) && empty($data)) {
            $data = new SCSLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [37,38,39,40])){
        if (!isset($data) && empty($data)) {
            $data = new IGWLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [70,71,72,73])){
        if (!isset($data) && empty($data)) {
            $data = new MNPLicenseMaster();
        }
    }
    elseif(in_array($processInfo->process_type_id, [33,34,35,36])){
        if (!isset($data) && empty($data)) {
            $data = new ICXLicenseMaster();
        }
    }

    $table_data = $model::where('id', $processInfo->ref_id)->first();
    if (isset($data->sharok_no) && !empty($data->sharok_no)) {
        $exp_sharok = explode(".", $data->sharok_no);
        $inc = intval($exp_sharok[7]);
        $sharok_no = substr($data->sharok_no, 0, -3) . str_pad(++$inc, 3, '0', STR_PAD_LEFT);
        $data->sharok_no = $sharok_no;
        $table_data->sharok_no = $sharok_no;
    } else {

        $generated_sharok_no = generateLicenseNo($processInfo->process_type_id, $tableName, $file_no, $service_category_id, $license_type_id, $license_type_col);
        $data->sharok_no = $generated_sharok_no;
        $data[$master_column_name] = $processInfo->tracking_no; //based on process type
        $table_data->sharok_no = $generated_sharok_no;
    }

    $data->save();
    $table_data->save();
}


function requestForPayment($user_mobile, $processInfo, $appInfo, $receiverInformation, $serviceName, $approver_desk_id = 0, $masterTableName = '', $certificateName = '', $payment_step_id = 0, $with_1st_annual_fee = 0, $flag = 0)
{
    $amountArray = getPaymentDetails($processInfo, $payment_step_id, $with_1st_annual_fee);
    $appInfo['total_amount'] = $amountArray['total_amount'];
    //SMS send for request for payment
    if ($flag == 0){
    $userMobile = $user_mobile;
    $loginControllerInstance = new LoginController();
    $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $serviceName, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $appInfo['total_amount']], $userMobile);
    $pdfLink = generatePDFLinkForRequestPayment($processInfo, $approver_desk_id, $masterTableName, $certificateName);
    }else{
        $pdfLink = generatePDFLinkForRequestPayment($processInfo, $approver_desk_id, $masterTableName, $certificateName, $flag);

    }
    //Send email for request for payment.
    if ($flag == 0) {
        CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation, $pdfLink);
    }
    //Send mail to Director General, Director, and Chairman
    $officersInfo = ConfigSetting::whereIn(
        'label', ['director', 'chairman']
    )->where([
        'status' => 1
    ])->get()->toArray();

    $receiverInformationforDGDC = [];
    foreach ($officersInfo as $key => $officerInfo) {
        $receiverInformation = [
            'user_mobile' => '',
            'user_email' => $officerInfo['value']
        ];
        array_push($receiverInformationforDGDC, $receiverInformation);
    }
    if ($flag == 0) {
        CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformationforDGDC, $pdfLink);
    }
}

function requestForPaymentV3($user_mobile, $processInfo, $appInfo, $receiverInformation, $serviceName, $approver_desk_id = 0, $masterTableName = '', $certificateName = '', $payment_step_id = 0, $with_1st_annual_fee = 0)
{
    $amountArray = getPaymentDetails($processInfo, $payment_step_id, $with_1st_annual_fee);
    $appInfo['total_amount'] = $amountArray['total_amount'];

    //SMS send for request for payment
    $userMobile = $user_mobile;
    $loginControllerInstance = new LoginController();
    $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $serviceName, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $appInfo['total_amount']], $userMobile);

    $pdfLink = generatePDFLinkForRequestPayment($processInfo, $approver_desk_id, $masterTableName, $certificateName);
    //Send email for request for payment.
    CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation, $pdfLink);
}

function getPaymentDetails($processInfo, $payment_step_id, $with_1st_annual_fee, $is_annual_fee=0)
{
    $process_type_id = $processInfo->process_type_id;
    $app_id = $processInfo->ref_id;
    $unfixedAmounts = [
        1 => 0, // Vendor-Service-Fee
        2 => 0, // Govt-Service-Fee
        3 => 0, // Govt. Application Fee
        4 => 0, // Vendor-Vat-Fee
        5 => 0, // Govt-Vat-Fee
        6 => 0, // Govt-Vendor-Vat-Fee
        7 => 0, // Govt-Annual-Fee
        8 => 0, // Govt-Delay-Fee
        9 => 0, // Govt-Annual-Vat-Fee
        10 => 0 // Govt-Delay-Vat-Fee
    ];

    switch ($process_type_id) {
        case 1:
            $license_type = ISPLicenseIssue::where('id', $app_id)->value('isp_license_type');
            $unfixedAmounts = (new ISPLicenseIssue())->unfixedAmountsForGovtServiceFee($license_type, $payment_step_id, $app_id, $process_type_id);
            break;
        case 2:
            $license_type = ISPLicenseRenew::where('id', $app_id)->value('isp_license_type');
            $unfixedAmounts = (new ISPLicenseRenew())->unfixedAmountsForGovtServiceFee($license_type, $payment_step_id, $app_id, $process_type_id);
            break;
    }

    // calculate paymenent
    $payment_config = PaymentConfiguration::leftJoin(
        'sp_payment_steps',
        'sp_payment_steps.id',
        '=',
        'sp_payment_configuration.payment_step_id'
    )->where([
        'sp_payment_configuration.process_type_id' => $process_type_id,
        'sp_payment_configuration.payment_step_id' => $payment_step_id,
        'sp_payment_configuration.status' => 1,
        'sp_payment_configuration.is_archive' => 0
    ])->first([
        'sp_payment_configuration.id'
    ]);

    $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
        ->where('status', 1)
        ->where('is_archive', 0)
        ->get([
            'id',
            'stakeholder_ac_no',
            'pay_amount',
            'purpose',
            'purpose_sbl',
            'fix_status',
            'distribution_type'
        ]);
    $payment_amounts = PaymentPanelController::unfixedAmountsForPayment($stakeDistribution, $unfixedAmounts, $payment_step_id, $process_type_id, $app_id);
    $paymenDetailsArray = [
        'main_amount' => 0,
        'vat_amount' => 0,
        'total_amount' => 0
    ];

    if (!$is_annual_fee) {
        if ($with_1st_annual_fee) {
            $paymenDetailsArray['main_amount'] = $payment_amounts['pay_amount_total'] - $payment_amounts['delay_fee'];
            $paymenDetailsArray['vat_amount'] = $payment_amounts['vat_on_pay_amount_total'] - $payment_amounts['delay_vat_fee'];
        } else {
            $paymenDetailsArray['main_amount'] = $payment_amounts['pay_amount_total'] - ($payment_amounts['delay_fee'] + $payment_amounts['annual_fee']);
            $paymenDetailsArray['vat_amount'] = $payment_amounts['vat_on_pay_amount_total'] - ($payment_amounts['delay_vat_fee'] + $payment_amounts['annual_vat_fee']);
        }
    }else{
        $paymenDetailsArray['main_amount'] = $payment_amounts['pay_amount_total'];
        $paymenDetailsArray['vat_amount'] = $payment_amounts['vat_on_pay_amount_total'];
    }

    $paymenDetailsArray['total_amount'] = $paymenDetailsArray['main_amount'] + $paymenDetailsArray['vat_amount'];
    return $paymenDetailsArray;
}

function requestForPaymentIPTSP($user_mobile, $processInfo, $modelName, $appInfo, $receiverInformation, $serviceName)
{
    $iptsp_license_data = $modelName::where([
        'id' => $processInfo->ref_id
    ])->select(['isptspli_type','isptspli_area_div'])->first();
    $isp_license_type = $iptsp_license_data->isptspli_type;
    $iptsp_division_id = $iptsp_license_data->isptspli_area_div;

//    //Amount calculation
    $unfixedAmountArray = (new $modelName())->unfixedAmountsForGovtServiceFee($isp_license_type, 2, $processInfo->ref_id, $processInfo->process_type_id, $iptsp_division_id);

    $totalAmount = 0;
    foreach ($unfixedAmountArray as $amount) {
        $totalAmount = $totalAmount + $amount;
    }
    $appInfo['total_amount'] = $totalAmount;

    //SMS send for request for payment
    $userMobile = $user_mobile;
    $loginControllerInstance = new LoginController();
    $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $serviceName, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $totalAmount], $userMobile);

    //Send email for request for payment.
    CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation);


    //Send mail to Director General, Director, and Chairman
    $officersInfo = ConfigSetting::whereIn(
        'label', ['director', 'chairman']
    )->where([
        'status' => 1
    ])->get()->toArray();

    $receiverInformationforDGDC = [];
    foreach ($officersInfo as $key => $officerInfo) {
        $receiverInformation = [
            'user_mobile' => '',
            'user_email' => $officerInfo['value']
        ];
        array_push($receiverInformationforDGDC, $receiverInformation);
    }

    CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformationforDGDC);


}

function requestForPaymentV2($user_mobile, $processInfo, $appInfo, $receiverInformation, $serviceName, $approver_desk_id = 0, $masterTableName = '', $certificateName = '', $payment_step_id = 0, $with_1st_annual_fee = 0, $flag = 0)
{
    $amountArray = getPaymentDetails($processInfo, $payment_step_id, $with_1st_annual_fee);
    $appInfo['total_amount'] = $amountArray['total_amount'];
    //SMS send for request for payment
    $userMobile = $user_mobile;
    $loginControllerInstance = new LoginController();
    if ($flag == 0){
        $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST', ['{$serviceName}' => $serviceName, '{$trackingNumber}' => $processInfo->tracking_no, '{$amount}' => $appInfo['total_amount']], $userMobile);
    }

    // pdf generation for payment request
    $pdfLink = generatePDFLinkForRequestPayment($processInfo, $approver_desk_id, $masterTableName, $certificateName);

    //Send email for request for payment.
    if($flag == 0){
        CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformation, $pdfLink);
    }

    //Send mail to Director General, Director, and Chairman
    $officersInfo = ConfigSetting::whereIn(
        'label', ['director', 'chairman', 'office_copy']
    )->where([
        'status' => 1
    ])->get()->toArray();

    $receiverInformationforDGDC = [];
    foreach ($officersInfo as $key => $officerInfo) {
        $receiverInformation = [
            'user_mobile' => '',
            'user_email' => $officerInfo['value']
        ];
        array_push($receiverInformationforDGDC, $receiverInformation);
    }
    if($flag == 0){
        CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST', $appInfo, $receiverInformationforDGDC, $pdfLink);
    }
}

function fixedPyamentCalculation($process_type_id, $payment_step_id)
{
    $payment_config = PaymentConfiguration::leftJoin(
        'sp_payment_steps',
        'sp_payment_steps.id',
        '=',
        'sp_payment_configuration.payment_step_id'
    )->where([
        'sp_payment_configuration.process_type_id' => $process_type_id,
        'sp_payment_configuration.payment_step_id' => $payment_step_id,
        'sp_payment_configuration.status' => 1,
        'sp_payment_configuration.is_archive' => 0
    ])->first([
        'sp_payment_configuration.id'
    ]);
    $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
        ->where('status', 1)
        ->where('is_archive', 0)
        ->get([
            'id',
            'pay_amount'
        ]);
    $total_amount = 0;
    foreach ($stakeDistribution as $payment) {
        $total_amount += $payment->pay_amount;
    }

    return $total_amount;
}

function approvedPayOrderVerification($processInfo, $payment_step_id)
{
    SonaliPayment::where([
        'app_id' => $processInfo->ref_id,
        'payment_step_id' => $payment_step_id,
        'process_type_id' => $processInfo->process_type_id,
        'payment_type' => 'pay_order'
    ])->update(['payment_status' => 1, 'is_pay_order_verified' => 1]);
}

function shortfallPayOrderVerification($requestData, $processInfo, $payment_step_id)
{
    if (isset($requestData['pay_order_verification'])) {
        $pay_order_status = $requestData['pay_order_verification'] === 'YES' ? 1 : 0;
        $pay_status = $pay_order_status ? 1 : -1;
        SonaliPayment::where([
            'app_id' => $processInfo->ref_id,
            'payment_step_id' => $payment_step_id,
            'process_type_id' => $processInfo->process_type_id,
            'payment_type' => 'pay_order'
        ])->update([
            'is_pay_order_verified' => $pay_order_status,
            'payment_status' => $pay_status
        ]);
    }
}

function generatePDFLinkAndSendSMSEmailForRequestForAnnualFeeOrBGFee($userMobile, $appInfo, $receiverInformation, $serviceName, $processInfo, $approver_desk_id, $masterTableName, $certificateName, $process_list_id, $status_id, $flag = 0)
{
    $pdf_link_gen_params = [
        "process_type_id" => $processInfo->process_type_id,
  //      "process_list_id" => $processInfo->process_list_id,
        "app_id" => $processInfo->ref_id,
        "processInfo" => $processInfo,
        "certificate_name" => $certificateName,
        "approver_desk_id" => $approver_desk_id,
        "certificate_type" => 'generate',
        "status_id" => $status_id,
    ];
    insertDataInPDFPrintRequestTableForAnnualFeeOrBG($pdf_link_gen_params, 1, $flag);

    //TODO:: run cron job for generate certificate
    $certificateControllerInstance = new CertificateGenerationController();
    $generateCertificateStatus = $certificateControllerInstance->generateCertificate();
    $pdfLink = PdfPrintRequestQueue::where('process_type_id', '=', $processInfo->process_type_id)->where('app_id', '=', $processInfo->ref_id)->where('pdf_diff', '=', 4)->value('certificate_link');

    //Payment Calculation for SMS or Email content
    $amountArray = getPaymentDetails($processInfo, 3, 0, 1);
    $pdfAmountArray = [
        "mainAmount" => $amountArray['main_amount'],
        "vatAmount" => $amountArray['vat_amount'],
        "totalAmount" => $amountArray['total_amount'] * 4,
    ];
    $paymentJson = ProcessType::where('id', $processInfo->process_type_id)->get('process_desk_status_json');
    $isp_license_type = '';
    if($processInfo->process_type_id == 1){
        $isp_license_type = ISPLicenseIssue::where('id', $processInfo->ref_id)->value('isp_license_type');
    }elseif($processInfo->process_type_id == 2){
        $isp_license_type = ISPLicenseRenew::where('id', $processInfo->ref_id)->value('isp_license_type');
    }

    $getPaymentJson = json_decode($paymentJson, true);
    $feesJson = json_decode($getPaymentJson[0]['process_desk_status_json'], true);
    $bg_object = $feesJson['bg_object'];
    if($isp_license_type == '1'){
        $pdfAmountArray['bank_guarantee_amount'] = $bg_object['nationwide'];
    } elseif($isp_license_type == '2'){
        $pdfAmountArray['bank_guarantee_amount'] = $bg_object['divisional'];
    } elseif($isp_license_type == '3'){
        $pdfAmountArray['bank_guarantee_amount'] = $bg_object['district'];
    } elseif($isp_license_type == '4'){
        $pdfAmountArray['bank_guarantee_amount'] = $bg_object['thana'];
    }
    $appInfo['pdfAmountArray'] = $pdfAmountArray;
    if($flag == 0){
        CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST_FOR_ANNUAl_OR_BG_FEE', $appInfo, $receiverInformation, $pdfLink);
        $loginControllerInstance = new LoginController();
        $loginControllerInstance->SendSmsService('APP_PAYMENT_REQUEST_FOR_ANNUAl_OR_BG_FEE', ['{$serviceName}' => $serviceName, ' {$trackingNumber}' => $appInfo['tracking_no'], '{$bgAmount}' => $pdfAmountArray["bank_guarantee_amount"], '{$totalAnnualAmount}' => $pdfAmountArray["totalAmount"]], $userMobile);
    }

     $officersInfo = ConfigSetting::whereIn(
         'label', ['director_general', 'director', 'chairman']
     )->where([
         'status' => 1
     ])->get()->toArray();
    //Send mail to Director General, Director, and Chairman
    $receiverInformationforDGDC = [];
    foreach ($officersInfo as $key => $officerInfo) {
        $receiverInformation = [
            'user_mobile' => '',
            'user_email' => $officerInfo['value']
        ];
        array_push($receiverInformationforDGDC, $receiverInformation);
    }
    if($flag == 0){
        CommonFunction::sendEmailSMS('APP_PAYMENT_REQUEST_FOR_ANNUAl_OR_BG_FEE', $appInfo, $receiverInformationforDGDC, $pdfLink);
    }
}

function insertDataInPDFPrintRequestTableForAnnualFeeOrBG($pdf_link_gen_params, $pdf_gen_number = 0, $flag = 0)
{

    $url_store = PdfPrintRequestQueue::firstOrNew([
        'process_type_id' => $pdf_link_gen_params["process_type_id"],
        'app_id' => $pdf_link_gen_params["app_id"],
        'pdf_diff' => pdf_diff($pdf_link_gen_params["status_id"], $pdf_gen_number)
    ]);

    $pdf_info = PdfServiceInfo::where('certificate_name', $pdf_link_gen_params["certificate_name"])->first([
        'pdf_server_url',
        'reg_key',
        'pdf_type',
        'certificate_name',
        'table_name',
        'field_name'
    ]);

    if (empty($pdf_info)) {
        return false;
    }
    $tableName = $pdf_info->table_name;
    $fieldName = $pdf_info->field_name;

    $url_store->process_type_id = $pdf_link_gen_params["process_type_id"];
    $url_store->app_id = $pdf_link_gen_params["app_id"];
   // $url_store->process_list_id = $pdf_link_gen_params["process_list_id"];
    $url_store->pdf_server_url = $pdf_info->pdf_server_url;
    $url_store->reg_key = $pdf_info->reg_key;
    $url_store->pdf_type = $pdf_info->pdf_type;
    $url_store->certificate_name = $pdf_info->certificate_name;
    $url_store->prepared_json = 0;
    $url_store->table_name = $tableName;
    $url_store->field_name = $fieldName;
    $url_store->url_requests = '';
    //        $url_store->status = 0;
    $url_store->job_sending_status = 0;
    $url_store->no_of_try_job_sending = 0;
    $url_store->job_receiving_status = 0;
    $url_store->no_of_try_job_receving = 0;
    if ($pdf_link_gen_params["certificate_type"] == 'generate') {
        if($flag == 0){
            $url_store->signatory = Auth::user()->id;
        }

        // Store approve information
        $signature_store_status = storeSignatureQRCode($pdf_link_gen_params["process_type_id"], $pdf_link_gen_params["app_id"], 0, $pdf_link_gen_params["approver_desk_id"], 'final', $pdf_link_gen_params["status_id"], $pdf_gen_number);

        if ($signature_store_status === false) {
            return false;
        }
    }
    $url_store->pdf_diff = pdf_diff($pdf_link_gen_params["status_id"], $pdf_gen_number);
    $url_store->updated_at = date('Y-m-d H:i:s');
    $url_store->save();

//    if ($pdf_gen_number == 1) {
//        $certificateControllerInstance = new CertificateGenerationController();
//        $generateCertificateStatus = $certificateControllerInstance->generateCertificate();
//    }
}
