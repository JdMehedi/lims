<?php

namespace App\Modules\API\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Models\ActionInformation;
use App\Models\DNothiHistoryLogs;
use App\Models\DNothiPdfPrintRequestQueue;
use App\Models\UrlInformation;
use App\Modules\API\Http\Controllers\Traits\Notification;
use App\Modules\ProcessPath\Http\Controllers\ProcessPathController;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessPath;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServiceInfo;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Modules\API\Http\Controllers\Traits\ApiRequestManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Diff\Exception;


class APIController extends Controller
{
    use ApiRequestManager, Notification;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function apiRequest()
    {
        $response = array();
        try {
            $paramValue = str_replace('\"', '"', $_REQUEST['param']);
            $requestData = $this->getParamValue($paramValue);
            $requestType = $requestData['osspidRequest']['requestType'];
            $response = $this->manageRequestType($requestType, $requestData);

        } catch (\Exception $e) {
            // In case of invalid request format
            $response['osspidResponse'] = [
                'responseTime' => Carbon::now()->timestamp,
                'responseType' => '',
                'responseCode' => '400',
                'responseData' => [],
                'message' => 'Bad request format.' . $e->getMessage() . $e->getFile() . $e->getLine()
            ];
            $response = response()->json($response);
        }
        return $response;
    }

    /**
     * Get Parameter as JSON decoded
     * @param $getParam
     * @return mixed
     */
    function getParamValue($getParam)
    {
        $this->writeLog("Request", $getParam);
        return $returnArray = json_decode($getParam, true);
    }

