<?php

namespace App\Modules\SonaliPayment\Services;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Models\User;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\SonaliPayment\Http\Controllers\PaymentPanelController;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\PaymentDistributionType;
use App\Modules\SonaliPayment\Models\PayOrderInfo;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\SonaliPayment\Models\AnnualFeeInfo;
use App\Modules\SonaliPayment\Models\PayOrderPayment;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

trait SPPaymentManager
{
    public function storeSubmissionFeeData($app_id, $payment_step_id, array $contact_info, array $unfixed_amounts = [], $request)
    {
        // Checking the Service Fee Payment(SFP) configuration for this service
        $payment_config = PaymentConfiguration::leftJoin(
            'sp_payment_steps',
            'sp_payment_steps.id',
            '=',
            'sp_payment_configuration.payment_step_id'
        )
            ->where([
                'sp_payment_configuration.process_type_id' => $this->process_type_id,
                'sp_payment_configuration.payment_step_id' => $payment_step_id,
                'sp_payment_configuration.status' => 1,
                'sp_payment_configuration.is_archive' => 0,
            ])->first(['sp_payment_configuration.*', 'sp_payment_steps.name']);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [SPPM-100]");
            // $messages = 'Payment configuration not found [SPPM-100]';
            // return response()->json(['responseCode' => 0, 'status' => false,  'html' => $messages]);
            // return $messages;
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'purpose', 'purpose_sbl', 'fix_status', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            Session::flash('error', "Stakeholder not found [SPPM-101]");
            return redirect()->back()->withInput();
        }

        // Store payment info
        $paymentInfo = SonaliPayment::firstOrNew([
            'app_id' => $app_id,
            'process_type_id' => $this->process_type_id,
            'payment_config_id' => $payment_config->id
        ]);

        $paymentInfo->payment_step_id = $payment_step_id;
        $paymentInfo->payment_type = $request->get('payment_type');
        //Concat Account no of stakeholder
        $account_no = "";
        foreach ($stakeDistribution as $distribution) {
            $account_no .= $distribution->stakeholder_ac_no . "-";
        }
        $account_numbers = rtrim($account_no, '-');
        //Concat Account no of stakeholder End

        $paymentInfo->receiver_ac_no = $account_numbers;
        $payment_amounts =  PaymentPanelController::unfixedAmountsForPayment($stakeDistribution, $unfixed_amounts, $payment_step_id, $this->process_type_id, $app_id);

        /*
         * The amount of unfixed Amount defaults to 0, if there is an unfixed Amount for this payment,
         * then this Amount will come when you arrive at this function.
         * And it will be added to the pay_amount, charge_amount, vat_amount
         */
        $paymentInfo->pay_amount = $payment_amounts['pay_amount_total'];
        $paymentInfo->vat_on_pay_amount = $payment_amounts['vat_on_pay_amount_total'];
        $paymentInfo->first_annual_fee = ($request->get('license_with_one_year_annual_fee') == 1) ? ($payment_amounts['annual_fee'] + $payment_amounts['annual_vat_fee']): 0;
        $paymentInfo->delay_fee = $payment_amounts['delay_fee'] + $payment_amounts['delay_vat_fee'];
        $paymentInfo->total_amount = $paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount;
        $paymentInfo->contact_name = $contact_info['contact_name'];
        $paymentInfo->contact_email = $contact_info['contact_email'];
        $paymentInfo->contact_no = $contact_info['contact_no'];
        $paymentInfo->address = $contact_info['contact_address'];
        $paymentInfo->is_annual_fee = (($request->get('license_or_annual_fee') == 2) || ($request->get('license_with_one_year_annual_fee') == 1)) ? 1 : 0;
        $paymentInfo->year = $request->get('annual_fee_current_year') ? $request->get('annual_fee_current_year') : 0;
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInfo->save();

