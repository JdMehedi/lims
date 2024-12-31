<?php
namespace App\Modules\ReportsV2\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ReportsV2\Models\ReportRequestList;
use App\Modules\ReportsV2\Models\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\DB;
use yajra\Datatables\Datatables;


class PdfReportControllerV2 extends Controller
{

    public function showCrystalReportData(Request $request){

        $decoded_report_id = Encryption::decodeId($request->get('report_id'));

        $reportRequestList = ReportRequestList::where('report_id', $decoded_report_id)
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'DSC')
            ->get(['pdf_url', 'created_at','search_keys']);

        return Datatables::of(($reportRequestList))
            ->addColumn('pdf_download_link', function ($reportRequestList) {
                if(empty($reportRequestList->pdf_url)){
                    return '-';
                }
                return '<a target="_blank" class="btn btn-danger" href="'.$reportRequestList->pdf_url.'"><i class="fa fa-download"></i></a>';
            })
            ->rawColumns(['pdf_download_link'])
            ->make(true);
    }


    public function generateCrystalReport($request)
    {
        $report_id = (int)$request->report_id;
        $SQL = $request->report_sql;
        $pdfurl = $request->pdfurl;

//
//        $reportRequestExist = ReportRequestList::where('report_id', $report_id)
//            ->where('user_id', Auth::user()->id)
//            ->whereRaw('created_at BETWEEN (NOW() - INTERVAL 15 MINUTE)  AND  NOW()')
//            ->first(['id','created_at']);
//
//        if(isset($reportRequestExist->id) && !empty($reportRequestExist->id)){
//
//            return [
//                'responseCode' => 0,
//                'error' => 'A request already submitted for same report '.$reportRequestExist->created_at->diffForHumans().'. Please try after some time',
//                'error_details' => '',
//            ];
//
//            die();
//        }

        $SQL = Encryption::dataDecode($SQL);
        $json_data = DB::select(DB::raw($SQL));
        $encodedRepRequestId = 0;
        $search_keys = '';
        $responseCode = 0;
        $error = '';
        $msg = '';

        if($json_data){

            $searchKey = isset($request->search_key) ? Encryption::dataDecode($request->search_key) : null;
            $searchKeyArray = explode(',',$searchKey);
            if(count($searchKeyArray) > 2){
                $slicedSearchKeys = array_slice($searchKeyArray, 1, -1);
                $search_keys = implode(',',$slicedSearchKeys);
            }else{
                $search_keys = '';
            }

            $repRequestObj = ReportRequestList::create(
                [
                    'report_id' => $report_id,
                    'search_keys' => $search_keys,
                    'user_id' => Auth::user()->id,
                    'pdf_url' => "",
                    'user_type' => Auth::user()->user_type
                ]
            );


            $data['user_id'] = Auth::user()->id;
            $data['report_request_list_id'] = $repRequestObj->id;
            $data['json_data'] = $json_data;
            $encodedRepRequestId = Encryption::encodeId($repRequestObj->id);
            $responseCode = 1;
            $msg = 'Certificate generation on process!!!';
            $this->requestAPI($report_id, 'new-job',$pdfurl,$data);
        }
        else{
            $responseCode = 0;
            $error = 'There are no data for PDF generation';
        }

        return [
            'responseCode' => $responseCode,
            'msg' => $msg,
            'error' => $error,
            'report_request_id' => $encodedRepRequestId,
            'error_details' => '',
        ];

    }

    public function ajaxApiFeedback(Request $request)
    {
        $report_id = (int)Encryption::decodeId($request->get('report_id')); // doceded report_id is a string, so parse to integer
        $pdfurl = $request->get('pdfurl');
        $encodedReportRequestId = $request->get('report_request_id');
        $repRequestId = (int)Encryption::decodeId($encodedReportRequestId);     //  same here. Otherwise json data in curl can not parse it properly.

        $rdata['user_id'] = Auth::user()->id;
        $rdata['report_request_list_id'] = $repRequestId;

        $response = $this->requestAPI($report_id, 'job-status', $pdfurl, $rdata);

        /*
         * API request for certificate generation will done here
         */

        $data = ['responseCode' => 0, 'data' => '','ref_id' => 0];

        if (isset($response->response)) {

            if ($response->response->status == 0 or $response->response->status == -1) {
                // In-progress
                $data = ['responseCode' => 1, 'data' => 2,'ref_id' => 0];
            } elseif ($response->response->status == 1) {
                $repRequestObj = ReportRequestList::find($repRequestId);
                $repRequestObj->status = $response->response->status;
                $repRequestObj->pdf_url = trim($response->response->download_link);
                $repRequestObj->save(); // update report_request_list -> pdf_url and status column
                $data = ['responseCode' => 1, 'data' => 1,'ref_id' => $encodedReportRequestId];

            } else {
                // Information not eligible!
                $repRequestObj = ReportRequestList::find($repRequestId);
                $repRequestObj->status = $response->response->status;
                $repRequestObj->save(); // update report_request_list -> pdf_url and status column
                $data = ['responseCode' => 1, 'data' => -1,'ref_id' => 0];
            }

        }else{

            $repRequestObj = ReportRequestList::find($repRequestId);
            $repRequestObj->delete(); // delete report_request_list row if response is empty
         }


        return response()->json($data);
    }

    public function updateDownloadPanel(Request $request)
    {
        $ref_id = Encryption::decodeId($request->get('ref_id'));
        $repObj = ReportRequestList::where('id',$ref_id)->first();

        if ($repObj != null) {
            $return = '';
            //$return .= '<button type="button" id="crystal_gen_btn" reportsql="'.$reportsql.'"  report_id="'.Encryption::encodeId($repObj->id).'" class="btn btn-primary pull-left">Generate Report</button>';
            $return .= '<button class="btn btn-success" id="crystal_report_generate" type="submit">Generate Report</button>';
            $responseCode = 1;
        } else {
            $responseCode = 0;
            $return = '';
        }
        $data = ['responseCode' => $responseCode, 'data' => $return];
        return response()->json($data);
    }


    private function requestAPI($app_id, $action = '',$pdfurl = '',$rData=array())
    {
        $reportObj = Reports::where('report_id',$app_id)->first([
            'report_id',
            'res_key',
            'pdf_type',
        ]);

        if($reportObj == null){
            return false;
        }


        $pdf_type = $reportObj->pdf_type;
        $reg_key = $reportObj->res_key;

        $data = array();
        $json_data = array();

        if ($action == "new-job") {
            $json_data = $rData['json_data'];
        }


        $data['data'] = array(
            'reg_key' => $reg_key,       // Authentication key
            'pdf_type' => $pdf_type,     // letter type
            'ref_id' => $app_id,         //app_id
            'json' => $json_data,         //Json Data
            'param' => array(
                'id' => $app_id,  // app_id
                'user_id' => $rData['user_id'],  // app_id
                'report_request_list_id' => $rData['report_request_list_id']
            )
        );


        $data1 = urlencode(json_encode($data));
        if ($action == "job-status") {
            $url = "{$pdfurl}api/job-status";
        } else if ($action == "new-job") {
            $url = "{$pdfurl}api/new-job";
        } else {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "requestData=" . $data1);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $response = '';
        } else {
            curl_close($ch);
        }
        $dataResponse = json_decode($response);
        return $dataResponse;
    }

    

    private function XrequestAPI($app_id, $action = '',$pdfurl = '',$SQL='')
    {
        $reportObj = Reports::where('report_id',$app_id)->first([
            'report_id',
            'res_key',
            'pdf_type',
        ]);

        if($reportObj == null){
            return false;
        }



        $pdf_type = $reportObj->pdf_type;
        $reg_key = $reportObj->res_key;

        $data = array();
        $json_data = array();

        if($action == "new-job")
        {
            $json_data = DB::select(DB::raw($SQL));
        }

        $data['data'] = array(
            'reg_key' => $reg_key,       // Authentication key
            'pdf_type' => $pdf_type,     // letter type
            'ref_id' => $app_id,         //app_id
            'json' => $json_data,         //Json Data
            'param' => array(
                'id' => $app_id,  // app_id
            )
        );
        $data1 = urlencode(json_encode($data));


        $url = '';
        if ($action == "job-status") {
            $url = "{$pdfurl}api/job-status?requestData=$data1";
        } else if ($action == "new-job") {
            $url = "{$pdfurl}api/new-job?requestData=$data1";

        } else {
            return false;
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $response = '';
        } else {
            curl_close($ch);
        }
        $dataResponse = json_decode($response);
        return $dataResponse;
    }
}