    /**
     * Write Log in Local File
     * @param $type
     * @param $log
     */
    function writeLog($type, $log)
    {
        date_default_timezone_set('Asia/Dhaka');
        $fileName = storage_path() . '/logs/' . date("Ymd") . ".txt";
        //echo $fileName;die();
        $file = fopen($fileName, "a");
        if ($type == "Request") {
            fwrite($file, "\r### " . date("H:i:s") . "\t" . $type . ":" . $log);
        } else {
            fwrite($file, "\r###" . $type . ":" . $log);
        }
        fclose($file);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function newJob(Request $request)
    {
        $client_request = $request->all();

        if ($client_request == ''
            || $client_request == null
            || !isset($client_request)) {
            $response = array( // Response for invalid request
                'status' => 400,
                'success' => false,
                'error' => array(
                    'code' => 'EQR101',
                    'message' => 'Invalid Request or Parameter'
                ),
                'response' => null
            );
        } else {

            $url = (isset($client_request['url']) ? $client_request['url'] : '#');
            $ip_address = $client_request['ip_address'];
            $user_id = $client_request['user_id'];
            $project_code = $client_request['project'];
            $message = $client_request['message'];

            $prev_url = UrlInformation::where('user_id', $user_id)
                ->orderBy('id', 'DESC')
                ->first();

            if (!empty($prev_url)) {
                // store time duration in Hour:Minute format. Ex - 01:02 (1 hour 2 minute). second is not stored
                $time_diff = (new Carbon(date('Y:m:d H:i:s', time())))->diff(new Carbon($prev_url->in_time))->format('%h:%I');
                UrlInformation::where('id', $prev_url->id)->update([
                    'out_time' => date('Y:m:d H:i:s', time()),
                    'duration' => $time_diff
                ]);
            }

            UrlInformation::create([
                'url' => $url,
                'ip_address' => $ip_address,
                'project_code' => $project_code,
                'message' => $message,
                'in_time' => date('Y:m:d H:i:s', time()),
                'user_id' => $user_id
            ]);
            $response = array( // Response for valid request
                'status' => 200,
                'success' => true,
                'error' => null,
            );
            http://localhost:8000/api/new-job?requestData={%22data%22:{%22project%22:%22beza%22,%20%22user_id%22:%2211%22,%22url%22:%22localhost://111.com%22,%22method%22:%22post%22}}

        }
        return \GuzzleHttp\json_encode($response);
    }

    public function actionNewJob(Request $request)
    {
        $client_request = $request->all();


        if ($client_request == ''
            || $client_request == null
            || !isset($client_request)) {
            $response = array( // Response for invalid request
                'status' => 400,
                'success' => false,
                'error' => array(
                    'code' => 'EQR101',
                    'message' => 'Invalid Request or Parameter'
                ),
                'response' => null
            );
        } else {

            $url = (isset($client_request['url']) ? $client_request['url'] : '#');
            $ip_address = $client_request['ip_address'] ?? "";
            $action = trim($client_request['action'] ?? "");
            $user_id = $client_request['user_id'] ?? "";
            $project_code = $client_request['project'] ?? "";
            $message = $client_request['message'] ?? "";

            ActionInformation::create([
                'url' => $url,
                'action' => $action,
                'ip_address' => $ip_address,
                'project_code' => $project_code,
                'message' => $message,
                'user_id' => $user_id
            ]);

            $response = array( // Response for valid request
                'status' => 200,
                'success' => true,
                'error' => null,
            );
            http://localhost:8000/api/new-job?requestData={%22data%22:{%22project%22:%22beza%22,%20%22user_id%22:%2211%22,%22url%22:%22localhost://111.com%22,%22method%22:%22post%22}}
        }
        return \GuzzleHttp\json_encode($response);
    }

    public function getNotificationStatus(Request $request)
    {
            $client_request = $request->all();
            $pass = $request->header('pass');
            $username = $request->header('username');
             if(defaultToken() === getNotificationToken($username, $pass)) {
             try{
                $processData = ProcessList::where('nothi_receipt_no', $client_request['dak_id'])->first();
                $processPath = ProcessPath::where('process_type_id', $processData->process_type_id)->where('status_to', $client_request['decision_answer'])->first();
                    $process_list_id = Encryption::encodeId($processData->id);
                    $cat_id = Encryption::encodeId($processData->cat_id);
                    $desk_from = Encryption::encodeId($processPath->desk_from);
                    $status_from = Encryption::encodeId($processPath->status_from);
                    $application_ids = Encryption::encodeId($processData->ref_id);

                    $verificationData = [];
                    $verificationData['id'] = $processData->id;
                    $verificationData['status_id'] = $processData->status_id;
                    $verificationData['desk_id'] = $processData->desk_id;
                    $verificationData['user_id'] = $processData->user_id;
                    $verificationData['office_id'] = $processData->office_id;
                    $verificationData['tracking_no'] = $processData->tracking_no;
                    $verificationData['created_by'] = $processData->created_by;
                    $verificationData['locked_by'] = 0;
                    $verificationData = (object)$verificationData;

                    $encryptedString = Encryption::encode(\App\Libraries\UtilFunction::processVerifyData($verificationData));

                    $requestArray = [
                        "application_ids" => [
                            $application_ids
                        ],
                        "status_from" => $status_from,
                        "desk_from" => $desk_from,
                        "process_list_id" => $process_list_id,
                        "data_verification" => $encryptedString,
                        "cat_id" => $cat_id,
                        "desk_id" => $processPath->desk_to,
                        "is_remarks_required" => null,
                        "is_file_required" => null,
                        "status_id" => $client_request['decision_answer'],
                        "remarks" =>  isset($client_request['decision_note'])? $client_request['decision_note']: $processData->remarks,
                        "approval_memo_no_ministry" => isset($client_request['approval_memo_no_ministry'])? $client_request['approval_memo_no_ministry']:'',
                        "approval_date_ministry" => isset($client_request['approval_date_ministry'])? $client_request['approval_date_ministry']:''
                    ];
                    $request = new Request();
                    $request->replace($requestArray);
//                 $requestObject = RequestObject::fromArray($requestArray);
                    $updateProcessData = new ProcessPathController();
                 $result = $updateProcessData->updateProcess($request, $flag = 1);

                 $content = $result->getContent();
                 $contentArray = json_decode($content, true);

                    // d nothi file store
                 $pdf_info = PdfPrintRequestQueue::where('process_type_id', $processData->process_type_id)
                     ->where('app_id', $processData->ref_id)
                     ->latest('created_at')->first();

                 DNothiPdfPrintRequestQueue::firstOrCreate([
                     'process_type_id' => $processData->process_type_id,
                     'app_id' => $processData->ref_id,
                     'pdf_diff' => pdf_diff($client_request['decision_answer']),
                     'process_list_id' => $processData->id,
                     'reg_key' => isset($pdf_info->reg_key)? $pdf_info->reg_key :'',
                     'pdf_type' => isset($pdf_info->pdf_type)? $pdf_info->pdf_type: '',
                     'certificate_name' => isset($pdf_info->certificate_name)? $pdf_info->certificate_name:'',
                     'table_name' => isset($pdf_info->table_name)? $pdf_info->table_name: '',
                     'field_name' => isset($pdf_info->field_name)?$pdf_info->field_name:'',
                     'certificate_link' => isset($pdf_info->certificate_link)? $pdf_info->certificate_link:'',
                     'doc_id' => isset($pdf_info->doc_id)?$pdf_info->doc_id:'',
                     'signatory' => isset($pdf_info->signatory)? $pdf_info->signatory: '',
                 ]);
                    if (!empty($contentArray) && empty(Session::get('error'))) {
                        DB::commit();
                        $response = array(
                            'status' => Response::HTTP_OK,
                            'success' => 'Successfully received',
                            'error' => null,
                        );
                        CommonFunction::DNothiHistoryLog([
                            'request_data' => json_encode($client_request),
                            'response_data' => json_encode($contentArray),
                            'table_name' => isset($pdf_info->table_name)?$pdf_info->table_name:'',
                            'response_code' => $response['status'],
                            'nothi_receipt_no' => $client_request['dak_id'],
                            'response_message' => $response['success'],
                            'process_list_id' => $processData->id,
                            'process_type_id' => $processData->process_type_id,
                            'api_type' => $processData->status_id,
                        ]);
                        return response()->json($response);
                    }else{
                        $response = array( // Response for valid request
                            'status' => Response::HTTP_OK,
                            'errorr' => Session::get('error'),
                        );
                        CommonFunction::DNothiHistoryLog([
                            'request_data' => json_encode($client_request),
                            'response_data' => json_encode($contentArray),
                            'table_name' => isset($pdf_info->table_name)?$pdf_info->table_name:'',
                            'response_code' => $response['status'],
                            'nothi_receipt_no' => $client_request['dak_id'],
                            'response_message' => $response['errorr'],
                            'process_list_id' => $processData->id,
                            'process_type_id' => $processData->process_type_id,
                            'api_type' => $processData->status_id,
                        ]);
                        return response()->json($response);
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
                    Session::flash('error', 'Sorry, something went wrong. ' . CommonFunction::showErrorPublic($e->getMessage()) . '[PPC-1004]');
                    $response = array( // Response for valid request
                        'status' => Response::HTTP_OK,
                        'errors' => $e->getMessage(),
                        'line' => $e->getLine(),
                    );
                 CommonFunction::DNothiHistoryLog([
                     'request_data' => json_encode($client_request),
                     'response_data' => json_encode($response),
                     'response_code' => $response['status'],
                     'nothi_receipt_no' => $client_request['dak_id'],
                     'response_message' => $response['errors'],
                 ]);
                    return response()->json($response);
                }
            }
    }
}