        // Annual fee table row store
        $year_name = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th'];
        $loop_count = intval($request->get('annual_fee_year_counting'));
        if ($loop_count > 0) {
            $lastPaymentData = SonaliPayment::where([
                'app_id' => $app_id,
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1
            ])->first(['updated_at']); // Submission payment date

            $payment_step_id_count = intval($payment_step_id);
            for($i = 0; $i < $loop_count; $i++) {
                $year_no = $i+1;
                $annualFeeInfo = new AnnualFeeInfo();
                $annualFeeInfo->year = $year_no;
                $annualFeeInfo->year_nm = $year_name[$i];
                $annualFeeInfo->payment_due_date = (($request->get('license_with_one_year_annual_fee') == 1) && isset($lastPaymentData)) ? (date('Y-m-d', strtotime("+$year_no year", strtotime(strval($lastPaymentData->updated_at))))) : (date('Y-m-d', strtotime("+$year_no year")));
                $annualFeeInfo->process_type_id = $this->process_type_id;
                $annualFeeInfo->payment_step = $payment_step_id_count;
                $annualFeeInfo->app_id = $app_id;
                $annualFeeInfo->contact_email = $contact_info['contact_email'];
                $annualFeeInfo->contact_number = $contact_info['contact_no'];
                $annualFeeInfo->status = 0;
                $annualFeeInfo->save();
                $payment_step_id_count += 1;
            }
        }

        //Payment Details By Stakeholders
        foreach ($stakeDistribution as $distribution) {
            $paymentDetails = PaymentDetails::firstOrNew([
                'sp_payment_id' => $paymentInfo->id,
                'payment_distribution_id' => $distribution->id
            ]);

            if ($distribution->fix_status == 1) {
                $paymentDetails->pay_amount = $distribution->pay_amount;
            } else {
                $paymentDetails->pay_amount = $unfixed_amounts[$distribution->distribution_type];
            }

            $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
            $paymentDetails->purpose = $distribution->purpose;
            $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
            $paymentDetails->fix_status = $distribution->fix_status;
            $paymentDetails->distribution_type = $distribution->distribution_type;
            $paymentDetails->save();
        }

