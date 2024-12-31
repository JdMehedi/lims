<?php

namespace App\Modules\API\Http\Controllers\Enothi;


use App\Models\ENothiSubmissionInfo;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BTRCLIMSAPIController extends ApiController
{

    public function getDocument(Request $request){
        $tokenResponse = $this->apiRequest($request);
        $data = [];
        if(!empty($tokenResponse['status']) && $tokenResponse['status'] == 200 && $tokenResponse['data']['status']){
            /** --need to add functional code-- **/
            return $tokenResponse['data'];
        }
        else{
            return $tokenResponse;
        }
    }
    public function apiRequest(Request $request)
    {
        try {
            $tokenAccess = $this->checkTokenValid($request->header('APIAuthorization'));
            if( !$tokenAccess) {
             //   return $this->apiResponse('Invalid token', HttpResponse::HTTP_UNAUTHORIZED);
                return $this->responseWithError('Invalid token',400,[],40001);
            }
            //$response =  $this->handleRequest($request,$tokenAccess['privilege']);
            $response =  $tokenAccess;
            //return $this->apiResponse($response, 200);
            return $this->responseWithSuccess($response,'Success Token', 200);
        } catch (\Exception $e) {
            Log::error($e->getTraceAsString());
            return $this->apiResponse($this->showErrorMessage($e), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function apiV2Request(Request $request)
    {
        try {
            $tokenAccess = $this->checkAppUserIdValidity($request->input('AppUserID'));
            if(!$tokenAccess) {
                return $this->responseWithError('Invalid token',400,[],40001);
            }
            return  $this->handleV2Request($request);
        } catch (\Exception $e) {
            return $this->apiResponse($this->showErrorMessage($e), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkinfo(){
        echo env('DB_DATABASE');echo '<br/>';
        echo env('DB_OFC_DATABASE');echo '<br/>';
        echo env('DB_DHK_DATABASE');echo '<br/>';
        echo env('DB_CTG_DATABASE');echo '<br/>';
        echo env('DB_RAJ_DATABASE');echo '<br/>';
        echo env('DB_SYL_DATABASE');echo '<br/>';
        exit;
    }

    public function getStatus(Request $request){
        $tokenResponse = $this->apiRequest($request);
        if(!empty($tokenResponse['status']) && $tokenResponse['status'] == 200 && $tokenResponse['data']['status']){
            $processData = ProcessList::where([
                'tracking_no' => $request->get('tracking_no'),
                'ref_id' => $request->get('app_id')
            ])->first();

        if(isset($processData) && !empty($processData)){
            $processData->desk_id = intval($request->get('desk_id'));
            $processData->status_id = intval($request->get('status_id'));
            $processData->save();

            $data = [
                "tracking_no" => $processData->tracking_no,
                "status_id" => $processData->status_id,
                "desk_id" => $processData->desk_id,
            ];

            return $this->responseWithSuccess($data,'Application updated successfully', 200);
        }else{
            return $this->responseWithError('Application not found',400,[],40001);
        }
        }
        else{
            return $tokenResponse;
        }


    }

    public function feedbackUrl(Request $request,  $process_type_id, $app_id, $tracking_no){

        $nothi_id = $request->get('nothi_id');
        $note_id = $request->get('note_id');
        $potro_id = $request->get('potro_id');
        $nothi_action = $request->get('nothi_action');
        $decision_note = $request->get('decision_note');
        $current_desk_id = $request->get('current_desk_id');

        try{
            $enothiInformation = ENothiSubmissionInfo::where([
                'process_type_id' => $process_type_id,
                'app_id' => $app_id,
                'tracking_no' => $tracking_no
            ])->first();
            $enothiInformation->nothi_id = $nothi_id;
            $enothiInformation->note_id = $note_id;
            $enothiInformation->potro_id = $potro_id;
            $enothiInformation->nothi_action = $nothi_action;
            $enothiInformation->decision_note = $decision_note;
            $enothiInformation->fdesk = $current_desk_id;
            $enothiInformation->save();

            //Update process list information
//            $process_list_info = ProcessList::where([
//                'process_type_id' => $process_type_id,
//                'ref_id' => $app_id,
//                'tracking_no' => $tracking_no
//            ])->get();
//
//            $process_list_info->status_id =
//            $process_list_info->desk_id =


            return $this->responseWithSuccess($enothiInformation,'Updated data successfully', 200);
        }catch(\Exception $e){
            return $this->apiResponse($this->showErrorMessage($e), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
