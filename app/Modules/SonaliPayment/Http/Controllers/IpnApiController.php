<?php


namespace App\Modules\SonaliPayment\Http\Controllers;


use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Models\IpnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpnApiController
{
    /**
     * IPN request handling
     * @param Request $request
     */
    public function apiIpnRequestPOST(Request $request)
    {
        $requestData = $request->all();
        $allowed_ip_data = Configuration::where('caption', 'ALLOW_IP_IPN')->value('value');
        $allowed_ip = explode(",", $allowed_ip_data);
        $env_spg_user_id = trim(config('payment.spg_settings.spg_user_id'));
        $env_spg_password = trim(config('payment.spg_settings.spg_password'));
        $requestIp = IpnRequest::getVisitorRealIP();
        $IpnRequest = '';

        /*
         * Check request is empty or not.
         * if empty, then store the request and return with message.
         */
        DB::beginTransaction();
        $IpnRequest = IpnRequest::firstOrNew(['ref_tran_no' => trim($requestData['RefTranNo']), 'ref_tran_date_time' => trim($requestData['RefTransTime'])]);

        if (empty($requestData)) {
            $IpnRequest->request_ip = $requestIp;

            $response['Msg'] = 'Request empty';
            $response['ResponseCode'] = 0;
            $response['ResponseType'] = 'IPN_RESPONSE';
            header('Content-type: application/json');
            echo json_encode($response);

            $IpnRequest->ipn_response = json_encode($response);
            $IpnRequest->save();
            DB::commit();
            exit();
        }


        // Check request IP is authorized or not
        if (!in_array("$requestIp", $allowed_ip)) {
            $response['Msg'] = 'Request could not be processed due to unauthorized ip';
        }

        // Check request authentication information is valid or not
        if (!($env_spg_user_id == $requestData['UserId'] && $env_spg_password == $requestData['Password'])) {
            $response['Msg'] = 'Request could not be processed due to UserId and password';
        }

        // Check request type is valid or not
        if ($requestData['requestType'] != 'IPN') {
            $response['Msg'] = 'Request could not be processed due to request type';
        }

        try {
            $IpnRequest->request_ip = $requestIp;
            $IpnRequest->transaction_id = $requestData['TransId'];
            $IpnRequest->pay_mode_code = $requestData['PayMode'];
            $IpnRequest->trans_time = $requestData['TransTime'];
            $IpnRequest->ref_tran_no = $requestData['RefTranNo'];
            $IpnRequest->ref_tran_date_time = $requestData['RefTransTime'];
            $IpnRequest->trans_status = $requestData['TransStatus'];
            $IpnRequest->trans_amount = $requestData['TransAmount'];
            $IpnRequest->pay_amount = $requestData['PayAmount'];
            $IpnRequest->json_object = json_encode($requestData);

            // TODO:: Action after IPN confirmation
            //UtilFunction::spIPN($requestData['TransId']);


            /*
             * if there have any validation error like : invalid request type, unauthorized IP,
             * invalid user or password then return response with error status and
             * store request info with is_authorized_request = '0'.
             * Otherwise, return response with success status and store request info with
             * is_authorized_request = '1'.
             */
            if ($requestData['requestType'] != 'IPN' || !in_array("$requestIp",
                    $allowed_ip) || !($env_spg_user_id == $requestData['UserId'] && $env_spg_password == $requestData['Password'])
            ) {

                $response['ResponseCode'] = 0;
                $response['ResponseType'] = 'IPN_RESPONSE';
                $IpnRequest->is_authorized_request = 0;
            } else {
                $response['ResponseCode'] = 1;
                $response['ResponseType'] = 'IPN_RESPONSE';
                $response['Msg'] = 'Request has been processed successfully';
                $IpnRequest->is_authorized_request = 1;
            }

            header('Content-type: application/json');
            echo json_encode($response);
            $IpnRequest->ipn_response = json_encode($response);
            $IpnRequest->save();
            DB::commit();
            exit();

        } catch (Exception $e) {
            $response['ResponseCode'] = 0;
            $response['Msg'] = 'Sorry! Something went wrong to IPN request!';
            $response['ResponseType'] = 'IPN_RESPONSE';
            header('Content-type: application/json');
            echo json_encode($response);

            /*
             * if any exception has occurred, then the request will be missed.
             * So, we store request also for any exception with exception message.
             *
             */
            $IpnRequest->ipn_response = json_encode($response);
            $IpnRequest->save();
            DB::commit();
            exit();
        }

    }
}