        //Payment Details By Stakeholders End
        return $paymentInfo->id;
    }

    public function storeSubmissionFeeDataV2($app_id, $payment_step_id, array $contact_info, array $unfixed_amounts = [], $request)
    {
       // dd($request->all());
// // pay order validation
//         $rules = [
            
//             'pay_order_number.*' => 'required',
//             // 'pay_order_date.*' => 'required|date_format:Y-m-d',
//             'bank_name.*' => 'required',
//             'branch_name.*' => 'required',
//         ];
  
//         $messages = [
            
//             'pay_order_number.required' => 'Pay order number is required.',
//             'pay_order_date.required' => 'Pay order date is required.',
//             // 'pay_order_date.date_format' => 'Invalid pay order date format.',
//             'bank_name.required' => 'Bank ID is required.',
//             'branch_name.required' => 'Branch ID is required.',
//         ];
       
//         $validator = Validator::make($request->all(), $rules, $messages);
//         if ($validator->fails()) {
//           //  dd($validator->fails());
//             Session::flash('error', 'pay order info not valid');
//             return redirect()->back()->withInput();
//         }
        // Checking the Service Fee Payment(SFP) configuration for this service
        $payment_config = PaymentConfiguration::leftJoin(
            'sp_payment_steps',
            'sp_payment_steps.id',
            '=',
            'sp_payment_configuration.payment_step_id'
        )
            ->where([
                'sp_payment_configuration.process_type_id' => $this->process_type_id,
                'sp_payment_configuration.payment_step_id' => $payment_step_id,
                'sp_payment_configuration.status' => 1,
                'sp_payment_configuration.is_archive' => 0,
            ])->first(['sp_payment_configuration.*', 'sp_payment_steps.name']);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [SPPM-100]");
            // $messages = 'Payment configuration not found [SPPM-100]';
            // return response()->json(['responseCode' => 0, 'status' => false,  'html' => $messages]);
            // return $messages;
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
//        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
//            ->where('status', 1)
//            ->where('is_archive', 0)
//            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'purpose', 'purpose_sbl', 'fix_status', 'distribution_type']);
//        if ($stakeDistribution->isEmpty()) {
//            Session::flash('error', "Stakeholder not found [SPPM-101]");
//            return redirect()->back()->withInput();
//        }

        // Store pay order info
        $process_list = ProcessList::where('process_type_id', $this->process_type_id)
                                   ->select('id','tracking_no','status_id','ref_id','process_type_id','hash_value','updated_by','user_id','status_id')
                                   ->where('ref_id', $app_id)->orderBy('id', 'desc')->first();

//        $paymentInfo = PayOrderPayment::firstOrNew([
//            'app_id' => $app_id,
//            'process_type_id' => $this->process_type_id,
////            'payment_config_id' => $payment_config->id
//        ]);
        $paymentInfo = [];
        $paymentInfo['payment_step_id'] = $payment_step_id;
//        $paymentInfo['ref_tran_no'] = $process_list->tracking_no . '-' . $payment_step_id;
        $paymentInfo['app_tracking_no'] = $process_list->tracking_no;


//        $paymentInfo['pay_order_number'] = '';
//        $paymentInfo['pay_order_date'] = '';
//        $paymentInfo['bank_id'] = '';
//        $paymentInfo['branch_id'] = '';
//        if(!empty($request->pay_order_copy)) {
//            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
//            $path      = 'uploads/pay_order/' . $yearMonth;
//            if ( ! file_exists( $path ) ) {
//                mkdir( $path, 0777, true );
//            }
//            $fileName = time() . '.' . $request->pay_order_copy->extension();
//            $request->pay_order_copy->move(public_path($path), $fileName);
//            $paymentInfo['pay_order_copy'] = $path.$fileName;
//        }


        // Bank guarantee store
        $paymentInfo['bg_expire_date'] = !empty($request->get('bg_expire_date')) ? date('Y-m-d', strtotime($request->get('bg_expire_date'))) : null;
        $paymentInfo['bg_bank_id'] = $request->get('bg_bank_name');
        $paymentInfo['bg_branch_id'] = $request->get('bg_branch_name')? $request->get('bg_branch_name') : 0;
        if(!empty($request->bg_copy)) {
            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
            $path      = 'uploads/bank_guarantee/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $fileName = time() . '.' . $request->bg_copy->extension();
            $request->bg_copy->move(public_path($path), $fileName);
            $paymentInfo['bg_copy'] = $path.$fileName;
        }
        $paymentInfo['is_verified'] = 1;
        //Concat Account no of stakeholder
        $account_no = "";
//        foreach ($stakeDistribution as $distribution) {
//            $account_no .= $distribution->stakeholder_ac_no . "-";
//        }
        $account_numbers = rtrim($account_no, '-');
        //Concat Account no of stakeholder End

        $paymentInfo['receiver_ac_no'] = $account_numbers;
//        $payment_amounts =  PaymentPanelController::unfixedAmountsForPayment($stakeDistribution, $unfixed_amounts);

        /*
         * The amount of unfixed Amount defaults to 0, if there is an unfixed Amount for this payment,
         * then this Amount will come when you arrive at this function.
         * And it will be added to the pay_amount, charge_amount, vat_amount
         */
//        $paymentInfo->pay_amount = $payment_amounts['pay_amount_total'];
//        $paymentInfo->vat_on_pay_amount = $payment_amounts['vat_on_pay_amount_total'];
//        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo['payment_type'] = $request->get('payment_type');
        $paymentInfo['first_annual_fee'] = $request->get('pay_order_annual_fee') ? $request->get('pay_order_annual_fee') : 0;
        $paymentInfo['delay_fee'] = $request->get('pay_order_delay_fee') ? $request->get('pay_order_delay_fee') : 0;
        $paymentInfo['pay_amount'] = ($request->get('pay_amount') ? $request->get('pay_amount') : 0) + $paymentInfo['first_annual_fee'] + $paymentInfo['delay_fee'];
        $paymentInfo['vat_on_pay_amount'] = $request->get('vat_on_pay_amount') ? $request->get('vat_on_pay_amount') : 0;
        $paymentInfo['total_amount'] = $request->get('total_amount') == ($paymentInfo['pay_amount'] + $paymentInfo['vat_on_pay_amount']) ? $request->get('total_amount') : ($paymentInfo['pay_amount'] + $paymentInfo['vat_on_pay_amount']);
        $paymentInfo['contact_name'] = $contact_info['contact_name'];
        $paymentInfo['contact_email'] = $contact_info['contact_email'];
        $paymentInfo['contact_no'] = $contact_info['contact_no'];
        $paymentInfo['address'] = $contact_info['contact_address'];
        $paymentInfo['is_annual_fee'] = (($request->get('license_or_annual_fee') == 2) || ($request->get('license_with_one_year_annual_fee') == 1)) ? 1 : 0;
        $paymentInfo['year'] = $request->get('annual_fee_current_year') ? $request->get('annual_fee_current_year') : 0;
        $paymentInfo['sl_no'] = 1; // Always 1
        $paymentInfo['payment_status'] = -1;
//        $paymentInfo->save();
        $paymentStore = SonaliPayment::updateOrCreate([
            'app_id' => $app_id,
            'process_type_id' => $this->process_type_id,
            'payment_step_id' => $payment_step_id,
            'year' => $paymentInfo['year']
//            'ref_tran_no' => $process_list->tracking_no ? $process_list->tracking_no . '-' . $payment_step_id : null,
//            'payment_config_id' => $payment_config->id
        ], $paymentInfo);

        // Annual fee table row store
        $year_name = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th'];
        $loop_count = intval($request->get('annual_fee_year_counting'));
        if ($loop_count > 0) {
            $lastPaymentData = SonaliPayment::where([
                'app_id' => $app_id,
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1
            ])->first(['updated_at']); // Submission payment date

            $payment_step_id_count = intval($payment_step_id);
            for($i = 0; $i < $loop_count; $i++) {
                $year_no = $i+1;
                $annualFeeInfo = new AnnualFeeInfo();
                $annualFeeInfo->year = $year_no;
                $annualFeeInfo->year_nm = $year_name[$i];
                $annualFeeInfo->payment_due_date = (($request->get('license_with_one_year_annual_fee') == 1) && isset($lastPaymentData)) ? (date('Y-m-d', strtotime("+$year_no year", strtotime(strval($lastPaymentData->updated_at))))) : (date('Y-m-d', strtotime("+$year_no year")));
                $annualFeeInfo->process_type_id = $this->process_type_id;
                $annualFeeInfo->payment_step = $payment_step_id_count;
                $annualFeeInfo->app_id = $app_id;
                $annualFeeInfo->contact_email = $contact_info['contact_email'];
                $annualFeeInfo->contact_number = $contact_info['contact_no'];
                $annualFeeInfo->status = 0;
                $annualFeeInfo->save();
                $payment_step_id_count += 1;
            }
        }

        // store pay order info
        $payOrderArrayInfo = $this->storePayOrderInfo($paymentStore->id,$request);
        //Payment Details By Stakeholders
//        foreach ($stakeDistribution as $distribution) {
//            $paymentDetails = PaymentDetails::firstOrNew([
//                'sp_payment_id' => $paymentInfo->id,
//                'payment_distribution_id' => $distribution->id
//            ]);
//
//            if ($distribution->fix_status == 1) {
//                $paymentDetails->pay_amount = $distribution->pay_amount;
//            } else {
//                $paymentDetails->pay_amount = $unfixed_amounts[$distribution->distribution_type];
//            }
//
//            $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
//            $paymentDetails->purpose = $distribution->purpose;
//            $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
//            $paymentDetails->fix_status = $distribution->fix_status;
//            $paymentDetails->distribution_type = $distribution->distribution_type;
//            $paymentDetails->save();
//        }
        if(
            ($request->get('actionBtn') == 'submit' || $process_list->status_id != -1) &&
            $process_list->status_id != 2
        ) {
            $process_type_info = ProcessType::where('id', $paymentStore->process_type_id)
                                ->orderBy('id', 'desc')
                                ->first([
                                    'form_url',
                                    'process_type.process_desk_status_json',
                                    'process_type.name',
                                ]);
            $submission_sql_param = [
                'app_id' => $process_list->ref_id,
                'process_type_id' => $process_list->process_type_id,
            ];

            $payment_json_name = $this->getPaymentJsonName($paymentStore->payment_step_id);

            $general_submission_process_data = $this->getProcessDeskStatus($payment_json_name, $process_type_info->process_desk_status_json, $submission_sql_param);

            // Assign application submission date if application status is draft (-1)
            if ($process_list->status_id == '-1') {
                $process_list->submitted_at = Carbon::now();
            }

            $process_list->status_id = $general_submission_process_data['process_starting_status'];
            $process_list->desk_id = $general_submission_process_data['process_starting_desk'];
            $process_list->user_id = $general_submission_process_data['process_starting_user'];
            $process_list->process_desc = 'Payment completed successfully.';
            $resultData = $process_list->id . '-' . $process_list->tracking_no .
                          $process_list->desk_id . '-' . $process_list->status_id . '-' . $process_list->user_id . '-' .
                          $process_list->updated_by;

            $process_list->previous_hash = $process_list->hash_value ?? "";
            $process_list->hash_value = Encryption::encode($resultData);
            $process_list->save();
            $this->callbakForPayOrder($paymentStore);

            if($process_list->status_id === 1){
                $userMobile = Auth::user()->user_mobile;
                $loginControllerInstance = new LoginController();
                $serviceName = ProcessType::where('id','=', $process_list->process_type_id)->value('drop_down_label');
                $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => $serviceName, '{$trackingNumber}' => $process_list->tracking_no], $userMobile);

                //TODO:: send email

                $receiverInfo = [
                    array(
                        'user_mobile' => Auth::user()->user_mobile,
                        'user_email' => Auth::user()->user_email
                    )
                ];

                $appInfo = [
                    'app_id'            => $process_list->ref_id,
                    'status_id'         => $process_list->status_id,
                    'process_type_id'   => $process_list->process_type_id,
                    'tracking_no'       => $process_list->tracking_no,
                    'process_type_name' => $serviceName,
                    'remarks'           => ''
                ];


                CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);


                // Send mail to desk officer for notifying that an application has been submitted
