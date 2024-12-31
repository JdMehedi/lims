<?php

namespace App\Modules\SonaliPayment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Library\SonaliPaymentACL;
use App\Modules\SonaliPayment\Models\IpnRequest;
use App\Modules\SonaliPayment\Models\PaymentCategory;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\SonaliPayment\Models\SonaliPaymentHistory;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\SonaliPayment\Models\PaymentStep;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SonaliPaymentController extends Controller
{
    use SPAfterPaymentManager;
    /**
     * Payment types
     * ================================
     * A01 => Sonali Bank Counter Based Payment
     * A02 => Sonali Bank Account
     * C01 => Sonali Bank Card(SBL)
     * ================================
     */
    public function index()
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            $ProcessType = ProcessType::whereStatus(1)
                                      ->orderBy('name')
                                      ->pluck('name', 'id')
                                      ->toArray();

            $CompanyInfo = CompanyInfo::orderBy('org_nm')
                                      ->pluck('org_nm', 'id')
                                      ->toArray();

            $PaymentSteps = PaymentStep::whereStatus(1)
                                       ->orderBy('name')
                                       ->pluck('name', 'id')
                                       ->toArray();

            return view('SonaliPayment::list', compact('ProcessType', 'CompanyInfo', 'PaymentSteps'));
        } catch (\Exception $e) {
//            dd($e->getMessage(), $e->getFile(), $e->getLine());
            Session::flash('error', 'Something went wrong! Error: ' . $e->getMessage() . '[SP-142]');
            return \redirect('dashboard');
        }
    }

    public function indivPaymentHistory($paymentId = '')
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            $ProcessType = ProcessType::whereStatus(1)
                                      ->orderBy('name')
                                      ->pluck('name', 'id')
                                      ->toArray();

            $CompanyInfo = CompanyInfo::orderBy('org_nm')
                                      ->pluck('org_nm', 'id')
                                      ->toArray();

            $PaymentSteps = PaymentStep::whereStatus(1)
                                       ->orderBy('name')
                                       ->pluck('name', 'id')
                                       ->toArray();

            return view(
                'SonaliPayment::payment-history',
                compact('ProcessType', 'CompanyInfo', 'PaymentSteps', 'paymentId')
            );
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong! Error: ' . $e->getMessage() . '[SP-142]');
            return \redirect('dashboard');
        }
    }

    /*
     * Payment list
     */
    public function paymentList()
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-E-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        DB::statement(DB::raw('set @rownum=0'));
        $list = SonaliPayment::leftJoin('process_list', function ($join) {
            $join->on('process_list.process_type_id', '=', 'sp_payment.process_type_id');
            $join->on('process_list.ref_id', '=', 'sp_payment.app_id');
        })
                             ->orderBy('sp_payment.id', 'desc')
                             ->get([
                                 'sp_payment.id',
                                 DB::raw('@rownum  := @rownum  + 1 AS sl_no'),
                                 'app_tracking_no',
                                 'contact_email',
                                 'app_id',
                                 'sp_payment.process_type_id',
                                 'pay_mode_code',
                                 'transaction_id',
                                 'request_id',
                                 'ref_tran_no',
                                 'ref_tran_date_time',
                                 'pay_amount',
                                 'payment_status',
                                 'is_verified',
                                 'process_list.tracking_no'
                             ]);

        return Datatables::of($list)
                         ->addColumn('action', function ($list) {
                             $btn = '';
                             if (SonaliPaymentACL::getAccessRight('SonaliPayment', '-A-')) {
                                 if ($list->payment_status == 1 && $list->is_verified == 1) {
                                     $btn .= '<a href="' . url('/spg/verifyAndComplete/' . Encryption::encodeId($list->id)) . '" class="btn btn-xs btn-info"><i class="fa fa-check"></i> Verify & Complete</a> <br/>';
                                 }
                                 $btn .= ' <a href="' . url('/spg/payment-history/' . Encryption::encodeId($list->id)) . '" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Open</a>';
                                 $btn .= ' <a href="' . url('/spg/ref-verification/' . Encryption::encodeId($list->id)) . '" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Verify by Ref. No.</a>';
                             }
                             return $btn;
                         })
                         ->editColumn('payment_status', function ($list) {
                             $status = '';
                             if ($list->payment_status == 1) {
                                 $status .= "<span class='label label-success'>Payment Success</span>";
                             } elseif ($list->payment_status == '-1') {
                                 $status .= "<span class='label label-warning'>Payment in-progress</span>";
                             } else {
                                 $status .= "<span class='label label-danger'>Payment Failed</span>";
                             }

                             if ($list->is_verified == 1) {
                                 $status .= "<br/><span class='label label-info'>Verification success</span>";
                             } else {
                                 $status .= "<br/><span class='label label-danger'>Verification failed</span>";
                             }
                             return $status;
                         })
                         ->removeColumn('id')
                         ->rawColumns(['payment_status', 'action'])
                         ->make(true);
    }

    public function indivPaymentHistoryData(Request $request)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-E-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $spPaymentId = Encryption::decodeId($request->get('sp_payment_id'));

        DB::statement(DB::raw('set @rownum=0'));
        $list = SonaliPaymentHistory::leftJoin('process_list', function ($join) {
            $join->on('process_list.process_type_id', '=', 'sp_payment_history.process_type_id');
            $join->on('process_list.ref_id', '=', 'sp_payment_history.app_id');
        })->where('sp_payment_id', $spPaymentId)
                                    ->orderBy('sp_payment_history.id', 'desc')
                                    ->get([
                                        'sp_payment_history.id',
                                        DB::raw('@rownum  := @rownum  + 1 AS sl_no'),
                                        'app_tracking_no',
                                        'sp_payment_id',
                                        'contact_email',
                                        'app_id',
                                        'sp_payment_history.process_type_id',
                                        'pay_mode_code',
                                        'transaction_id',
                                        'request_id',
                                        'ref_tran_no',
                                        'ref_tran_date_time',
                                        'pay_amount',
                                        'payment_status',
                                        'is_verified',
                                        'process_list.tracking_no'
                                    ]);

        return Datatables::of($list)
                         ->addColumn('action', function ($list) {
                             $btn = '';
                             if (SonaliPaymentACL::getAccessRight('SonaliPayment', '-A-')) {
                                 $btn .= '<a href="' . url('/spg/history-verify/' . Encryption::encodeId($list->sp_payment_id)) . '/' . Encryption::encodeId($list->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Verify</a>';
                             }
                             return $btn;
                         })
                         ->editColumn('payment_status', function ($list) {
                             $status = '';
                             if ($list->payment_status == 1) {
                                 $status .= "<span class='label label-success'>Payment Success</span>";
                             } elseif ($list->payment_status == '-1') {
                                 $status .= "<span class='label label-warning'>Payment in-progress</span>";
                             } else {
                                 $status .= "<span class='label label-danger'>Payment Failed</span>";
                             }

                             if ($list->is_verified == 1) {
                                 $status .= "<br/><span class='label label-info'>Verification success</span>";
                             } else {
                                 $status .= "<br/><span class='label label-danger'>Verification failed</span>";
                             }
                             return $status;
                         })
                         ->removeColumn('id')
                         ->rawColumns(['payment_status', 'action'])
                         ->make(true);
    }

    /*
     * Payment list by search
     */
    public function getPaymentList(Request $request)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-E-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $process_type_id = $request->process_type;
        $company_id = $request->company;
        $payment_category_id = $request->payment_type;

        DB::statement(DB::raw('set @rownum=0'));
        $list = SonaliPayment::leftJoin('process_list', function ($join) {
            $join->on('process_list.process_type_id', '=', 'sp_payment.process_type_id');
            $join->on('process_list.ref_id', '=', 'sp_payment.app_id');
        })->where('sp_payment.payment_status', 1);
        if (!empty($process_type_id)) {
            $list->where('sp_payment.process_type_id', $process_type_id);
        }
        if (!empty($company_id)) {
            $list->where('process_list.company_id', $company_id);
        }
        if (!empty($payment_category_id)) {
            $list->where('sp_payment.payment_category_id', $payment_category_id);
        }
        $list->orderBy('sp_payment.id', 'desc')
             ->get([
                 'sp_payment.id',
                 DB::raw('@rownum  := @rownum  + 1 AS sl_no'),
                 'pay_mode',
                 'app_id',
                 'sp_payment.process_type_id',
                 'app_tracking_no',
                 'pay_mode_code',
                 'transaction_id',
                 'request_id',
                 'ref_tran_no',
                 'ref_tran_date_time',
                 'pay_amount',
                 'payment_status',
                 'is_verified',
                 'process_list.tracking_no'
             ]);

        return Datatables::of($list)
                         ->addColumn('action', function ($list) {
                             $btn = '';
                             if (SonaliPaymentACL::getAccessRight('SonaliPayment', '-A-')) {
                                 if ($list->payment_status == 1 && ($list->is_verified == 0 || $list->is_verified == 1) && ($list->tracking_no == '' or $list->tracking_no == null)) {
                                     $btn .= '<a href="' . url('/spg/verifyAndComplete/' . Encryption::encodeId($list->id)) . '" class="btn btn-xs btn-info"><i class="fa fa-check"></i> Verify & Complete</a> <br/>';
                                 }
                                 $btn .= '<a href="' . url('/spg/verify/' . Encryption::encodeId($list->id)) . '" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Verify</a>';
                             }
                             return $btn;
                         })
                         ->editColumn('payment_status', function ($list) {
                             $status = '';
                             if ($list->payment_status == 1) {
                                 $status .= "<span class='label label-success'>Payment Success</span>";
                             } elseif ($list->payment_status == '-1') {
                                 $status .= "<span class='label label-warning'>Payment in-progress</span>";
                             } else {
                                 $status .= "<span class='label label-danger'>Payment Failed</span>";
                             }

                             if ($list->is_verified == 1) {
                                 $status .= "<br/><span class='label label-info'>Verification success</span>";
                             } else {
                                 $status .= "<br/><span class='label label-danger'>Verification failed</span>";
                             }
                             return $status;
                         })
                         ->removeColumn('id')
                         ->make(true);
    }

    /*
     * Multiple Sonali payment initiate
     */
    public function initiatePaymentMultiple($paymentId = null)
    {
        try {

            /**
             * Payment process without payment portal for development and testing
             * This function will be commented for UAT, Training and Production Server.
             */
            if (in_array(config('app.APP_ENV'), ['local', 'dev']) && config('payment.spg_settings.payment_mode') == 'off') {
                return (new self())->forcedPaymentProcessing($paymentId);
            }

            if ($paymentId != null) {
                DB::begintransaction();
                $web_service_url = config('payment.spg_settings.web_service_url');
                $user_id = config('payment.spg_settings.user_id');
                $password = config('payment.spg_settings.password');
                $sblAccount = config('payment.spg_settings.SBL_account');
                $payment_callback_url = config('payment.spg_settings.return_url_m');

                $decodePaymentId = Encryption::decodeId($paymentId);
                $paymentInfo = SonaliPayment::find($decodePaymentId);
                $payment_details = PaymentDetails::where('sp_payment_id', $paymentInfo->id)->get();

                /*
                 * $payment_details return a collection of data.
                 * php empty() function can't identify the collection is empty or not.
                 */
                if (empty($paymentInfo) || $payment_details->isEmpty()) {
                    DB::rollback();
                    Session::flash('error', 'Payment record not found! [SPM-102]');
                    return redirect()->back();
                }

                // Get application process information & URL redirection
                $process_info = ProcessList::leftJoin('process_type as pt', 'pt.id', '=', 'process_list.process_type_id')
                                           ->where('process_list.process_type_id', $paymentInfo->process_type_id)
                                           ->where('process_list.ref_id', $paymentInfo->app_id)
                                           ->first([
                                               'process_list.tracking_no',
                                               'pt.form_url',
                                           ]);

                //dd($process_info->tracking_no . '-' . $paymentInfo->payment_step_id);;


                $ref_tran_no = $process_info->tracking_no . '-' . $paymentInfo->payment_step_id;
                $paymentInfo->ref_tran_no = $ref_tran_no;
                $paymentInfo->app_tracking_no = $process_info->tracking_no;
                $paymentInfo->save();
                $this->updateProcessList($paymentInfo);               
                DB::commit();


                DB::beginTransaction();


                /*
                 * This code execute if second time payment button click
                 */
                if (!empty($paymentInfo->request_id)) {
                    $paymentVerifyCheck = (new self())->transactionVerificationWithRefNo($decodePaymentId);
                    if ($paymentVerifyCheck['code'] == '200') {

                        // Single payment distribution verify
                        (new self())->singlePaymentDetailsVerification($paymentInfo->id);

                        DB::commit();
                        $this->onlinePaymentCallbackProcessing($paymentInfo);
                        // return redirect($process_info->form_url . '/afterPayment/' . Encryption::encodeId($sonaliPayment->id));
                        return redirect($process_info->form_url . '/list/' . Encryption::encodeId($paymentInfo->process_type_id))->send();
                    }
                    // Payment pending
                    if ($paymentVerifyCheck['code'] == '201') {
                        $from_time = strtotime(date($paymentInfo->ref_tran_date_time));
                        $to_time = strtotime(date('Y-m-d H:i:s'));
                        $time_diff_minute = round(abs($to_time - $from_time) / 60);

                        $waiting_minutes = Configuration::where('caption', 'Payment_pending_minute')->value('value');
                        if ($time_diff_minute < $waiting_minutes) {
                            DB::commit();
                            Session::flash('error', 'Please try again after ' . (($waiting_minutes + 1) - $time_diff_minute) . ' minutes or call to help line. [SPM-107]');
                            return redirect()->back();
                        }
                    }
                }


                $send_request_id = config('payment.spg_settings.request_id_prefix') . rand(1000000, 9999999); // Will be change later
                $send_payment_date = date('Y-m-d');
                $send_ref_tran_date_time = date('Y-m-d H:i:s'); // need to clarify


                $body = '<?xml version="1.0" encoding="utf-8"?>
                 <soap:Envelope
                 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                 xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                 xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Header>
                        <SpgUserCredentials xmlns="http://tempuri.org/">
                            <userName>' . $user_id . '</userName>
                            <password>' . $password . '</password>
                        </SpgUserCredentials>
                    </soap:Header>
                    <soap:Body>
                        <GetSessionKey xmlns="http://tempuri.org/">
                            <strUserId>' . $user_id . '</strUserId>
                            <strPassKey>' . $password . '</strPassKey>
                            <strRequestId>' . $send_request_id . '</strRequestId>
                            <strAmount>' . ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount) . '</strAmount>
                            <strTranDate>' . $send_payment_date . '</strTranDate>
                            <strAccounts>' . $paymentInfo->receiver_ac_no . '</strAccounts>
                        </GetSessionKey>
                    </soap:Body>
                </soap:Envelope>';


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $web_service_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt(
                    $ch,
                    CURLOPT_HTTPHEADER,
                    array("Content-Type: text/xml;charset=utf-8", 'SOAPAction: "http://tempuri.org/GetSessionKey"')
                );
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    DB::rollback();
                    Session::flash('error', 'Request Error: ' . curl_error($ch) . '[SPM-139]');
                    return redirect()->back();
                }

                $xml_parser = xml_parser_create();
                xml_parse_into_struct($xml_parser, $result, $val);
                $getToken = $val[3]["value"];
                $sessTokenDecode = json_decode($getToken);
                if (!isset($sessTokenDecode)) {
                    DB::rollback();
                    Session::flash('error', 'Session token not found [SPM-103]');
                    return redirect()->back();
                }
                $sessionToken = $sessTokenDecode->scretKey;


                /*
                 * Payment credit data processing for
                 * request (Json & XML Format ) store in payment table
                 */
                $paymentReqCreditInfo = [];
                $sl = 1;
                $creditInfo = '';
                foreach ($payment_details as $data) {
                    if($data->pay_amount == 0) continue;
                    $singleCreditInfo = [];
                    $singleCreditInfo['SLNO'] = $sl;
                    $singleCreditInfo['CreditAccount'] = $data->receiver_ac_no;
                    $singleCreditInfo['CrAmount'] = $data->pay_amount;
                    $singleCreditInfo['Purpose'] = $data->purpose_sbl;
                    $singleCreditInfo['Onbehalf'] = $paymentInfo->contact_name;

                    $paymentReqCreditInfo[] = $singleCreditInfo;

                    // Credit info body for soap request
                    $creditInfo .= "<CreditInfo>
            <SLNO>$sl</SLNO>
            <CreditAccount>" . $data->receiver_ac_no . "</CreditAccount>
            <CrAmount>" . $data->pay_amount . "</CrAmount>
            <Purpose>" . $data->purpose_sbl . "</Purpose>
            <Onbehalf>" . $paymentInfo->contact_name . "</Onbehalf>
            </CreditInfo>";

                    $sl++;
                }

                // SOAP Request for payment initiate, which will store in payment table
                $body = "<html>
<head></head>
<body onload='document.forms[0].submit()'>
<form name='PostForm' method='POST' action=" . config('payment.spg_settings.web_service_url') . ">
<textarea name='datarequest' id='datarequest' rows='15' style='width:100%; display:none;'>
<SpsRequestByeChallan>
<RequestInformation>
 <Authentication>
<ApiAccessUserId>" . $user_id . "</ApiAccessUserId>
<AuthenticationKey>" . $sessionToken . "</AuthenticationKey>
 </Authentication>
 <ReferenceInfo>
<RequestId>" . $send_request_id . "</RequestId>
<RefTranNo>" . $paymentInfo->ref_tran_no . "</RefTranNo>
<RefTranDateTime>" . $send_ref_tran_date_time . "</RefTranDateTime>
<ReturnUrl>" . $payment_callback_url . "</ReturnUrl>
<ReturnMethod>POST</ReturnMethod>
<TranAmount>" . ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount) . "</TranAmount>
<ContactName>" . $paymentInfo->contact_name . "</ContactName>
<ContactNo>" . $paymentInfo->contact_no . "</ContactNo>
<PayerId>" . $paymentInfo->id . "</PayerId>
<Address>" . $paymentInfo->address . "</Address>
 </ReferenceInfo>

  <CreditInformation>" . $creditInfo . "</CreditInformation>

</RequestInformation>
</SpsRequestByeChallan>
</textarea></form>
</body>
</html>";

                // payment request info save in JSON
                $paymentReqInfo['ApiAccessUserId'] = $user_id;
                $paymentReqInfo['AuthenticationKey'] = $sessionToken;
                $paymentReqInfo['RequestId'] = $send_request_id;
                $paymentReqInfo['RefTranNo'] = $paymentInfo->ref_tran_no;
                $paymentReqInfo['RefTranDateTime'] = $send_ref_tran_date_time;
                $paymentReqInfo['ReturnUrl'] = $payment_callback_url;
                $paymentReqInfo['ReturnMethod'] = 'POST';
                $paymentReqInfo['TranAmount'] = $paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount;
                $paymentReqInfo['ContactName'] = $paymentInfo->contact_name;
                $paymentReqInfo['ContactNo'] = $paymentInfo->contact_no;
                $paymentReqInfo['PayerId'] = $paymentInfo->id;
                $paymentReqInfo['Address'] = $paymentInfo->address;
                $paymentReqInfo['CreditInformation'] = json_encode($paymentReqCreditInfo);

                $paymentInfo->payment_request = json_encode($paymentReqInfo);
                $paymentInfo->payment_request_xml = $body;
                //$paymentInfo->receiver_ac_no = $sblAccount;
                $paymentInfo->request_id = $send_request_id;
                $paymentInfo->payment_date = $send_payment_date;
                $paymentInfo->ref_tran_date_time = $send_ref_tran_date_time;
                $paymentInfo->save();

                DB::commit();
                return view(
                    'SonaliPayment::paymentRequestMultiple',
                    compact('sessionToken', 'paymentInfo', 'payment_details')
                );
            } else {
                DB::rollback();
                Session::flash('error', 'Invalid request! [SPM-104]');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Something went wrong! . Error: ' . $e->getMessage().'F:'.$e->getFile().'L:'.$e->getLine() . '[SPM-105]');
            return redirect()->back();
        }
    }

    /**
     * Forcefully payment processing for development and testing
     * @param $paymentId
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function forcedPaymentProcessing($paymentId)
    {
        $ref_url = $_SERVER["HTTP_REFERER"];
//        $url_sec = explode('/', $ref_url);


        $paymentId = Encryption::decodeId($paymentId);
        $sonaliPayment = SonaliPayment::where('id', $paymentId)->first();
        $process_info = ProcessType::where('id', $sonaliPayment->process_type_id)->first();
        $process_list = ProcessList::where('process_type_id', $sonaliPayment->process_type_id)
                                   ->where('ref_id', $sonaliPayment->app_id)->first();

        $ref_tran_no = $process_list->tracking_no . '-' . $sonaliPayment->payment_step_id;
        $sonaliPayment->ref_tran_no = $ref_tran_no;
        $sonaliPayment->app_tracking_no = $process_list->tracking_no;
        $sonaliPayment->payment_status = 1;
//        $sonaliPayment->app_id = $app_id;
//        $sonaliPayment->process_type_id = $process_type_id;
        $sonaliPayment->save();

        $this->onlinePaymentCallbackProcessing($sonaliPayment);
        // return redirect($process_info->form_url . '/afterPayment/' . Encryption::encodeId($sonaliPayment->id));
        // return redirect($process_info->form_url . '/list/' . Encryption::encodeId($sonaliPayment->process_type_id))->send();
        return redirect('/' . $process_info->form_url . '/list/' . Encryption::encodeId($sonaliPayment->process_type_id))->send();
    }

    /*
     * Multiple Payment request callback
     */
    public function callbackMultiple(Request $request)
    {
        try {
            $responseData = $request->get('Request');
            $xml = simplexml_load_string($responseData);
            if ($xml === false) {
                Session::flash('error', 'Payment request callback error.
                Please contact with Support team with your application tracking number. [SPM-146]');
                return redirect('dashboard');
            }
            
            $transaction_id = $xml->TransactionId->__toString();
            $refTranNo = $xml->RefTranNo->__toString();
            $refTranDateTime = $xml->RefTranDateTime->__toString();
            $TranAmount = $xml->TranAmount->__toString();
            $PayAmount = $xml->PayAmount->__toString();
            $vat = $xml->Vat->__toString();
            $commission = $xml->Commission->__toString();
            //$request_id = $xml->RefTranNo->__toString();
            $transaction_status = $xml->TransactionStatus->__toString();
            $pay_mode_code = $xml->PayMode->__toString();
            
            $payment_mode = getPaymentModeCodeMsg($pay_mode_code);

            $sonaliPayment = SonaliPayment::where('ref_tran_no', $refTranNo)
                                          ->first();
            $sonaliPayment->pay_mode = $payment_mode['pay_mode_msg'];
            $sonaliPayment->pay_mode_code = $pay_mode_code;
            $sonaliPayment->payment_response_xml = $responseData;
            $sonaliPayment->payment_response = json_encode($xml);
            $sonaliPayment->save();


            /*
            * $transaction_status (5555) is payment cancellation status.
            */
            if ($transaction_status == '5555') {
                Session::flash('error', 'Payment cancelled by user. [SPM-147]');
                return redirect('dashboard');
            }

            DB::beginTransaction();

            // get process info for URL redirection
            $process_type_info = ProcessType::where('id', $sonaliPayment->process_type_id)->first();

            // if transaction status == 200 (success) then check Transaction Verification
            if ($transaction_status == '200') {
                //$verifyStatus = $this->transactionVerificationMultiple($sonaliPayment->id);
                $verifyStatus = $this->transactionVerificationWithRefNo($sonaliPayment->id);

                /*
                 * if transaction verification is ok, then redirect to corresponding controller
                 * for next modification/updation on the payment.
                 */
                if (isset($verifyStatus['code']) && $verifyStatus['code'] == '200') {

                    // Single payment distribution verify
                    $this->singlePaymentDetailsVerification($sonaliPayment->id);
                    /// submit only if payment is successful
                    $process_list = ProcessList::where('ref_id',$sonaliPayment->app_id)->where('process_type_id',$sonaliPayment->process_type_id)->first();
                    $process_list->status_id=1; //submitted
                    $process_list->save();
                    ///
                    $sonaliPayment->save();
                    DB::commit();

                    // New function will be added here
                    $this->onlinePaymentCallbackProcessing($sonaliPayment);
                    // return redirect($process_info->form_url . '/afterPayment/' . Encryption::encodeId($sonaliPayment->id));
                    return redirect($process_type_info->form_url . '/list/' . Encryption::encodeId($sonaliPayment->process_type_id))->send();
                }

                // if verification response is false then redirect with message
                $sonaliPayment->save();
                DB::commit();
                return redirect($process_type_info->form_url . '/list/' . Encryption::encodeId($process_type_info->id))
                    ->with('success', 'Your payment has been successfully submitted to the Sonali Bank,
                    but the verification was not completed. Please contact the system support team
                    with this Transaction ID : ' . $transaction_id . '. [SPM-134]');
            } else {
                /*
                 * if transaction status is 5017 and payment mode is 'A01'
                 * then go to afterCounterPayment function to corresponding module
                 */
                if ($transaction_status == '5017' && $pay_mode_code == 'A01') {
                    $sonaliPayment->payment_status = (($pay_mode_code == 'A01') ? 3 : -2);
                    $sonaliPayment->save();

                    $this->counterPaymentCallbackProcessing($sonaliPayment);
                    DB::commit();
                    // return redirect($process_info->form_url . '/afterCounterPayment/' . Encryption::encodeId($sonaliPayment->id));
                    return redirect('client/'.$process_type_info->form_url . '/list/' . Encryption::encodeId($process_type_info->id))->send();
                }
            }
            $sonaliPayment->save();
            DB::commit();
            // redirect to corresponding application list with payment failure message
            return redirect($process_type_info->form_url . '/list/' . Encryption::encodeId($process_type_info->id))
                ->with('error', "Payment submission error. Please contact with system support team
                with this Transaction ID : " . $transaction_id . " and Transaction Status : " . $transaction_status . " [SPM-134]");
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash(
                'error',
                'Payment callback error. Message : ' . $e->getMessage() . '[SP-132]'
            );
            return redirect('dashboard');
        }
    }

    public function transactionVerificationWithRefNo($id)
    {
        try {
            $web_service_url = config('payment.spg_settings.spg_web_service_url');
            $user_id = config('payment.spg_settings.user_id');
            $password = config('payment.spg_settings.password');
            $ownerCode = config('payment.spg_settings.st_code');

            $paymentInfo = SonaliPayment::find($id);
            if (empty($paymentInfo)) {
                Session::flash('error', 'Invalid payment info [SP-150]');
                return false;
            }

            // Processing verification request as XML to store in process table and curl request
            $body = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <SpgUserCredentials xmlns="http://tempuri.org/">
      <userName>' . $user_id . '</userName>
      <password>' . $password . '</password>
    </SpgUserCredentials>
  </soap:Header>
  <soap:Body>
    <TransactionVerificationWithRefNo xmlns="http://tempuri.org/">
      <OwnerCode>' . $ownerCode . '</OwnerCode>
      <RefNo>' . $paymentInfo->ref_tran_no . '</RefNo>
      <isEncPwd>true</isEncPwd>
    </TransactionVerificationWithRefNo>
  </soap:Body>
</soap:Envelope>';


            // Processing Verification  request as Json to store in payment table
            $payVerificationReqInfo['userName'] = $user_id;
            $payVerificationReqInfo['password'] = $password;
            $payVerificationReqInfo['OwnerCode'] = $ownerCode;
            $payVerificationReqInfo['RefNo'] = $paymentInfo->ref_tran_no;
            $payVerificationReqInfo['isEncPwd'] = 'true';
            $payVerificationReqInfoJSON = json_encode($payVerificationReqInfo);

            $paymentInfo->verification_request_xml = $body;
            $paymentInfo->verification_request = $payVerificationReqInfoJSON;
            $paymentInfo->save();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $web_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: text/xml;charset=utf-8",
                'SOAPAction: "http://tempuri.org/TransactionVerificationWithRefNo"'
            ));
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                Session::flash('error', 'Request Error: ' . curl_error($ch) . '[SP-151]');
                return false;
            }
            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $result, $val);
            $responseData = json_decode($val[3]['value']);


            // Verification request & response store JSON & XML format in Payment table
            $paymentInfo->verification_response_xml = $result;
            $paymentInfo->verification_response = json_encode($responseData);

            $paymentInfo->status_code = $responseData->StatusCode;
            $paymentInfo->transaction_id = $responseData->TransactionId;

            $payment_mode = getPaymentModeCodeMsg($responseData->PayMode);
            $paymentInfo->pay_mode = $payment_mode['pay_mode_msg'];
            $paymentInfo->pay_mode_code = $responseData->PayMode;

            // if verification success then store payment info
            if (!empty($responseData->TransactionId) && $responseData->StatusCode == '200') {

                $paymentInfo->transaction_charge_amount = $responseData->Commission;
                $paymentInfo->vat_on_transaction_charge = $responseData->Vat;
                $paymentInfo->total_amount = ($responseData->Commission + $responseData->Vat + $responseData->TranAmount);
                $paymentInfo->sender_ac_no = 'Not given by SBL';
                $paymentInfo->is_verified = 1;
                $paymentInfo->payment_status = 1;
                $paymentInfo->spg_trans_date_time = $responseData->TransactionDate;
                $paymentInfo->save();

                return [
                    'status' => true,
                    'code' => $responseData->StatusCode,
                    'message' => 'Verification success.',
                ];
            }

            $paymentInfo->save();
            Session::flash('error', 'Invalid verification response [SP-152]');

            return [
                'status' => false,
                'code' => $responseData->StatusCode,
                'message' => 'Verification failed.',
            ];
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong. Error:' . $e->getMessage() . '[SP-153]');
            return false;
        }
    }

    /*
     * Transaction Verification by Reference Number
     */
    public function verifyTransactionByRefNo($id)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-E-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {

            DB::beginTransaction();
            $paymentId = Encryption::decodeId($id);
            // Payment verify by reference number and payment status update if not done yet.
            $verificationStatus = $this->transactionVerificationWithRefNo($paymentId);
            if (isset($verificationStatus['code']) && $verificationStatus['code'] == '200') {
                Session::flash('success', 'Payment verification successful. [SPC-154]');
            }
            DB::commit();

            // Error Session message will be set from transactionVerification() function
            return redirect('/spg/list');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry! Something went wrong! [SPC-156]');
            return redirect()->back();
        }
    }

    /*
     * Transaction Verification and application complete by user action
     */
    public function verifyAndComplete($id)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-E-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            DB::beginTransaction();
            $paymentId = Encryption::decodeId($id);
            // get payment info
            $paymentInfo = SonaliPayment::where('id', $paymentId)->first();
            if ($paymentInfo->payment_status != 1) {
                Session::flash('error', 'Payment not successful yet. [SPC-1085]');
                return redirect('/spg');
            }

            // Get application process information
            $process_data = ProcessList::where('process_type_id', $paymentInfo->process_type_id)
                                       ->where('ref_id', $paymentInfo->app_id)
                                       ->first();
            if ($process_data->desk_id != 0) {
                Session::flash('error', "The application is not in applicant desk. [SPC-1218]");
                return redirect()->back();
            }

            // get process info for URL redirection
            $process_type_info = ProcessType::where('id', $paymentInfo->process_type_id)->first();

            //$verificationStatus = $this->transactionVerification($paymentId);
            $verificationStatus = $this->transactionVerificationWithRefNo($paymentId);

            if (isset($verificationStatus['code']) && $verificationStatus['code'] == '200') {

                // A01 = Counter payment
                if ($paymentInfo->pay_mode_code == 'A01') {
                    $this->applicationRelatedTasks($process_type_info->name, $process_data, $paymentInfo);
                    DB::commit();

                    return redirect($process_type_info->form_url . '/list/' . Encryption::encodeId($paymentInfo->process_type_id))->send();
                }
                DB::commit();

                // Single payment distribution verify
                $this->singlePaymentDetailsVerification($paymentInfo->id);
                Session::put('payment.verifyComplete', true);
                Session::put('payment.updated_by', $paymentInfo->created_by);


                $this->onlinePaymentCallbackProcessing($paymentInfo);
                return redirect($process_info->form_url . '/list/' . Encryption::encodeId($paymentInfo->process_type_id))->send();
            }
            DB::commit();

            // Session message will be set from transactionVerification() function
            return redirect('/spg/list');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry! Something went wrong! [SPC-1215]');
            return redirect()->back();
        }
    }

    /*
     * Counter Payment Check by Applicant
     */
    public function counterPaymentCheck($paymentId = null, $status = null)
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-CPCR-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            DB::beginTransaction();
            $decodedStatus = Encryption::decodeId($status);
            $decodedPaymentId = Encryption::decodeId($paymentId);
            if ($paymentId != null) {

                $sonaliPayment = SonaliPayment::find($decodedPaymentId);
                if (empty($sonaliPayment)) {
                    Session::flash('error', 'Invalid payment info [SP-1599]');
                    return redirect()->back();
                }


                //$verifyStatus = $this->offlinePaymentVerify($paymentId);
                $verifyStatus = $this->verifyByRefNoForCounter($decodedPaymentId);
                /*
                 * if payment verification response is true
                 * then do necessary steps to transfer the application to submit status
                 */
                if ($decodedStatus == 1 && $verifyStatus == true) { // payment confirm = 1

                    // Single payment distribution verify
                    $this->singlePaymentDetailsVerification($sonaliPayment->id);

                    // get process info for URL redirection
                    $process_type_info = ProcessType::where('id', $sonaliPayment->process_type_id)->first();

                    // get process data from process list
                    $process_data = ProcessList::where([
                        'ref_id' => $sonaliPayment->app_id, 'process_type_id' => $sonaliPayment->process_type_id
                    ])->first();

                    $this->applicationRelatedTasks($process_type_info->name, $process_data, $sonaliPayment);
                    DB::commit();
                    return redirect($process_type_info->form_url . '/list/' . Encryption::encodeId($sonaliPayment->process_type_id))->send();
                } else if ($decodedStatus == 1 && $verifyStatus == false) {
                    Session::flash('error', 'Payment is not verified yet');
                }


                /*
                 * if user want to cancel counter payment
                 */
                if ($decodedStatus == 0) { // payment cancel = 0
                    if ($verifyStatus == true) {
                        DB::commit();
                        Session::flash('error', 'Your payment is already received by Sonali Bank.');
                        return redirect()->back();
                    }
                    $processInfo = ProcessList::where([
                        'ref_id' => $sonaliPayment->app_id,
                        'process_type_id' => $sonaliPayment->process_type_id,
                        'tracking_no' => $sonaliPayment->app_tracking_no,
                    ])->first();


                    $last_porcess = ProcessHistory::where('ref_id', $sonaliPayment->app_id)
                                                  ->where('process_type', $sonaliPayment->process_type_id)
                                                  ->orderBy('id', 'desc')
                                                  ->offset(1)
                                                  ->first();
                    if (empty($last_porcess)) {
                        Session::flash('error', 'Process history not found');
                        return redirect()->back();
                    }


                    $sonaliPayment->payment_status = '-3'; // Payment Cancel Status
                    $processInfo->status_id = $last_porcess->status_id;
                    $processInfo->desk_id = $last_porcess->desk_id;
                    $sonaliPayment->save();
                    $processInfo->save();
                    Session::flash('success', 'Payment cancelled successfully');
                }

                // Error Session message will come from offlinePaymentVerify() function
                DB::commit();
                return redirect()->back();
            }

            DB::commit();
            Session::flash('error', 'Invalid payment id [SPC-111]');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong! Error:' . $e->getMessage() . ' [SPC-121]');
            return redirect()->back();
        }
    }

    public function verifyByRefNoForCounter($id)
    {
        try {
            $web_service_url = config('payment.spg_settings.spg_web_service_url');
            $user_id = config('payment.spg_settings.user_id');
            $password = config('payment.spg_settings.password');
            $ownerCode = config('payment.spg_settings.st_code');

            $paymentInfo = SonaliPayment::find($id);
            if (empty($paymentInfo)) {
                Session::flash('error', 'Invalid payment info [SP-159]');
                return false;
            }

            // Processing verification request as XML to store in process table and curl request
            $body = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <SpgUserCredentials xmlns="http://tempuri.org/">
      <userName>' . $user_id . '</userName>
      <password>' . $password . '</password>
    </SpgUserCredentials>
  </soap:Header>
  <soap:Body>
    <TransactionVerificationWithRefNo xmlns="http://tempuri.org/">
      <OwnerCode>' . $ownerCode . '</OwnerCode>
      <RefNo>' . $paymentInfo->ref_tran_no . '</RefNo>
      <isEncPwd>true</isEncPwd>
    </TransactionVerificationWithRefNo>
  </soap:Body>
</soap:Envelope>';


            // Processing Verification  request as Json to store in payment table
            $payVerificationReqInfo['userName'] = $user_id;
            $payVerificationReqInfo['password'] = $password;
            $payVerificationReqInfo['OwnerCode'] = $ownerCode;
            $payVerificationReqInfo['RefNo'] = $paymentInfo->ref_tran_no;
            $payVerificationReqInfo['isEncPwd'] = 'true';
            $payVerificationReqInfoJSON = json_encode($payVerificationReqInfo);

            $paymentInfo->verification_request_xml = $body;
            $paymentInfo->verification_request = $payVerificationReqInfoJSON;
            $paymentInfo->save();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $web_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: text/xml;charset=utf-8",
                'SOAPAction: "http://tempuri.org/TransactionVerificationWithRefNo"'
            ));
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                Session::flash('error', 'Request Error: ' . curl_error($ch) . '[SP-160]');
                return false;
            }
            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $result, $val);
            $responseData = json_decode($val[3]['value']);


            // Verification request & response store JSON & XML format in Payment table
            $paymentInfo->verification_response_xml = $result;
            $paymentInfo->verification_response = json_encode($responseData);

            $paymentInfo->status_code = $responseData->StatusCode;
            $paymentInfo->transaction_id = $responseData->TransactionId;

            if (!empty($responseData->TransactionId) && $responseData->StatusCode == '200') {

                $payment_mode = getPaymentModeCodeMsg($responseData->PayMode);
                $paymentInfo->pay_mode = $payment_mode['pay_mode_msg'];
                $paymentInfo->pay_mode_code = $responseData->PayMode;

                $paymentInfo->transaction_charge_amount = $responseData->Commission;
                $paymentInfo->vat_on_transaction_charge = $responseData->Vat;
                $paymentInfo->total_amount = ($responseData->Commission + $responseData->Vat + $responseData->TranAmount);
                $paymentInfo->sender_ac_no = 'Not given by SBL';
                $paymentInfo->is_verified = 1;
                $paymentInfo->payment_status = 1;
                $paymentInfo->spg_trans_date_time = $responseData->TransactionDate;
                $paymentInfo->save();
                return true;
            }
            $paymentInfo->save();

            //            Session::flash('error', 'Invalid verification response [SP-161]');
            return false;
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong. Error:' . $e->getMessage() . '[SP-162]');
            return false;
        }
    }

    /*
     * IPN API method end
     */
    public function verifyTransactionHistory($id = '', $hist_id = '')
    {
        if (!SonaliPaymentACL::getAccessRight('SonaliPayment', '-E-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            DB::beginTransaction();
            $paymentId = Encryption::decodeId($id);
            $historyId = Encryption::decodeId($hist_id);
            $spPaymentInfo = SonaliPayment::find($paymentId);

            $verificationStatus = $this->transactionHistoryVerification($historyId);

            /*
             * if verification is true and (
             * payment->is_verified is not equal 1 or payment->payment_status is not equal 1)
             * then, update verification data in payment table.
             */
            if ($verificationStatus && ($spPaymentInfo->is_verified != '1' or $spPaymentInfo->payment_status != '1')) {
                $paymentHistoryInfo = SonaliPaymentHistory::find($historyId);

                $spPaymentInfo->request_id = $paymentHistoryInfo->request_id;
                $spPaymentInfo->ref_tran_date_time = $paymentHistoryInfo->ref_tran_date_time;
                $spPaymentInfo->verification_request_xml = $paymentHistoryInfo->verification_request_xml;
                $spPaymentInfo->verification_request = $paymentHistoryInfo->verification_request;
                $spPaymentInfo->verification_response_xml = $paymentHistoryInfo->verification_response_xml;
                $spPaymentInfo->verification_response = $paymentHistoryInfo->verification_response;
                $spPaymentInfo->is_verified = 1;
                $spPaymentInfo->payment_status = 1;
                $spPaymentInfo->save();

                DB::commit();
                Session::flash('success', 'Payment verification successful. [SPC-109]');
                return redirect('/spg');
            } /*
             * if verification is true only,
             * then no need to update verification data in payment table.
             */ elseif ($verificationStatus) {
                DB::commit();
                Session::flash('success', 'Payment verification successful. [SPC-109]');
                return redirect('/spg');
            }

            DB::commit();
            // Error Session message will be set from transactionHistoryVerification() function
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry! Something went wrong! [SPC-1214]');
            return redirect()->back();
        }
    }

    /*
     * Multiple Sonali payment Transaction Verification
     * This function is not used due to parameter complexity, now we used only verify by reference no function for all type of transaction verify
     */
    public function transactionVerificationMultiple($id)
    {
        try {
            $web_service_url = config('payment.spg_settings.spg_web_service_url');
            $user_id = config('payment.spg_settings.user_id');
            $password = config('payment.spg_settings.password');
            $ownerCode = config('payment.spg_settings.st_code');

            $paymentInfo = SonaliPayment::find($id);
            if (empty($paymentInfo)) {
                Session::flash('error', 'Invalid payment info [SPM-136]');
                return false;
            }

            $body = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Header>
                    <SpgUserCredentials xmlns="http://tempuri.org/">
                            <userName>' . $user_id . '</userName>
                            <password>' . $password . '</password>
                    </SpgUserCredentials>
                    </soap:Header>
                    <soap:Body>
                    <TransactionVerification
                    xmlns="http://tempuri.org/">
                    <OwnerCode>' . $ownerCode . '</OwnerCode>
                    <ReferenceDate>' . date('Y-m-d', strtotime($paymentInfo->ref_tran_date_time)) . '</ReferenceDate>
                    <RequiestNo>' . $paymentInfo->request_id . '</RequiestNo>
                    <isEncPwd>true</isEncPwd>
                    </TransactionVerification>
                    </soap:Body>
                    </soap:Envelope>';


            // Verification request store JSON & XML format in Payment table
            $payVerificationReqInfo['userName'] = $user_id;
            $payVerificationReqInfo['password'] = $password;
            $payVerificationReqInfo['OwnerCode'] = $ownerCode;
            $payVerificationReqInfo['ReferenceDate'] = date('Y-m-d', strtotime($paymentInfo->ref_tran_date_time));
            $payVerificationReqInfo['RequiestNo'] = $paymentInfo->request_id;
            $payVerificationReqInfo['isEncPwd'] = 'true';
            $payVerificationReqInfoJSON = json_encode($payVerificationReqInfo);

            $paymentInfo->verification_request_xml = $body;
            $paymentInfo->verification_request = $payVerificationReqInfoJSON;
            $paymentInfo->save();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $web_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: text/xml;charset=utf-8", 'SOAPAction: "http://tempuri.org/TransactionVerification"'
            ));
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                Session::flash('error', 'Request Error: ' . curl_error($ch) . '[SP-133]');
                return false;
            }
            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $result, $val);
            $responseData = json_decode($val[3]['value']);

            // Verification response store JSON & XML format in Payment table
            $paymentInfo->verification_response_xml = $result;
            $paymentInfo->verification_response = json_encode($responseData);

            if (!empty($responseData->TransactionId) && $responseData->StatusCode == '200') {
                $paymentInfo->is_verified = 1;
                $paymentInfo->save();
                return true;
            }
            $paymentInfo->save();

            Session::flash('error', 'Invalid verification response [SPM-138]');
            return false;
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong. Error:' . $e->getMessage() . '[SPM-138]');
            return false;
        }
    }

    /*
     * Single payment details transaction verification singlePaymentDetailsVerification
     */
    public function singlePaymentDetailsVerification($id)
    {
        try {
            $web_service_url = config('payment.spg_settings.single_details_url');
            $user_id = config('payment.spg_settings.user_id');
            $password = config('payment.spg_settings.password');
            $ownerCode = config('payment.spg_settings.st_code');

            $paymentInfo = SonaliPayment::find($id);
            if (empty($paymentInfo)) {
                Session::flash('error', 'Invalid payment info [SPDV-136]');
                return false;
            }

            $body = "{\n\"AccessUser\":{\n\"userName\":\"$user_id\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$paymentInfo->payment_date\",\n\"RequiestNo\":\"$paymentInfo->request_id\",\n\"isEncPwd\":true\n}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $web_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "cache-control: no-cache", "content-type: application/json"
            ));

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                Session::flash('error', 'Details payment verification error: ' . curl_error($ch) . '[SPDV-133]');
                return false;
            }
            $result1 = json_decode($result);
            $result_json = json_decode($result1);

            foreach ($result_json as $key => $value) {

                $paymentDetailsInfo = PaymentDetails::where(['sp_payment_id' => $id, 'receiver_ac_no' => $value->TranAccount])->first();

                if (empty($paymentDetailsInfo)) {
                    Session::flash('error', 'Payment details info is not found. [SPDV-189]');
                    return false;
                }

                $paymentDetailsInfo->verification_request = $body;

                $paymentDetailsInfoJson['TransactionId'] = $value->TransactionId;
                $paymentDetailsInfoJson['TransactionDate'] = $value->TransactionDate;
                $paymentDetailsInfoJson['ReferenceDate'] = $value->ReferenceDate;
                $paymentDetailsInfoJson['ReferenceNo'] = $value->ReferenceNo;
                $paymentDetailsInfoJson['TranAccount'] = $value->TranAccount;
                $paymentDetailsInfoJson['TranAmount'] = $value->TranAmount;
                $paymentDetailsInfoJson['StatusCode'] = $value->StatusCode;

                $paymentDetailsInfo->verification_response = json_encode($paymentDetailsInfoJson);
                $paymentDetailsInfo->confirm_amount_sbl = $value->TranAmount;

                if (!empty($value->TransactionId) && $value->StatusCode == '200') {
                    $paymentDetailsInfo->is_verified = 1;
                }

                $paymentDetailsInfo->save();
            }

            return true;
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong. Error:' . $e->getMessage() . '[SPDV-138]');
            return false;
        }
    }

    public function transactionHistoryVerification($id)
    {
        try {
            $web_service_url = config('payment.spg_settings.spg_web_service_url');
            $user_id = config('payment.spg_settings.user_id');
            $password = config('payment.spg_settings.password');
            $ownerCode = config('payment.spg_settings.st_code');

            $payment_history = SonaliPaymentHistory::find($id);
            if (empty($payment_history)) {
                Session::flash('error', 'Invalid payment info [SPM-136]');
                return false;
            }

            $body = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Header>
                    <SpgUserCredentials xmlns="http://tempuri.org/">
                            <userName>' . $user_id . '</userName>
                            <password>' . $password . '</password>
                    </SpgUserCredentials>
                    </soap:Header>
                    <soap:Body>
                    <TransactionVerification
                    xmlns="http://tempuri.org/">
                    <OwnerCode>' . $ownerCode . '</OwnerCode>
                    <ReferenceDate>' . date('Y-m-d', strtotime($payment_history->ref_tran_date_time)) . '</ReferenceDate>
                    <RequiestNo>' . $payment_history->request_id . '</RequiestNo>
                    <isEncPwd>true</isEncPwd>
                    </TransactionVerification>
                    </soap:Body>
                    </soap:Envelope>';


            // Verification request store JSON & XML format in Payment table
            $payVerificationReqInfo['userName'] = $user_id;
            $payVerificationReqInfo['password'] = $password;
            $payVerificationReqInfo['OwnerCode'] = $ownerCode;
            $payVerificationReqInfo['ReferenceDate'] = date('Y-m-d', strtotime($payment_history->ref_tran_date_time));
            $payVerificationReqInfo['RequiestNo'] = $payment_history->request_id;
            $payVerificationReqInfo['isEncPwd'] = 'true';
            $payVerificationReqInfoJSON = json_encode($payVerificationReqInfo);

            $payment_history->verification_request_xml = $body;
            $payment_history->verification_request = $payVerificationReqInfoJSON;
            // TODO:: transaction info (id, pay_mode etc) update if previous info is empty
            $payment_history->save();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $web_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: text/xml;charset=utf-8", 'SOAPAction: "http://tempuri.org/TransactionVerification"'
            ));
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                Session::flash('error', 'Request Error: ' . curl_error($ch) . '[SP-133]');
                return false;
            }
            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $result, $val);
            $responseData = json_decode($val[3]['value']);

            // Verification response store JSON & XML format in Payment table
            $payment_history->verification_response_xml = $result;
            $payment_history->verification_response = json_encode($responseData);

            if (!empty($responseData->TransactionId) && $responseData->StatusCode == '200') {
                $payment_history->is_verified = 1;
                $payment_history->save();
                return true;
            }
            $payment_history->save();

            Session::flash('error', 'Invalid verification response [SPM-138]');
            return false;
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong. Error:' . $e->getMessage() . '[SPM-138]');
            return false;
        }
    }

    /*
     * Offline payment verification
     */
    public function offlinePaymentVerify($paymentId)
    {
        try {
            $web_service_url = config('payment.spg_settings.spg_web_service_url');
            $user_id = config('payment.spg_settings.user_id');
            $password = config('payment.spg_settings.password');
            $ownerCode = config('payment.spg_settings.st_code');

            $decodedPaymentId = Encryption::decodeId($paymentId);
            $paymentInfo = SonaliPayment::find($decodedPaymentId);

            if (empty($paymentInfo)) {
                Session::flash('error', 'Invalid payment info. [SPC-115]');
                return false;
            }

            $body = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Header>
                    <SpgUserCredentials xmlns="http://tempuri.org/">
                            <userName>' . $user_id . '</userName>
                            <password>' . $password . '</password>
                    </SpgUserCredentials>
                    </soap:Header>
                    <soap:Body>
                    <TransactionVerification
                    xmlns="http://tempuri.org/">
                    <OwnerCode>' . $ownerCode . '</OwnerCode>
                    <ReferenceDate>' . date('Y-m-d', strtotime($paymentInfo->ref_tran_date_time)) . '</ReferenceDate>
                    <RequiestNo>' . $paymentInfo->request_id . '</RequiestNo>
                    <isEncPwd>true</isEncPwd>
                    </TransactionVerification>
                    </soap:Body>
                    </soap:Envelope>';


            // Verification request store JSON & XML format in Payment table
            $payVerificationReqInfo['userName'] = $user_id;
            $payVerificationReqInfo['password'] = $password;
            $payVerificationReqInfo['OwnerCode'] = $ownerCode;
            $payVerificationReqInfo['ReferenceDate'] = date('Y-m-d', strtotime($paymentInfo->ref_tran_date_time));
            $payVerificationReqInfo['RequiestNo'] = $paymentInfo->request_id;
            $payVerificationReqInfo['isEncPwd'] = 'true';

            $payVerificationReqInfoJSON = json_encode($payVerificationReqInfo);
            $paymentInfo->offline_verify_request_xml = $body;
            $paymentInfo->offline_verify_request = $payVerificationReqInfoJSON;
            $paymentInfo->save();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $web_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: text/xml;charset=utf-8", 'SOAPAction: "http://tempuri.org/TransactionVerification"'
            ));

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                Session::flash('error', 'Payment verification Request Error:. ' . curl_error($ch) . '[SPC-116]');
                return false;
            }

            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $result, $val);
            $responseData = json_decode($val[3]['value']);
            //$responseData = $responseData[0];

            /*
             * if response is empty then return false
             */
            if (empty($responseData) || $responseData->StatusCode != "200") {
                Session::flash('error', 'Your payment is not received by Sonali Bank till now. [SPC-139]');
                return false;
            }

            // Verification response store JSON & XML format in Payment table
            $paymentInfo->offline_verify_response_xml = $result;
            $paymentInfo->offline_verify_response = json_encode($responseData);

            if (!empty($responseData->TransactionId) && $responseData->StatusCode == "200") {
                $paymentInfo->is_verified = 1;
                $paymentInfo->save();
                return true;
            }

            $paymentInfo->save();

            Session::flash('error', 'Invalid verification response. [SPC-119]');
            return false;
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong! Error: . ' . $e->getMessage() . '[SPC-120]');
            return false;
        }
    }

    /*
     * Daily transaction list
     */
    public function dailyTransaction()
    {
        try {
            $web_service_url = config('payment.spg_settings.spg_web_service_url');
            $user_id = config('payment.spg_settings.user_id');
            $password = config('payment.spg_settings.password');
            $ownerCode = config('payment.spg_settings.st_code');

            $body = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Header>
                    <SpgUserCredentials xmlns="http://tempuri.org/">
                            <userName>' . $user_id . '</userName>
                            <password>' . $password . '</password>
                    </SpgUserCredentials>
                    </soap:Header>
                    <soap:Body>
                    <DailyStTransaction
                    xmlns="http://tempuri.org/">
                    <OwnerCode>' . $ownerCode . '</OwnerCode>
                    <ReferenceDate>' . date('Y-m-d') . '</ReferenceDate>
                    <isEncPwd>true</isEncPwd>
                    </DailyStTransaction>
                    </soap:Body>
                    </soap:Envelope>';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $web_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array("Content-Type: text/xml;charset=utf-8", 'SOAPAction: "http://tempuri.org/DailyStTransaction"')
            );

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                Session::flash('error', 'Daily Transaction request error: ' . curl_error($ch) . '[SPC-130]');
                return redirect()->back();
            }

            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $result, $val);
            echo 'Daily transaction details:</br></br></pre>';
            print_r($val[3]['value']);
            echo '</br></br>End of daily transaction details:</br>';
            $dailyTransactions = $val[3]['value'];
            return view('SonaliPayment::daily_transaction', compact('dailyTransactions'));
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong. Error: ' . $e->getMessage() . '[SPC-110]');
            return redirect()->back();
        }
    }

    public static function RedirectToPaymentPortal($payment_id)
    {
        return redirect('spg/initiate-multiple/' . $payment_id);
    }

    public function addRow( Request $request ) {
        $data['bank_list'] = Bank::orderBy('name')->where('is_active', 1)->pluck( 'name', 'id' )->toArray();
        $data['row_id'] = $request->get('row_id');
        $public_html    = (string) view( 'SonaliPayment::payment-ui.add-row', $data );
        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function DataUpdateByIPN(Request $request)
    {
        $env_spg_user_id = trim(config('payment.spg_settings.user_id'));
        $env_spg_password = trim(config('payment.spg_settings.password'));

        $ipnRequest = IpnRequest::firstOrNew([
            'ref_tran_no' => trim($request->data['InvoiceNo'])
        ], [
            'request_ip' => $request->ip(),
            'transaction_id' => trim($request->data['TransactionId']),
            'ref_tran_no' => trim($request->data['InvoiceNo']),
            'ref_tran_date_time' => trim($request->data['InvoiceDate']),
            'json_object' => json_encode($request->all()),
        ]);
        $ipnRequest->save();

        $response['Msg'] = 'Success';
        if (!($env_spg_user_id == $request->credential['userName'] && $env_spg_password == $request->credential['password'])) {
            $response['Msg'] = 'Request could not be processed due to UserId and password';
        }

        try {
            $sonaliPayment = SonaliPayment::query()
                ->where(function($q) use($request){
                    $q->where('transaction_id', $request->data['TransactionId']);
                    $q->orWhere('ref_tran_no', $request->data['InvoiceNo']);
                })
                ->first();

            if (!empty($sonaliPayment) && $sonaliPayment->payment_status == 1) { // if payment_status = 1
                $response['Status'] = "200";
                $response['TransactionId'] = $sonaliPayment->transaction_id ?? $request->data['TransactionId'];
            }
            elseif(!empty($sonaliPayment) && $sonaliPayment->payment_status != 1) { // if payment_status not equel 1
                $verifyStatus = $this->transactionVerificationWithRefNo($sonaliPayment->id);
                if (isset($verifyStatus['code']) && $verifyStatus['code'] == '200') {
                    $response['Status'] = "200";
                    $response['TransactionId'] = $sonaliPayment->transaction_id ?? $request->data['TransactionId'];
                }
            }

            $ipnRequest->ipn_response = json_encode($response);
            $ipnRequest->save();

            return response()->json($response);
        } catch (Exception $e) {
            $ipnRequest->ipn_response = json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
            $ipnRequest->save();
        }
    }
}