//                $getAllDeskUserForSendMail = User::where([
//                    'user_type' => '4x404',
//                    'desk_id' => 1,
//                ])->get()->toArray();
//
//
//                if(!empty($getAllDeskUserForSendMail)){
//                    foreach ($getAllDeskUserForSendMail as $user){
//                        $receiverInfo = [
//                            array(
//                                'user_mobile' => $user['user_mobile'],
//                                'user_email' => $user['user_email']
//                            )
//                        ];
//                        CommonFunction::sendEmailSMS( 'DESK_PROCESS_MAIL_TO_DESK_OFFICER', $appInfo, $receiverInfo );
//                    }
//                }

            }
        }

        return $paymentStore->id;
    }

    public function storePayOrderInfo($payment_id,$request){
        PayOrderInfo::where( 'pay_order_info_id', $payment_id )->delete();
        $payOrderArr = [];
        foreach ($request->get('pay_order_number') ?? [] as $key => $item) {
            $payOrderInfo = new PayOrderInfo();
            $payOrderInfo->pay_order_info_id = $payment_id;
            $payOrderInfo->pay_order_num = $item;
            $payOrderInfo->pay_order_date = !empty($request->get('pay_order_date')) ? date('Y-m-d', strtotime($request->pay_order_date[$key])) : null;
            $payOrderInfo->bank_id = !empty($request->bank_name[$key]) ? $request->bank_name[$key] : null;
            $payOrderInfo->branch_id = !empty($request->branch_name[$key]) ? $request->branch_name[$key] : null;

            if (!empty($request->pay_order_copy[$key])) {
                $yearMonth = date('Y') . '/' . date('m') . '/';
                $path = 'uploads/pay_order/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $fileName = time() . '.' . $request->pay_order_copy[$key]->extension();
                $request->pay_order_copy[$key]->move(public_path($path), $fileName);
                $payOrderInfo->pay_order_copy = $path . $fileName;
            } else {
                $payOrderInfo->pay_order_copy = !empty($request->pay_order_exists[$key]) ? $request->pay_order_exists[$key] : null;
            }
            $payOrderInfo->status = 1;
            $payOrderInfo->save();
            $singlePayOrderData = $payOrderInfo->toArray();
            $payOrderArr[] =$singlePayOrderData;
        }
        return $payOrderArr;
    }

    public function storeBankGuaranteeInfo($app_id, $payment_step_id, $process_type_id, $request){
        $paymentInfo = [];

        // Store pay order info
        $process_list = ProcessList::where('process_type_id', $process_type_id)
            ->select('id','tracking_no','status_id','ref_id','process_type_id','hash_value','updated_by','user_id','status_id')
            ->where('ref_id', $app_id)->first();


        $paymentInfo['payment_step_id'] = $payment_step_id;
        $paymentInfo['app_tracking_no'] = $process_list->tracking_no;

        // Bank guarantee store
        $paymentInfo['bg_expire_date'] = !empty($request->get('bg_expire_date')) ? date('Y-m-d', strtotime($request->get('bg_expire_date'))) : null;
        $paymentInfo['bg_bank_id'] = $request->get('bg_bank_name');
        $paymentInfo['bg_branch_id'] = $request->get('bg_branch_name')? $request->get('bg_branch_name') : 0;
        if(!empty($request->bg_copy)) {
            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
            $path      = 'uploads/bank_guarantee/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $fileName = time() . '.' . $request->bg_copy->extension();
            $request->bg_copy->move(public_path($path), $fileName);
            $paymentInfo['bg_copy'] = $path.$fileName;
        }
        $paymentInfo['is_bg'] = 1;
        $paymentInfo['total_amount'] = $request->get('bank_guarantee_amount');
        $paymentInfo['sl_no'] = 1; // Always 1
        $paymentInfo['payment_status'] = -1;

        $paymentStore = SonaliPayment::updateOrCreate([
            'app_id' => $app_id,
            'process_type_id' => $process_type_id,
            'payment_step_id' => $payment_step_id,
        ], $paymentInfo);

        if(
            ($request->get('actionBtn') == 'submit' || $process_list->status_id != -1) &&
            $process_list->status_id != 2
        ) {
            // Assign application submission date if application status is draft (-1)
            if ($process_list->status_id == '-1') {
                $process_list->submitted_at = Carbon::now();
            }

//            $process_list->status_id = $general_submission_process_data['process_starting_status'];
//            $process_list->desk_id = $general_submission_process_data['process_starting_desk'];
//            $process_list->user_id = $general_submission_process_data['process_starting_user'];
            $process_list->status_id = 63;
            $process_list->desk_id = 5;
            $process_list->process_desc = 'Payment completed successfully.';
            $resultData = $process_list->id . '-' . $process_list->tracking_no .
                $process_list->desk_id . '-' . $process_list->status_id . '-' . $process_list->user_id . '-' .
                $process_list->updated_by;

            $process_list->previous_hash = $process_list->hash_value ?? "";
            $process_list->hash_value = Encryption::encode($resultData);
            $process_list->save();
//            $this->callbakForPayOrder($paymentStore);

//            if($process_list->status_id === 1){
//                $userMobile = Auth::user()->user_mobile;
//                $loginControllerInstance = new LoginController();
//                $serviceName = ProcessType::where('id','=', $process_list->process_type_id)->value('name');
//                $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => $serviceName, '{$trackingNumber}' => $process_list->tracking_no], $userMobile);
//
//                //TODO:: send email
//
//                $receiverInfo = [
//                    array(
//                        'user_mobile' => Auth::user()->user_mobile,
//                        'user_email' => Auth::user()->user_email
//                    )
//                ];
//
//                $appInfo = [
//                    'app_id'            => $process_list->ref_id,
//                    'status_id'         => $process_list->status_id,
//                    'process_type_id'   => $process_list->process_type_id,
//                    'tracking_no'       => $process_list->tracking_no,
//                    'process_type_name' => $serviceName,
//                    'remarks'           => ''
//                ];
//
//
//                CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);
//
//            }
        }

        return $paymentStore->id;
    }

    public function callbakForPayOrder($paymentInfo) {
        if ($paymentInfo->is_annual_fee && $paymentInfo->year) {
            $annualFeeData = AnnualFeeInfo::where([
                'process_type_id' => $paymentInfo->process_type_id,
                'app_id' => $paymentInfo->app_id,
                'payment_step' => $paymentInfo->payment_step_id,
                'year' => $paymentInfo->year,
                'status' => 0
            ]);

            $annualFeeData->update([
                'paid_at' => Carbon::now(),
                'status' => 1
            ]);
        }
    }
}
