<?php

namespace App\Modules\ProcessPath\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Dashboard\Models\DynamicPayment;
use App\Modules\Dashboard\Models\SpecialServiceAmmendment;
use App\Modules\Dashboard\Models\SpecialServiceIssue;
use App\Modules\Dashboard\Models\SpecialServiceMaster;
use App\Modules\Dashboard\Models\SpecialServiceRenew;
use App\Modules\Dashboard\Models\SpecialServiceSurrender;
use App\Modules\ProcessPath\Models\HelpText;
use App\Modules\ProcessPath\Models\ProcessDoc;
use App\Modules\ProcessPath\Models\ProcessFavoriteList;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessPath;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Http\Controllers\ReuseController;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\CallCenterRenew;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenew;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\ShadowFile;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Datatables;
use App\Modules\ProcessPath\Models\PayOrderPayment;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrender;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\Settings\Models\DynamicProcess;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServiceInfo;
use App\Modules\Users\Models\Countries;
use Illuminate\Support\Facades\Log;

class ProcessPathController extends Controller
{
    public $processPathTable = 'process_path';
    public $deskTable = 'user_desk';
    public $processStatus = 'process_status';
    public $processType = 'process_type';
    public $shortFallId = '5,6';
    protected $aclName;

    public function __construct()
    {
        $this->aclName = 'processPath';
    }

    public function processListById(Request $request,$form_url = '', $id = '', $processStatus = '')
    {
        //dd($form_url, $id, $processStatus);
        if($request->segments()[0] === 'client'){
            $id = isset($request->segments()[3]) ? $request->segments()[3] : '';
        }else{
            $id = isset($request->segments()[2]) ? $request->segments()[2] : '';
        }
         //process_type_id
        $userType = Auth::user()->user_type;
        if (CommonFunction::checkEligibility() != 1 and $userType == '5x505') {
            Session::flash('error', 'You are not eligible for apply ! [PPC-1042]');
            return redirect('dashboard');
        }

        try {
            if ($userType == '4x404') {
                Session::forget('is_delegation');
                Session::forget('batch_process_id');
                Session::forget('is_batch_update');
                Session::forget('single_process_id_encrypt');
                Session::forget('next_app_info');
                Session::forget('total_selected_app');
                Session::forget('total_process_app');
            }
            //end
            $process_type_id = $id != '' ? Encryption::decodeId($id) : 0;
//            dd($process_type_id);
            if (!session()->has('active_process_list')) {
                session()->put('active_process_list', $process_type_id);
            }


//            $ProcessType = ProcessType::select(DB::raw("CONCAT(name_bn,' ',group_name) AS name"),'id')
            $ProcessType = ProcessType::select(DB::raw("CONCAT(name) AS name"),'id')
                ->whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
            $process_info = ProcessType::where('id', $process_type_id)->first(['id', 'acl_name', 'form_url', 'name','group_name', 'drop_down_label','is_special']);
            $processStatus = null;
            $status = ['' => 'Select one'] + ProcessStatus::where('process_type_id', $process_type_id != 0 ? $process_type_id : -1) // -1 means this service not available
                ->where('id', '!=', -1)
                ->where('status', 1)
                ->orderBy('status_name', 'ASC')
                ->pluck('status_name', 'id')->toArray();
            $searchTimeLine = [
                '' => 'select One',
                '1' => '1 Day',
                '7' => '1 Week',
                '15' => '2 Weeks',
                '30' => '1 Month',
            ];
            $aclName = $this->aclName;

            // Global search or dashboar Application list
            $search_by_keyword = '';
            if ($request->isMethod('post')) {
                $search_by_keyword = $request->get('search_by_keyword');
            }


            $number_of_rows = Configuration::where('caption', 'PROCESS_ROW_NUMBER')->value('value');
            $status_wise_apps = null;
            if ($userType == "1x101" || $userType == "10x101" || $userType == "4x404") {
                $status_wise_apps = ProcessList::statuswiseAppInDesks($process_type_id);
            }
            $guideline_config_text = Configuration::where('caption', 'READ_GUIDELINE')->value('value');
            return view("ProcessPath::common-list", compact(
                'status',
                'ProcessType',
                'processStatus',
                'searchTimeLine',
                'process_type_id',
                'process_info',
                'aclName',
                'search_by_keyword',
                'status_wise_apps',
                'number_of_rows',
                'guideline_config_text'
            ));
        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1044]');
            return redirect()->back();
        }
    }

    public function setProcessType(Request $request)  //The function not use for now. ajax set process type
    {
        session()->put('active_process_list', $request->get('data'));
        return 'success';
    }

    public function searchProcessType(Request $request)  //ajax get process type
    {
        $process_type_id = $request->get('data');
        $status =  ProcessStatus::where('process_type_id', $process_type_id != 0 ? $process_type_id : -1) // -1 means this service not available
            ->whereNotIn('id', [-1, 3])
            ->orderBy('status_name')
            ->select(DB::raw("CONCAT(id,' ') AS id"), 'status_name')
            ->pluck('status_name', 'id')->all();

        $data = ['responseCode' => 1, 'data' => $status];
        return response()->json($data);
    }

    public function getList(Request $request, $status = '', $desk = '')
    {
        try {
            //        $process_type_id = session('active_process_list');
            $process_type_id = $request->get('process_type_id'); //new process type get by javascript

            $status == '-1000' ? '' : $status;
            $get_user_desk_ids = CommonFunction::getUserDeskIds();

            $prefix = '';
            if (CommonFunction::getUserType() == '5x505') {
                $prefix = 'client';
            }

            $user_id = CommonFunction::getUserId();
            $list = ProcessList::getApplicationList($process_type_id, $status, $request, $desk);

            /*
             * If search option has only one result then open the application
             */
            if ($request->filled('process_search')) {
                $list_count = $list->get()->count();
                if ($list_count == 1) {
                    $single_data = $list->get();

                    return response()->json([
                        'responseType' => 'single',
                        'url' => url('' . $prefix . '/process/' . $single_data[0]->form_url . '/view/' . Encryption::encodeId($single_data[0]->ref_id) . '/' . Encryption::encodeId($single_data[0]->process_type_id))
                    ]);
                }
            }

            $class = $this->batchUpdateClass($request, $desk);
            return Datatables::of($list)
                ->addColumn('action', function ($list) use ($status, $request, $prefix, $desk, $class) {

                    if (
                        $list->locked_by > 0
                        && Carbon::createFromFormat('Y-m-d H:i:s', $list->locked_at)->diffInMinutes() < 3 and $list->locked_by != Auth::user()->id
                    ) {
                        $locked_by_user = Users::where('id', $list->locked_by)
                            ->select(DB::raw("CONCAT_WS(' ', users.user_first_name, users.user_middle_name, users.user_last_name) as user_name"))
                            ->value('user_name');
                        $html = '<img width="20" src="' . url('/assets/images/Lock-icon_2.png') . '"/>' .
                            '<a onclick="return confirm(' . "'The record locked by $locked_by_user, would you like to force unlock?'" . ')"
                            target="_blank" href="' . url('process/' . $list->form_url . '/view/' . Encryption::encodeId($list->ref_id) . '/' . Encryption::encodeId($list->process_type_id)) . '"
                            class="btn btn-xs btn-primary"> Open</a> &nbsp;';
                    } else {
                        if (in_array($list->status_id, [-1, 5]) && $list->created_by == Auth::user()->id) {
                            $html = '<a class="subSectorEditBtn btn btn-xs btn-info"  href="' . url('' . $prefix . '/process/' . $list->form_url . '/edit/' . Encryption::encodeId($list->ref_id) . '/' . Encryption::encodeId($list->process_type_id)) . '" class="btn btn-xs btn-success button-color ' . $class['button_class'] . ' " style="color: white"> <i class="fa fa-edit"></i> Edit</a><br>';
                        } else {
                            $html = '<a class="subSectorEditBtn btn btn-xs btn-info"  href="' . url('' . $prefix . '/process/' . $list->form_url . '/view/' . Encryption::encodeId($list->ref_id) . '/' . Encryption::encodeId($list->process_type_id)) . '" class="btn btn-xs btn-primary button-color ' .  $class['button_class'] . ' " style="color: white"> <i class="fa fa-folder-open"></i> Open</a><br>';
                        }
                    }

                    $html .= '<input type="hidden" class="' . $class['input_class'] . '" name="batch_input"  value=' . Encryption::encodeId($list->id) . '>';
                    return $html;
                })
                ->editColumn('tracking_no', function ($list) use ($desk, $request, $class) {
                    $existingFavoriteItem = CommonFunction::checkFavoriteItem($list->id);
                    $htm = '';
                    if ($existingFavoriteItem > 0) {
                        $htm .= '<i style="cursor: pointer;color:#f0ad4e" class="fas fa-star remove_favorite_process" title="Added to your favorite list. Click to remove." id=' . Encryption::encodeId($list->id) . '></i> ' . $list->tracking_no;
                    } else {
                        $htm .= '<i style="cursor: pointer" class="far fa-star favorite_process"  title="Add to your favorite list" id=' . Encryption::encodeId($list->id) . '></i> ' . $list->tracking_no;
                    }
                    return $htm;
                })
                ->editColumn('json_object', function ($list) {
                    if(!empty($list->json_object)){
                        return getDataFromJson($list->json_object);
                    }
                })
                 ->editColumn('license_json', function ($list) {
                     if(!empty($list->license_json)){
                         return getDataFromJson($list->license_json);
                     }else{
                         return "";
                     }
                 })
                ->editColumn('process_status.status_name', function ($list) {
                    return $list->status_name;
                })
                ->editColumn('user_desk.desk_name', function ($list) {
                    return $list->desk_id == 0 ? 'Applicant' : $list->desk_name;
                })
                ->editColumn('updated_at', function ($list) {
                    return CommonFunction::updatedOn($list->updated_at);
                })
                ->removeColumn('id', 'ref_id', 'process_type_id', 'updated_by', 'closed_by', 'created_by', 'updated_by', 'desk_id', 'status_id', 'locked_by', 'ref_fields')
                ->rawColumns(['tracking_no', 'action'])
                ->setRowAttr([
                    'style' => function ($list) {
                        $color = '';
                        if ($list->priority == 1) {
                            $color .= '';
                        } elseif ($list->priority == 2) {
                            $color .= '    background: -webkit-linear-gradient(left, rgba(220,251,199,1) 0%, rgba(220,251,199,1) 80%, rgba(255,255,255,1) 100%);';
                        } elseif ($list->priority == 3) {
                            $color .= '    background: -webkit-linear-gradient(left, rgba(255,251,199,1) 0%, rgba(255,251,199,1) 40%, rgba(255,251,199,1) 80%, rgba(255,255,255,1) 100%);';
                        }
                        return $color;
                    },
                    'class' => function ($list) use ($get_user_desk_ids, $user_id) {
                        if (!in_array($list->status_id, [-1, 5, 6, 25]) && $list->read_status == 0 && in_array($list->desk_id, $get_user_desk_ids)) {
                            return 'unreadMessage';
                        } elseif (in_array($list->status_id, [5, 6, 25]) && $list->read_status == 0 && $list->created_by == $user_id) {
                            return 'unreadMessage';
                        }
                    }
                ])
                ->make(true);

        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1041]');
            return redirect()->back();
        }
    }

    public function getDeskByStatus(Request $request)
    {
        try {
            $process_list_id = Encryption::decodeId($request->get('process_list_id'));
            $status_from = Encryption::decodeId($request->get('status_from'));
            $cat_id = Encryption::decodeId($request->get('cat_id'));
            $statusId = trim($request->get('statusId'));

            $processInfo = ProcessList::where('id', $process_list_id)->first([
                'process_type_id', 'desk_id', 'ref_id'
            ]);

            $get_desk_list_query = "SELECT DGN.id, DGN.desk_name
                        FROM user_desk DGN WHERE
                        find_in_set(DGN.id,
                        (SELECT desk_to FROM process_path APP
                         where APP.desk_from LIKE '%$processInfo->desk_id%'
                            AND APP.status_from = '$status_from'
                            AND APP.cat_id = '$cat_id'
                            AND APP.process_type_id = '$processInfo->process_type_id'
                            AND APP.status_to REGEXP '^([0-9]*[,]+)*$statusId([,]+[,0-9]*)*$')) ";


            $deskList = \DB::select(DB::raw($get_desk_list_query));

            $get_process_path_query = "SELECT APP.id, APP.ext_sql, APP.file_attachment,APP.remarks
                            FROM process_path APP
                            WHERE APP.desk_from LIKE '%$processInfo->desk_id%'
                            AND APP.status_from = '$status_from'
                            AND APP.process_type_id = '$processInfo->process_type_id'
                            AND APP.cat_id = '$cat_id'
                            AND APP.status_to REGEXP '^([0-9]*[,]+)*$statusId([,]+[,0-9]*)*$' limit 1";
            $process_path_info = \DB::select(DB::raw($get_process_path_query));

            // extra sql code here
            if ($process_path_info && $process_path_info[0]->ext_sql != "NULL" && $process_path_info[0]->ext_sql != "") { // ext_sql not null
                $fullSql = $process_path_info[0]->ext_sql . $processInfo->ref_id; // concat app id
                $ext_sql_desk_list = \DB::select(DB::raw($fullSql));

                if ($ext_sql_desk_list[0]->returnStatus == 1) { // type_of_company = 1 and pr_cer_uplodead = no
                    $deskList = $ext_sql_desk_list; // assign new desk list from new query
                    if ($deskList[0]->id == null) { // desk = null or no desk
                        $deskList = [];
                    }
                } elseif ($ext_sql_desk_list[0]->returnStatus == -100) { // continue the previous query
                    $deskList = $deskList;
                }
            }
            // End extra sql code here


            // Generate desk list
            $final_desk_list = array();
            foreach ($deskList as $k => $v) {
                $tmpDeskId = $v->id;
                $final_desk_list[$tmpDeskId] = $v->desk_name;
            }

            // End Generate desk list

            // Send PIN number for final status
            $pinNumber = '';
            $processTypeFinalStatus = ProcessType::where('id', $processInfo->process_type_id)->first(['final_status']);
            $finalStatus = explode(",", $processTypeFinalStatus->final_status);
//            if (in_array($statusId, $finalStatus)) {  //checking final status
//                $result = CommonFunction::requestPinNumber($processInfo->ref_id, $processInfo->process_type_id);
//                if ($result == true)
//                    $pinNumber = 1;
//            }
            // End Send PIN number for final status

            // Get Add-on form if have any
            $add_on_form = $this->requestFormContent($statusId, $processInfo->process_type_id, $processInfo->ref_id);
            // End Get Add-on form if have any

            $data = [
                'responseCode' => 1,
                'data' => $final_desk_list,
                'html' => $add_on_form,
                'remarks' => $process_path_info[0]->remarks ?? '',
                'file_attachment' => $process_path_info[0]->file_attachment ?? '',
                'pin_number' => $pinNumber
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1040]');
            return redirect()->back();
        }
    }

    protected function getUserByDesk(Request $request)
    {
        try {
            $desk_to = trim($request->get('desk_to'));
            $statusId = trim($request->get('statusId'));
            $cat_id = Encryption::decodeId($request->get('cat_id'));
            $status_from = Encryption::decodeId($request->get('status_from'));
            $desk_from = Encryption::decodeId($request->get('desk_from'));
            $process_type_id = Encryption::decodeId($request->get('process_type_id'));
            $office_id = Encryption::decodeId($request->get('office_id'));

            $get_process_path_sql = "SELECT APP.id, APP.ext_sql, APP.ext_sql2
            FROM process_path APP WHERE APP.desk_from = '$desk_from'
            AND APP.status_from = '$status_from'
            AND APP.cat_id = '$cat_id'
            AND APP.process_type_id = '$process_type_id'
            AND APP.status_to LIKE '%$statusId%' limit 1";

            $get_process_path = \DB::select(DB::raw($get_process_path_sql));

            if ($get_process_path[0]->ext_sql2 != null) { // ext_sql two not null

                $get_user_list_query = str_replace("{desk_to}", "$desk_to", $get_process_path[0]->ext_sql2);
            } else {

                $get_user_list_query = "SELECT id as user_id, concat_ws(' ', user_first_name, user_middle_name, user_last_name) as user_full_name
                from users
                WHERE is_approved = 1
                AND user_status='active'
                AND desk_id REGEXP '^([0-9]*[,]+)*$desk_to([,]+[,0-9]*)*$'
                AND office_ids REGEXP '^([0-9]*[,]+)*$office_id([,]+[,0-9]*)*$'";
            }
            $user_list = DB::select(DB::raw($get_user_list_query));
            $data = ['responseCode' => 1, 'data' => $user_list];
            return response()->json($data);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1036]');
            return redirect()->back();
        }
    }

    public function getAppInfoBySingleApplication($process_type_id, $ref_id){
        if($process_type_id == 1){
            $query = ISPLicenseIssue::query();
        }elseif($process_type_id == 2){
            $query = ISPLicenseRenew::query();
        }
        $appInfo = $query->where('id', $ref_id)
            ->first([
                'approval_memo_no_ministry',
                'approval_date_ministry',
            ]);
        return $appInfo;
    }

    public function requestFormContent($CurrentStatusId, $process_type_id, $ref_id)
    {
        try {
            $form_id = ProcessStatus::where('process_type_id', $process_type_id)->where('id', $CurrentStatusId)->value('form_id');


            if ($form_id == 'AddOnForm/desk_from') {
                $appInfo = ProcessList::leftJoin('space_allocation as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->where('process_list.ref_id', $ref_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->first([
                        'process_list.company_id',
                        'process_list.desk_id',
                        'process_list.office_id',
                        'process_list.tracking_no',
                        'process_list.status_id',
                        'process_list.locked_by',
                        'process_list.locked_at',
                        'apps.*',
                    ]);
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'appInfo')));
            } elseif ($form_id == 'AddOnForm/general-desk-from') {
                $appInfo = ProcessList::leftJoin('ga_master as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->where('process_list.ref_id', $ref_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->first([
                        'process_list.company_id',
                        'process_list.desk_id',
                        'process_list.office_id',
                        'process_list.tracking_no',
                        'process_list.status_id',
                        'process_list.locked_by',
                        'process_list.locked_at',
                        'apps.service_name',
                    ]);

                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'appInfo')));
            } elseif ($form_id == 'AddOnForm/process-certificate-form') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'appInfo')));
            } elseif ($form_id == 'AddOnForm/forward_attach_call_center_issue') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            }  elseif ($form_id == 'AddOnForm/forward_attachV2') {
                $payment_info = SonaliPayment::where(['app_id' => $ref_id, 'process_type_id' => $process_type_id])->first();
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'payment_info')));
            }  elseif ($form_id == 'AddOnForm/pay_order_issue') {
                $payment_info = SonaliPayment::where(['app_id' => $ref_id, 'payment_step_id' => 1, 'process_type_id' => $process_type_id])->first();
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'payment_info')));
            } elseif ($form_id == 'AddOnForm/pay_order_issue_radio') {
                $payment_info = SonaliPayment::where(['app_id' => $ref_id, 'payment_step_id' => 1, 'process_type_id' => $process_type_id])->first();
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'payment_info')));
            } elseif ($form_id == 'AddOnForm/pay_order_status_view') {
                $payment_info = SonaliPayment::where(['app_id' => $ref_id, 'payment_step_id' => 1, 'process_type_id' => $process_type_id])->first();
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'payment_info')));
            } elseif ($form_id == 'AddOnForm/forward_att_and_pay_order_cc_issue') {
                $payment_info = SonaliPayment::where(['app_id' => $ref_id, 'payment_step_id' => 1, 'process_type_id' => $process_type_id])->first();
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'payment_info')));
            }  elseif ($form_id == 'AddOnForm/forward_attach') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            }  elseif ($form_id == 'AddOnForm/forward_attach_isp_amendment') {
                $payment_info = SonaliPayment::where(['app_id' => $ref_id, 'payment_step_id' => 1, 'process_type_id' => $process_type_id])->first();
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'payment_info')));
            } elseif ($form_id == 'AddOnForm/forward_attach_nix_issue') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            } elseif ($form_id == 'AddOnForm/forward_attach_vsat_renew') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            } elseif ($form_id == 'AddOnForm/forward_attach_tvas_issue') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            }  elseif ($form_id == 'AddOnForm/forward_attach_tvas_renew') {
                $payment_info = SonaliPayment::where(['app_id' => $ref_id,'payment_step_id' => 1, 'process_type_id' => $process_type_id])->first();
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id','payment_info')));
            } elseif ($form_id == 'AddOnForm/forward_attach_vsat_issue') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            } elseif ($form_id == 'AddOnForm/forward_attach_iptsp_issue') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            } elseif ($form_id == 'AddOnForm/shortfall_reason_text') {
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
            } elseif ($form_id == 'AddOnForm/approval_memo_for_payment_request') {
                $appInfo = $this->getAppInfoBySingleApplication($process_type_id, $ref_id);
                $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id', 'appInfo')));
            }elseif ($CurrentStatusId== 5) { //shortfall
                $type=ProcessType::find($process_type_id);

                if($type->is_special == 1){
                    $form_id = 'AddOnForm/shortfall_reason_text';
                    $public_html = strval(view("ProcessPath::{$form_id}", compact('form_id', 'process_type_id')));
                }else{
                    $public_html = '';
                }


            } else {
                $public_html = '';
            }
            return $public_html;
        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1032]');
            return redirect()->back();
        }
    }

    /*
     * Application Processing
     */
    public function updateProcess(Request $request, $flag= 0)
    {
        $rules = [
            'status_id' => 'required',
        ];
        if ($request->get('is_remarks_required') == 1) {
            $rules['remarks'] = 'required';
        }
        if ($request->get('is_file_required') == 1) {
            $rules['attach_file'] = 'requiredarray';
        }

        //        if ($request->has('is_user')) {
        //            $rules['is_user'] = 'required|numeric';
        //        }

//        if ($request->has('pin_number')) {
//            if ($request->get('pin_number') == '') {
//                \Session::flash('error', "Pin number Field Is Required");
//                return redirect()->back();
//            }
//        }
        $customMessages = [
            'status_id.required' => 'Apply Status Field Is Required',
            'remarks.required' => 'Remarks Field Is Required',
            'attach_file.requiredarray' => 'Attach File Field Is Required',
        ];
        $this->validate($request, $rules, $customMessages);

        try {

            // if isset Application processing PIN number, then match the PIN
//            if ($request->has('pin_number')) {
//                $security_code = trim($request->get('pin_number'));
//                $user_id = CommonFunction::getUserId();
//                $count = Users::where('id', $user_id)->where(['pin_number' => $security_code])->count();
//                if ($count <= 0) {
//                    \Session::flash('error', "Security Code doesn't match. [PPC-1030]");
//                    return redirect()->back();
//                }
//            }
            // End if isset Application processing PIN number, then match the PIN
            // Any action from d-nothi $flag will be 1 because we have not d-nothi locked_by data
            if($flag == 0){
                if (empty(Auth::user()->signature_encode)) {
                    \Session::flash('error', "Please upload your signature before application processing. [PPC-1026]");
                    return redirect()->back();
                }
            }

            DB::beginTransaction();


            $process_list_id = Encryption::decodeId($request->get('process_list_id'));
            $cat_id = Encryption::decodeId($request->get('cat_id'));
            $statusID = trim($request->get('status_id'));
            $deskID = (empty($request->get('desk_id')) ? 0 : trim($request->get('desk_id')));

            $existProcessInfo = ProcessList::where('id', $process_list_id)
                ->first([
                    'id',
                    'ref_id',
                    'tracking_no',
                    'office_id',
                    'process_type_id',
                    'status_id',
                    'desk_id',
                    'hash_value',
                    'user_id',
                    'created_by',
                    'locked_by'
                ]);

            /*
             * Verify Process Path
             * Check whether the application's process_type_id and cat_id and status_from
             * and desk_from and desk_to and status_to are equal with any of one row from process_path table
             */

            if($flag == 0){
                $process_path_count = DB::select(DB::raw("select count(*) as procss_path from process_path
                                        where process_type_id = $existProcessInfo->process_type_id
                                        AND cat_id = $cat_id
                                        AND status_from = $existProcessInfo->status_id
                                        AND desk_from = $existProcessInfo->desk_id
                                        AND desk_to = $deskID
                                        AND status_to REGEXP '^([0-9]*[,]+)*$statusID([,]+[,0-9]*)*$'"));
                if ($process_path_count[0]->procss_path == 0) {
                    Session::flash('error', 'Sorry, invalid process request.[PPC-1002]');
                    $process_type = ProcessType::find($existProcessInfo->process_type_id);
                    if($process_type->is_special){
                        return redirect('special_service/service-list/'.Encryption::encodeId($existProcessInfo->process_type_id));
                    }else{
                        return redirect('process/list/' . Encryption::encodeId($existProcessInfo->process_type_id));
                    }

                }
            }
            /*
             * End Verify Process Path
             */


            // Desk user identify checking
            $user_id = 0;
            if (!empty($request->get('is_user'))) {
                $user_id = trim($request->get('is_user'));
                $findUser = Users::where('id', $user_id)->first();
                if (empty($findUser)) {
                    \Session::flash('error', 'Desk user not found!.[PPC-1019]');
                    return Redirect::back()->withInput();
                }
            }

            // End Desk user identify checking

            // Process data verification, if verification is true then proceed for Processing
            $verificationData = [];
            $verificationData['id'] = $existProcessInfo->id;
            $verificationData['status_id'] = $existProcessInfo->status_id;
            $verificationData['desk_id'] = $existProcessInfo->desk_id;
            $verificationData['user_id'] = $existProcessInfo->user_id;
            $verificationData['office_id'] = $existProcessInfo->office_id;
            $verificationData['tracking_no'] = $existProcessInfo->tracking_no;
            $verificationData['created_by'] = $existProcessInfo->created_by;
            // Any action from d-nothi $flag will be 1 because we have not d-nothi locked_by data
            if($flag == 1){
                $verificationData['locked_by'] = 0;
            }else{
                $verificationData['locked_by'] = $existProcessInfo->locked_by;
            }
            $verificationData = (object)$verificationData;

            if (Encryption::decode($request->data_verification) == \App\Libraries\UtilFunction::processVerifyData($verificationData)) {
                // On Behalf of desk id
                $on_behalf_of_user = 0;
                if($flag == 0){
                    $my_desk_ids = CommonFunction::getUserDeskIds();
                    if (!in_array($existProcessInfo->desk_id, $my_desk_ids)) {
                        $on_behalf_of_user = Encryption::decodeId($request->get('on_behalf_user_id'));
                    }
                }

                // Process attachment store
                if ($request->hasFile('attach_file')) {
                    $attach_file = $request->file('attach_file');
                    foreach ($attach_file as $afile) {
                        $original_file = $afile->getClientOriginalName();
                        $afile->move('uploads/', time() . $original_file);
                        $file = new ProcessDoc;
                        $file->process_type_id = $existProcessInfo->process_type_id;
                        $file->ref_id = $process_list_id;
                        $file->desk_id = $request->get('desk_id');
                        $file->status_id = $request->get('status_id');
                        $file->file = 'uploads/' . time() . $original_file;
                        $file->save();
                    }
                }
                // End Process attachment store
                // Updating process list
                $status_from = $existProcessInfo->status_id;
                $deskFrom = $existProcessInfo->desk_id;

                if (empty($deskID)) {
                    $whereCond = "select * from process_path
                                where process_type_id='$existProcessInfo->process_type_id'
                                AND status_from = '$status_from'
                                AND desk_from = '$deskFrom'
                                AND status_to REGEXP '^([0-9]*[,]+)*$statusID([,]+[,0-9]*)*$'";
                    $processPath = DB::select(DB::raw($whereCond));

                    $deskList = null;
                    // if ext_sql not null
                    if (count($processPath) > 0 && $processPath[0]->ext_sql1 != "NULL" && $processPath[0]->ext_sql1 != "") {
                        $fullSql = $processPath[0]->ext_sql . $existProcessInfo->ref_id; // concat app id
                        $ext_sql_desk_list = \DB::select(DB::raw($fullSql));
                        if ($ext_sql_desk_list[0]->returnStatus == 1) {
                            $deskList = $ext_sql_desk_list; // assign new desk list from new query
                        }
                    }
                    if (!empty($deskList[0]->deskIsnull) && $deskList[0]->deskIsnull != -100) {
                        $deskID = $deskList[0]->deskIsnull;
                    } else {
                        if($request->get('status_id')==5 || $request->get('status_id')==15){
                                if($flag == 1){
                                    $nothiUserId = Users::where('id','2014')->first()->id;
                                    $user_id = $nothiUserId;
                                }else{
                                $user_id= Auth::user()->id;
                                }
                            }
                         else {
                            $user_id = 0;
                         }
                        $deskID = 0;

                        if (count($processPath) > 0 && $processPath[0]->desk_to == '0')  // Sent to Applicant
                            $deskID = 0;
                        if (count($processPath) > 0 && $processPath[0]->desk_to == '-1') {  // Keep in same desk
                            $deskID = $deskFrom;
                            $user_id = CommonFunction::getUserId(); //user wise application assign
                        }
                    }
                }
                // Process data for modification
                $processData['desk_id'] = $deskID;
                $processData['status_id'] = $statusID;
                // Forward to desk officer user id have to store cause after resubmit data he will be responsible to process
                if($deskID == 5 && $statusID == 47){
                $processData['shortfall_resubmit_to'] = CommonFunction::getUserId();
                }
                $processData['process_desc'] = $request->get('remarks');
                $processData['user_id'] = $user_id;
                $processData['on_behalf_of_user'] = $on_behalf_of_user;
                if($flag == 1){
                    $processData['updated_by'] = 0;
                }else{
                    $processData['updated_by'] = Auth::user()->id;
                }

                $processData['locked_by'] = 0;
                $processData['locked_at'] = null;
                $processData['read_status'] = 0;
                $processTypeFinalStatus = ProcessType::where('id', $existProcessInfo->process_type_id)->first(['final_status']);
                $finalStatus = explode(",", $processTypeFinalStatus->final_status);
                $closed_by = 0;
                // final status for certificate approve 25 and 65(isp issue, renew)
                if (in_array($statusID, $finalStatus)) {
                    if($flag == 0){
                    $closed_by = CommonFunction::getUserId();
                    }else{
                        $closed_by = 0;
                    }
                }
                $processData['closed_by'] = $closed_by;

                /*
                 * Process Hash value generate
                 */

                $resultData = $existProcessInfo->id . '-' . $existProcessInfo->tracking_no .
                    $deskID . '-' . $statusID . '-' . $processData['user_id'] . '-' .
                    $processData['updated_by'];

                $processData['previous_hash'] = $existProcessInfo->hash_value;
                $processData['hash_value'] = Encryption::encode($resultData);
                /*
                 * End Process Hash value generate
                 */
                ProcessList::where('id', $existProcessInfo->id)->update($processData);

                /*
                 * process type wise, process status wise additional info update
                 * application certificate generation, email or sms sending function,
                 * During the processing of the application, the data provided by the desk user in the add-on form is given
                 * CertificateMailOtherData() comes from app\Modules\ProcessPath\helper.php
                 */
                $result = CertificateMailOtherData($existProcessInfo->id, $statusID, $existProcessInfo->desk_id, $request->all(), $flag);
                if ($result == false) {
                    DB::rollback();
                    Session::flash('error', 'Sorry! Something is Wrong. [PPC-1003]');
                    return Redirect::back()->withInput();
                }


                DB::commit();
                // new code for batch update
                if (isset($request->is_batch_update)) {
                    $batch_process_id = Session::get('batch_process_id');

                    $single_process_id_encrypt_next = null;
                    $single_process_id_encrypt_second_next_key = null;
                    $find_current_key = array_search($request->get('single_process_id_encrypt'), $batch_process_id); //find current key
                    $keys = array_keys($batch_process_id); //total key
                    $nextKey = isset($keys[array_search($find_current_key, $keys) + 1]) ? $keys[array_search($find_current_key, $keys) + 1] : ''; //next key
                    $second_nextKey = isset($keys[array_search($find_current_key, $keys) + 2]) ? $keys[array_search($find_current_key, $keys) + 2] : ''; //second next key

                    if (!empty($nextKey)) {
                        $single_process_id_encrypt_next = $batch_process_id[$nextKey]; //next process id
                    }
                    if (!empty($second_nextKey)) {
                        $single_process_id_encrypt_second_next_key = $batch_process_id[$second_nextKey]; //next second process id
                    }

                    if (empty($single_process_id_encrypt_next)) {
                        \Session::flash('success', 'Process has been updated successfully.');
                        $process_type = ProcessType::find($existProcessInfo->process_type_id);
                        if($process_type->is_special){
                            return redirect('special_service/service-list/'.Encryption::encodeId($existProcessInfo->process_type_id));
                        }else{
                            return redirect('process/list/' . Encryption::encodeId($existProcessInfo->process_type_id));
                        }

                    }

                    Session::put('single_process_id_encrypt', $single_process_id_encrypt_next);
                    $nextAppInfo = 'null';
                    if ($single_process_id_encrypt_second_next_key != null) {
                        $nextAppInfo = ProcessList::where('process_list.id', Encryption::decodeId($single_process_id_encrypt_second_next_key))->first(['tracking_no'])->tracking_no;
                    }
                    Session::put('next_app_info', $nextAppInfo);
                    $get_total_process_app = Session::get('total_process_app');
                    Session::put('total_process_app', $get_total_process_app + 1);

                    $processData = ProcessList::leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                        ->where('process_list.id', Encryption::decodeId($single_process_id_encrypt_next))->first(['process_type.form_url', 'process_list.ref_id', 'process_list.process_type_id']);
                    \Session::flash('success', 'Process has been updated successfully.');
                    $redirectUrl = 'process/' . $processData->form_url . '/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id);
                    return redirect($redirectUrl);
                }
                //end


            } else {
                dd('Sorry, Process data verification failed. [PPC-1003, updateprocessefsf]');
                \Session::flash('error', 'Sorry, Process data verification failed. [PPC-1003]');
            }
            $process_type = ProcessType::find($existProcessInfo->process_type_id);
            $type = $process_type->type;
            if($process_type->is_special){
                if($type==1){
                    $table = 'special_license_issue';
                    $appData     = SpecialServiceIssue::find($existProcessInfo->ref_id);
                }elseif($type==2){
                    $table = 'special_license_renew';
                    $appData     = SpecialServiceRenew::find($existProcessInfo->ref_id);
                }elseif($type==3){
                    $table = 'special_license_amendment';
                    $appData     = SpecialServiceAmmendment::find($existProcessInfo->ref_id);
                }elseif($type==4){
                    $table = 'special_license_surrender';
                    $appData     = SpecialServiceSurrender::find($existProcessInfo->ref_id);
                }

                if ( $statusID == 5) { //shortfall
                    $appData->shortfall_reason = $request->get('shortfall_reason');
                    $appData->save();
                }


                //

                if ( $statusID == 25) {



                    $license_no = generateLicenseNo($existProcessInfo->process_type_id, $table, '400', '42');

                    $license_update = ProcessList::find($existProcessInfo->id);
                    $license_update->license_no = $license_no;
                    $license_update->save();

                    $appData->license_no = $license_no;
                    $appData->license_issue_date = Carbon::now();
                    $appData->expiry_date =  date('Y-m-d h:i:sa', strtotime(Carbon::now() . '+3 years'));
                    $appData->save();

                    //

                    $ispLicenseMaster = SpecialServiceMaster::where([
                        'issue_tracking_no' => $existProcessInfo->tracking_no
                    ])->latest()->first();

                    if (!isset($ispLicenseMaster) && empty($ispLicenseMaster)) {
                        $ispLicenseMaster = new SpecialServiceMaster();
                    }


                    // ISP license master data isnert
                    $ispLicenseMaster->issue_tracking_no = $existProcessInfo->tracking_no;
                    $ispLicenseMaster->company_id = $appData->company_id;
                    $ispLicenseMaster->org_nm = $appData->orgapplicant_name_nm;
                    // $ispLicenseMaster->org_type = $appData->org_type;
                    $ispLicenseMaster->org_mobile = $appData->applicant_mobile;
                    $ispLicenseMaster->org_phone = $appData->applicant_telephone;
                    $ispLicenseMaster->org_email = $appData->applicant_email;
                    $ispLicenseMaster->org_district = $appData->applicant_district;
                    $ispLicenseMaster->org_upazila = $appData->applicant_thana;
                    $ispLicenseMaster->org_address = $appData->applicant_address;
                    $ispLicenseMaster->org_website = $appData->applicant_website;
                    $ispLicenseMaster->json_object = $appData->json_object;
                    $ispLicenseMaster->status = 1;
                    $ispLicenseMaster->created_at = Carbon::now();

                    // license info
                    $ispLicenseMaster->license_no = $license_no;
                    $ispLicenseMaster->license_issue_date = Carbon::now();
                    $ispLicenseMaster->expiry_date =  date('Y-m-d h:i:sa', strtotime(Carbon::now() . '+3 years'));

                    $ispLicenseMaster->save();

                    //

                    $pdf_link_gen_params = [
                        "process_type_id" => $existProcessInfo->process_type_id,
                        "app_id" => $existProcessInfo->ref_id,
                        "processInfo" => $existProcessInfo,
                        "certificate_name" => 'isp-license-renew',
                        "approver_desk_id" => $existProcessInfo->desk_id,
                        "certificate_type" => 'generate',
                    ];
                    // PDFLinkForCertificate($pdf_link_gen_params, 1);


                $url_store = PdfPrintRequestQueue::firstOrNew([
                    'process_type_id' => $existProcessInfo->process_type_id,
                    'app_id' => $existProcessInfo->ref_id,
                    'pdf_diff' => pdf_diff($statusID)
                ]);
                $pdf_info = PdfServiceInfo::first();

                if (empty($pdf_info)) {
                    return false;
                }
                $tableName = $pdf_info->table_name;
                $fieldName = $pdf_info->field_name;

                $url_store->process_type_id = $existProcessInfo->process_type_id;
                $url_store->app_id = $existProcessInfo->ref_id;
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

        // Store approve information
                $signature_store_status = storeSignatureQRCode($pdf_link_gen_params["process_type_id"], $pdf_link_gen_params["app_id"], 0, $pdf_link_gen_params["approver_desk_id"], 'final', $pdf_link_gen_params["processInfo"]->status_id, 0);
                if ($signature_store_status === false) {
                    return false;
                }


                $url_store->pdf_diff = pdf_diff($existProcessInfo->status_id,0);

                $url_store->updated_at = date('Y-m-d H:i:s');
                $url_store->save();

                }

                ///
                return redirect('special_service/service-list/'.Encryption::encodeId($existProcessInfo->process_type_id));
            }else{
                if($flag == 0){
                return redirect('process/list/' . Encryption::encodeId($existProcessInfo->process_type_id));
                }else{
                    $response = array( // Response for valid request
                        'status' => Response::HTTP_OK,
                        'success' => 'success',
                    );
                    return response()->json($response);
                }
            }


        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry, something went wrong. ' . CommonFunction::showErrorPublic($e->getMessage()) . '[PPC-1004]');
            return redirect()->back();
        }
    }

    /**
     * Check application validity for application process
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkApplicationValidity(Request $request)
    {
        $process_list_id = Encryption::decodeId($request->get('process_list_id'));

        /*
         * $existProcessInfo variable must should be same as $verificationData variable
         * of applicationOpen() function, it's required for application verification
         *
         *
         * When one officer is processing an application, another officer may want to open the application.
         * If the 2nd officer forcibly opens the application, the previous officer should be alerted,
         * this is done through process data verification. That's why the 'locked_by' field is a must.
         */
        $existProcessInfo = ProcessList::where('id', $process_list_id)
            ->first([
                'id',
                'status_id',
                'desk_id',
                'user_id',
                'office_id',
                'tracking_no',
                'created_by',
                'locked_by'
            ]);


        if (Encryption::decode($request->data_verification) == UtilFunction::processVerifyData($existProcessInfo)) {
            return response()->json(array('responseCode' => 1));
        }
        return response()->json(array('responseCode' => 0));
    }


    /**
     * Load status list
     * @param $param
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxRequest($param, Request $request)
    {
        try {
            $data = ['responseCode' => 0];
            $cat_id = Encryption::decodeId($request->get('cat_id'));
            $process_list_id = Encryption::decodeId($request->get('process_list_id'));
            $appInfo = ProcessList::where('id', $process_list_id)->first(
                [
                    'process_type_id',
                    'id as process_list_id',
                    'status_id',
                    'ref_id',
                    'id',
                    'json_object',
                    'desk_id',
                    'process_desc',
                    'updated_at'
                ]
            );
            $statusFrom = $appInfo->status_id; // current process status
            $deskId = $appInfo->desk_id; // Current desk id
            $process_type_id = $appInfo->process_type_id; // Current desk id

            if ($param == 'load-status-list') {

                // Get extra SQL from this process path for Status loading,
                // if have any extra sql then , load status list from extra SQL
                // otherwise status list load from Static Query
                $check_extra_sql = ProcessPath::where(['status_from' => $statusFrom, 'desk_from' => $deskId, 'process_type_id' => $process_type_id, 'cat_id' => $cat_id])
                    ->first(['id', 'ext_sql']);
                if (!empty($check_extra_sql->ext_sql)) {
                    $get_status_query = str_replace("{app_id}", "$appInfo->ref_id", $check_extra_sql->ext_sql);
                } else {
                    //checking payment through the pay order
                    $isPayOrderPayment = SonaliPayment::where(['app_id' => $appInfo->ref_id,'payment_step_id' => 2, 'payment_type' => 'pay_order' ])->count(); // 2 = Second payment
                    $exceptPayOrderStatusQry = ($isPayOrderPayment) ? '':' AND APP.status_to != 46 ' ; // 46 = Shortfall for pay order issue (process_Status)

                    $get_status_query = "SELECT APS.id, APS.status_name
                        FROM process_status APS
                        WHERE find_in_set(APS.id,
                        (SELECT GROUP_CONCAT(status_to) FROM process_path APP
                        WHERE APP.status_from = '$statusFrom' $exceptPayOrderStatusQry
                        AND APP.desk_from = '$deskId'
                        AND APP.cat_id = '$cat_id'
                        AND APP.process_type_id = '$process_type_id'))
                        AND APS.process_type_id = '$process_type_id'
                        order by APS.status_name";
                }
                $status_list = \DB::select(DB::raw($get_status_query));

                // Get suggested desk
                $suggested_status = $this->getSuggestedStatus($appInfo, $cat_id);

                $data = ['responseCode' => 1, 'data' => $status_list, 'suggested_status' => $suggested_status];
            }
            return response()->json($data);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[PPC-1021]");
            return Redirect::back();
        }
    }

    public function getSuggestedStatus($appInfo, $cat_id)
    {
        try {
            // Get suggested status by comment
            $suggested_status_by_comment = 0;
            $suggested_status_data = ProcessType::where('id', $appInfo->process_type_id)->first(['suggested_status_json']);

            if (!empty($suggested_status_data->suggested_status_json)) {
                $suggested_status_json = json_decode($suggested_status_data->suggested_status_json);
                if (!empty($suggested_status_json)) {
                    foreach ($suggested_status_json as $json) {
                        $search_result = strpos($appInfo->process_desc, $json->comments);
                        if ($search_result !== false) {
                            $suggested_status_by_comment = $json->status;
                            break;
                        }
                    }
                }
            }
            if (!empty($suggested_status_by_comment)) {
                return $suggested_status_by_comment;
            }

            // Get suggested status by process path
            $suggested_status_data = ProcessPath::where([
                'process_type_id' => $appInfo->process_type_id,
                'cat_id' => $cat_id,
                'desk_from' => $appInfo->desk_id,
                'status_from' => $appInfo->status_id,
            ])->where('suggested_status', '!=', 0)->first(['suggested_status']);

            return empty($suggested_status_data->suggested_status) ? 0 : $suggested_status_data->suggested_status;
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1017]');
            return redirect()->back();
        }
    }


    public function applicationAdd($module = '', $encoded_process_type_id)
    {
        try {
            $mode = '-A-';
            $viewMode = 'off';
            $openMode = 'add';
            $decoded_process_type_id = Encryption::decodeId($encoded_process_type_id);
            if($response=CommonFunction::warningProcessType($decoded_process_type_id)){
                if(array_key_exists('html',$response)){
                    return redirect()->to('/dashboard')->with('error', $response['html']);
                }else{
                    return redirect()->to('/dashboard')->with('error', "You have no access right! Please contact with system admin if you have any query.");
                }
            }
            $process_info = ProcessType::where('id', $decoded_process_type_id)->first([
                'id as process_type_id',
                'acl_name',
                'form_id',
                'name',
                'drop_down_label'
            ]);
            $form_id = json_decode($process_info->form_id, true);
//            $url = (isset($form_id[$openMode]) ? $form_id[$openMode] : '');
            $url = "process/license/content/{$encoded_process_type_id}";
            $page_header = $process_info->name;

            // Following variables will be used for Ajax calling from the form page
            $encoded_app_id = 0;
            $encoded_process_list_id = 0;
            return view(
                "ProcessPath::form",
                compact('process_info', 'mode', 'viewMode', 'openMode', 'url', 'page_header', 'encoded_app_id', 'encoded_process_type_id', 'encoded_process_list_id')
            );
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [PPC-1008]');
            return Redirect::back();
        }
    }

    public function applicationOpen($module = '', $encoded_app_id, $encoded_process_type_id)
    {
        try {
            $process_type_id = Encryption::decodeId($encoded_process_type_id);
            $process_type= ProcessType::find($process_type_id);
            $application_id = Encryption::decodeId($encoded_app_id);
            $user_type = CommonFunction::getUserType();
            $process_info = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('process_type as pt', function ($join) use ($process_type_id) {
                    $join->on('pt.id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('pdf_print_requests_queue as pdf', function ($join) use ($process_type_id) {
                    $join->on('pdf.app_id', '=', 'process_list.ref_id');
                    //$join->on('pdf.process_list_id', '=', 'process_list.id');
                    $join->on('pdf.process_type_id', '=', DB::raw($process_type_id));
                    $join->on('pdf.pdf_diff', '=', DB::raw(0));
                })
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->where([
                    'process_list.ref_id' => $application_id,
                    'process_list.process_type_id' => $process_type_id,
                ])
                ->orderBy('process_list.id', 'desc')
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.office_id',
                    'process_list.cat_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.priority',
                    'process_list.json_object',
                    'process_list.updated_at',
                    'process_list.created_by',
                    'process_list.user_id',
                    'process_list.read_status',
                    'process_list.submitted_at',
                    'process_type.name',
                    'process_type.acl_name',
                    'process_type.form_id',
                    'ps.status_name',
                    'pt.menu_name',
                    'pt.acl_name',
                    'pdf.certificate_link',
                    'user_desk.desk_name',
                ]);
                $process_type_check = ProcessType::where('id',$process_type_id)->first();
                if($process_type_check->is_special==1){

                    if($process_type_check->type==1){
                      $process_type_table= 'special_license_issue';
                    }elseif($process_type_check->type==2){
                      $process_type_table= 'special_license_renew';
                    }elseif($process_type_check->type==3){
                      $process_type_table  = 'special_license_amendment';
                    }elseif($process_type_check->type==4){
                      $process_type_table = 'special_license_surrender'  ;
                    }

                }else{
                    $process_type_table = ProcessType::process_type_table_by_id($process_type_id);
                }

            $ref_table_info = DB::table($process_type_table)->where('id',$process_info->ref_id)->first(['tracking_no','license_no']);

            if (empty($process_info)) {
                Session::flash('error', 'Invalid application [PPC-1096]');
                return \redirect()->back();
            }

            // Following variables will be used for Ajax calling from the form page
            $encoded_process_list_id = Encryption::encodeId($process_info->process_list_id);
            $page_header = $process_info->name;

            // ViewMode, EditMode permission setting
            $viewMode = 'on';
            $openMode = 'view';
            $mode = '-V-';

            $form_id = json_decode($process_info->form_id, true);
            $url = (isset($form_id[$openMode]) ? $form_id[$openMode] : '');

            // Update process read status from applicant user
            if ($process_info->created_by == Auth::user()->id && in_array($process_info->status_id, [5, 6, 25]) && $process_info->read_status == 0) {
                $this->updateProcessReadStatus($application_id);
            }
            if ($process_info->created_by == Auth::user()->id && in_array($process_info->status_id, [5, 6, 25]) && $process_info->read_status == 0) {
                $this->updateProcessReadStatus($application_id);
            }

            /**
             * if this user has access to application processing,
             * then check the permission for this application with the corresponding desk, office etc.
             */
            $accessMode = ACL::getAccsessRight($process_info->acl_name);

            $hasDeskOfficeWisePermission = false;
            if (ACL::isAllowed($accessMode, '-UP-')) {
                $hasDeskOfficeWisePermission = CommonFunction::hasDeskOfficeWisePermission($process_info->desk_id, $process_info->office_id);
                //user wise permission   if this user has access to application processing
                if($process_info->user_id && ($process_info->user_id != null ||$process_info->user_id!=0)){
                if(Auth::user()->id != $process_info->user_id){
                    $hasDeskOfficeWisePermission = false;
                }
                }
                // Update process read status from desk officer user
                if (($hasDeskOfficeWisePermission && $process_info->read_status == 0)) {
                    $this->updateProcessReadStatus($application_id);
                }

                if ($hasDeskOfficeWisePermission) {
                    $remarks_attachment = DB::select(DB::raw(
                        "select * from `process_documents`
                                                where `process_type_id` = $process_info->process_type_id and `ref_id` = $process_info->process_list_id and `status_id` = $process_info->status_id
                                                and `process_hist_id` = (SELECT MAX(process_hist_id) FROM process_documents WHERE ref_id=$process_info->process_list_id AND process_type_id=$process_info->process_type_id AND status_id=$process_info->status_id)
                                                ORDER BY id ASC"
                    ));
                }
            }


            /*
             * Conditional data for desk user, system admin
             */
            $verificationData = [];
            $cat_id = '';
            $remarks_attachment = '';
            if (in_array($user_type, ['4x404', '3x303', '1x101', '10x101'])) {

                $cat_id = $process_info->cat_id;

                /**
                 * Lock application by the current user,
                 * if the current user's desk id is not equal to zero (0) and
                 * application desk id is in user's authorized desk
                 */
                $userDeskIds = CommonFunction::getUserDeskIds();

                if (Auth::user()->desk_id != 0 && (in_array($process_info->desk_id, $userDeskIds) || in_array($process_info->desk_id, $this->getDelegateUsers()))) {
                    ProcessList::where('id', $process_info->process_list_id)->update([
                        'locked_by' => Auth::user()->id,
                        'locked_at' => date('Y-m-d H:i:s')
                    ]);
                }
                // End Lock application by current desk user


                /*
                * $verificationData variable must should be same as $existProcessInfo variable
                * of checkApplicationValidity() function, it's required for application verification
                */

                $verificationData['id'] = $process_info->process_list_id;
                $verificationData['status_id'] = $process_info->status_id;
                $verificationData['desk_id'] = $process_info->desk_id;
                $verificationData['user_id'] = $process_info->user_id;
                $verificationData['office_id'] = $process_info->office_id;
                $verificationData['tracking_no'] = $process_info->tracking_no;
                $verificationData['created_by'] = $process_info->created_by;

                /*
                * When one officer is processing an application, another officer may want to open the application.
                * If the 2nd officer forcibly opens the application, the previous officer should be alerted,
                * this is done through process data verification. That's why the 'locked_by' field is a must.
                *
                * Locked by field updates when the application is open. Since the database will not be updated
                * before the transaction is completed, we will give the value directly.
                */
                $verificationData['locked_by'] = Auth::user()->id;
                $verificationData = (object)$verificationData;
            }

            return view("ProcessPath::form", compact(
                'url',
                'process_info',
                'process_type',
                'ref_table_info',
                'encoded_app_id',
                'mode',
                'viewMode',
                'encoded_process_type_id',
                'encoded_process_list_id',
                'openMode',
                'verificationData',
                'hasDeskOfficeWisePermission',
                'page_header',
                'cat_id',
                'remarks_attachment'
            ));
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong!. ' . CommonFunction::showErrorPublic($e->getMessage()) . '[PPC-109]');
            return \redirect()->back();
        }
    }

    public function applicationEdit($module = '', $encoded_app_id, $encoded_process_type_id)
    {
        try {
            $verificationData = [];
            $cat_id = '';
            $remarks_attachment = '';

            $process_type_id = Encryption::decodeId($encoded_process_type_id);
            $application_id = Encryption::decodeId($encoded_app_id);
            if($response=CommonFunction::warningProcessType($process_type_id)){
                if(array_key_exists('html',$response)){
                    return redirect()->to('/dashboard')->with('error', $response['html']);
                }else{
                    return redirect()->to('/dashboard')->with('error', "You have no access right! Please contact with system admin if you have any query.");
                }
            }
            $process_info = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('pdf_print_requests_queue as pdf', function ($join) use ($process_type_id) {
                    $join->on('pdf.app_id', '=', 'process_list.ref_id');
                    $join->on('pdf.process_type_id', '=', DB::raw($process_type_id));
                    $join->on('pdf.pdf_diff', '=', DB::raw(0));
                })
                ->where([
                    'process_list.ref_id' => $application_id,
                    'process_list.process_type_id' => $process_type_id,
                ])
                ->orderBy('process_list.id', 'desc')
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.office_id',
                    'process_list.cat_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.priority',
                    'process_list.json_object',
                    'process_list.updated_at',
                    'process_list.created_by',
                    'process_list.user_id',
                    'process_type.name',
                    'process_type.acl_name',
                    'process_type.form_id',
                    'pdf.certificate_link',
                    'ps.status_name',
                ]);

            if (empty($process_info)) {
                Session::flash('error', 'Invalid application [PPC-1096]');
                return \redirect()->back();
            }

            // Following variables will be used for Ajax calling from the form page
            $encoded_process_list_id = Encryption::encodeId($process_info->process_list_id);
            $page_header = $process_info->name;

            // ViewMode, EditMode permission setting
            $viewMode = 'on';
            $openMode = 'view';
            $mode = '-V-';
            $hasDeskOfficeWisePermission = false;
            $user_type = CommonFunction::getUserType();

            if (in_array($user_type, ['5x505', '6x606'])) {
                $companyId = CommonFunction::getUserCompanyWithZero();
                if ($process_info->company_id == $companyId && in_array($process_info->status_id, [-1, 5])) {
                    $mode = '-E-';
                    $viewMode = 'off';
                    $openMode = 'edit';
                }
            }



            // No need to check the acl again in application view function into corresponding controller
            if (!ACL::getAccsessRight($process_info->acl_name, $mode)) {
                die('You have no access right! Please contact system administration for more information.');
            }

            $form_id = json_decode($process_info->form_id, true);
            $url = (isset($form_id[$openMode]) ? $form_id[$openMode] : '');
            $process_type= ProcessType::find($process_info->process_type_id);
            $data=[];

            if($process_type->is_special==1){
                  if($process_type->type==1){
                     $data['appInfo'] = SpecialServiceIssue::find($process_info->ref_id) ;
                  }elseif($process_type->type==2){
                     $data['appInfo'] = SpecialServiceRenew::find($process_info->ref_id);
                  }elseif($process_type->type==3){
                     $data['appInfo'] = SpecialServiceAmmendment::find($process_info->ref_id);
                  }elseif($process_type->type==4){
                     $data['appInfo'] = SpecialServiceSurrender::find($process_info->ref_id);
                  }


        $data['appinfo_json'] = json_decode($data['appInfo']->json_object,true);
        $data['appDynamicDocInfo']  = collect(json_decode($data['appInfo']->json_object))->reject(function($item, $key){
            if (strpos($key,'doc_') !== false) {
                return false;
            } else {
                return true;
            }
        })->toArray();

        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();


        $data['process_type_id']  = $process_info->process_type_id;
        $data['current_status_id']  = $process_info->status_id;
        $dynamic_form = DynamicProcess::where('process_type_id',$process_info->process_type_id)->first();
        $data['districts']        = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thana']        = ['' => 'Select'] + Area::where('area_type', 3)->where('pare_id',$data['appInfo']->applicant_district)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division']         = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['bank_list']        = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();

        $data['dynamic_form']        = $dynamic_form;
        $data['dynamic_form_data']        = json_decode($dynamic_form->dynamic_data,true) ;
        $data['dynamic_payment_data']        = DynamicPayment::where('process_type_id',$data['process_type_id'])->where('app_id',$process_info->ref_id)->first() ;
        $data['branch_list']        = BankBranch::orderBy('branch_name')->where('bank_id',$data['dynamic_payment_data']->bank_id)->where('is_active', 1)->pluck('branch_name', 'id')->toArray();
        $data['dynamic_form_attachments']        = $data['dynamic_form_data'][0]['attachments'] ;

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

                unset($data['dynamic_form_data'][0]['attachments']);

        $data['reg_thana'] = ['' => 'Select'] + Area::where('area_type', 3)->where('pare_id',$data['appInfo']->reg_office_district)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['op_thana'] = ['' => 'Select'] + Area::where('area_type', 3)->where('pare_id',$data['appInfo']->op_office_district)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
            return view("Dashboard::special-service-edit", $data);

            }else{
                return view("ProcessPath::form", compact(
                    'url',
                    'process_info',
                    'mode',
                    'viewMode',
                    'openMode',
                    'hasDeskOfficeWisePermission',
                    'verificationData',
                    'page_header',
                    'cat_id',
                    'remarks_attachment',
                    'encoded_process_type_id',
                    'encoded_app_id',
                    'encoded_process_list_id'
                ));
            }


        } catch (Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Something went wrong! [PPC-1019]');
            return \redirect()->back();
        }
    }

    public function updateProcessReadStatus($application_id)
    {
        ProcessList::where('ref_id', $application_id)->update(['read_status' => 1]);
    }

    public function getCatId($cat_id, $process_type_id)
    {
        $cat_id = 1;
        $data = DB::table('process_path_cat_mapping')
            ->where('process_type_id', $process_type_id)
            ->where('industrial_category_id', $cat_id)
            ->first(['cat_id']);
        if ($data) {
            $cat_id = $data->cat_id;
        }
        return $cat_id;
    }

    public function getHelpText(Request $request)
    {
        try {
            if ($request->has('uri') && $request->get('uri') != '') {
                $module = $request->get('uri');
            }

            if (!empty($module)) {
                $data = HelpText::where('is_active', 1)->where('module', $module)->get(['field_id', 'field_class', 'help_text', 'help_text_type', 'validation_class']);
            } else {
                $data = HelpText::where('is_active', 1)->get(['field_id', 'field_class', 'help_text', 'help_text_type']);
            }
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1017]');
            return redirect()->back();
        }
    }

    public function favoriteDataStore(Request $request)
    {
        $process_id = Encryption::decodeId($request->get('process_list_id'));
        ProcessFavoriteList::create([
            'process_id' => $process_id,
            'user_id' => CommonFunction::getUserId()
        ]);
        return response()->json('success');
    }

    public function favoriteDataRemove(Request $request)
    {
        $process_id = Encryption::decodeId($request->get('process_list_id'));
        ProcessFavoriteList::where('process_id', $process_id)
            ->where('user_id', CommonFunction::getUserId())
            ->delete();
        return response()->json('success');
    }

    public function getShadowFileHistory($process_type_id, $ref_id)
    {
        $process_type_id = Encryption::decodeId($process_type_id);
        $ref_id = Encryption::decodeId($ref_id);
        $getShadowFile = ShadowFile::where('user_id', CommonFunction::getUserId())
            ->where('ref_id', $ref_id)
            ->where('process_type_id', $process_type_id)
            ->orderBy('id', 'DESC')
            ->get();
        $content = strval(view('ProcessPath::shadow-file-history', compact('getShadowFile')));
        return response()->json(['response' => $content]);
    }

    public function getApplicationHistory($process_list_id)
    {
        try {
            $decoded_process_list_id = Encryption::decodeId($process_list_id);
            $process_history = DB::select(DB::raw("select  `process_list_hist`.`desk_id`,`as`.`status_name`,
                                `process_list_hist`.`process_id`,
                                if(`process_list_hist`.`desk_id`=0,\"-\",`ud`.`desk_name`) `deskname`,
                                `users`.`user_first_name`,
                                `users`.`user_middle_name`,
                                `users`.`user_last_name`,
                                `process_list_hist`.`updated_by`,
                                `process_list_hist`.`status_id`,
                                `process_list_hist`.`process_desc`,
                                `process_list_hist`.`process_id`,
                                `process_list_hist`.`updated_at`,
                                 group_concat(`pd`.`file`) as files
                                from `process_list_hist`
                                left join `process_documents` as `pd` on `process_list_hist`.`id` = `pd`.`process_hist_id`
                                left join `user_desk` as `ud` on `process_list_hist`.`desk_id` = `ud`.`id`
                                left join `users` on `process_list_hist`.`updated_by` = `users`.`id`

                                left join `process_status` as `as` on `process_list_hist`.`status_id` = `as`.`id`
                                and `process_list_hist`.`process_type_id` = `as`.`process_type_id`
                                where `process_list_hist`.`process_id`  = '$decoded_process_list_id'
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`process_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, process_list_hist.updated_at
                    order by process_list_hist.updated_at desc
                    "));
            $content = strval(view('ProcessPath::application-history', compact('process_list_id', 'process_history')));
            return response()->json(['response' => $content]);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1003]');
            return redirect()->back();
        }
    }


    public function getProcessData($processTypeId, $appId = 0, $cat_id)
    {
        try {
            $app_id = Encryption::decodeId($appId);
            $processTypeId = Encryption::decodeId($processTypeId);

            $resubmitId = ProcessHistory::where('ref_id', $app_id)
                ->where('process_type_id', $processTypeId)
                ->where('status_id', '=', 2)
                ->orderBy('id', 'desc')
                ->first(['id']);


            if ($resubmitId != null) {
                $sql2 = "SELECT  group_concat(distinct desk_id) as deskIds,group_concat(distinct status_id) as statusIds from process_list_hist
                where id >= $resubmitId->id and ref_id= $app_id
                and process_type_id=$processTypeId and status_id!='-1'";
                $processHistory = \DB::select(DB::raw($sql2));
            } else {
                $sql = "select  group_concat(distinct desk_id) as deskIds,group_concat(distinct status_id) as statusIds,group_concat(distinct id) as history_id  from process_list_hist
                where ref_id = $app_id and process_type_id = $processTypeId and status_id!='-1'";
                $processHistory = \DB::select(DB::raw($sql));
            }

            //extra code for dynamic graph
            $passed_desks_ids = explode(',', $processHistory[0]->deskIds);
            $passed_status_ids = explode(',', $processHistory[0]->statusIds);

            array_push($passed_desks_ids, 0);

            foreach ($passed_desks_ids as $v) {
                $passed_desks_id[] = (int)$v;
            }

            foreach ($passed_status_ids as $va) {
                $passed_status_id[] = (int)$va;
            }

            $passed_status_id = array_reverse($passed_status_id);
            //extra code for dynamic graph end

            $processPathTable = $this->processPathTable;
            $deskTable = $this->deskTable;
            $processStatus = $this->processStatus;

            $fullProcessPath = DB::table($processPathTable)
                ->leftJoin($deskTable . ' as from_desk', $processPathTable . '.desk_from', '=', 'from_desk.id')
                ->leftJoin($deskTable . ' as to_desk', $processPathTable . '.desk_to', '=', 'to_desk.id')
                ->leftJoin($processStatus . ' as from_process_status', function ($join) use ($processTypeId, $processPathTable) {
                    $join->where('from_process_status.process_type_id', '=', $processTypeId);
                    $join->on($processPathTable . '.status_from', '=', 'from_process_status.id');
                })
                ->leftJoin($processStatus . ' as to_process_status', function ($join) use ($processTypeId, $processPathTable) {
                    $join->where('to_process_status.process_type_id', '=', $processTypeId);
                    $join->on($processPathTable . '.status_to', '=', 'to_process_status.id');
                })
                ->select(
                    $processPathTable . '.desk_from',
                    $processPathTable . '.desk_to',
                    $processPathTable . '.status_from as status_from',
                    $processPathTable . '.status_to as status_to',
                    'from_desk.desk_name as from_desk_name',
                    'to_desk.desk_name as to_desk_name',
                    'from_process_status.status_name as from_status_name',
                    'to_process_status.status_name as to_status_name',
                    'to_process_status.id as status_id'
                )
                ->where($processPathTable . '.process_type_id', $processTypeId)
                ->where($processPathTable . '.cat_id', $cat_id)
                ->orderBy('process_path.id', 'ASC')
                ->get();

            $moveToNextPath = [];
            $deskActions = [];
            $i = 0;

            foreach ($fullProcessPath as $process) {

                if ($i == 0) {
                    $moveToNextPath[] = [
                        'Applicant',
                        $process->from_desk_name,
                        [
                            'label' => isset($resubmitId->id) ? 'Re-submitted' : $process->from_status_name,
                        ],
                    ];
                }

                if (intval($process->desk_to) > 0) {

                    $moveToNextPath[] = [
                        $process->from_desk_name,
                        $process->to_desk_name,
                        [
                            'label' => $process->to_status_name,
                        ],
                    ];
                } else {
                    $moveToNextPath[] = [
                        $process->from_desk_name,
                        $process->from_desk_name . '_' . $process->to_status_name,
                        ['label' => $process->to_status_name],
                    ];

                    $deskActions[] = [
                        'name' => $process->from_desk_name . '_' . $process->to_status_name,
                        'label' => $process->to_status_name,
                        'action_id' => $process->status_id,
                        'shape' => 'ellipse',
                        'background' => $this->getColor($process->status_to),
                    ];
                }

                $i++;
            }

            $allFromDeskForThisProcess = DB::table($processPathTable)
                ->select('from_desk.desk_name as name', 'from_desk.id as desk_id', DB::raw('CONCAT(from_desk.desk_name, " (", from_desk.id, ")") as label'))
                ->leftJoin($deskTable . ' as from_desk', $processPathTable . '.desk_from', '=', 'from_desk.id')
                ->where($processPathTable . '.process_type_id', $processTypeId)
                ->groupBy('desk_from')
                ->get()->toArray();

            array_push($allFromDeskForThisProcess, [
                'name' => 'Applicant',
                'label' => 'Applicant',
                'desk_id' => 0
            ]);

            return response()->json([
                'desks' => $allFromDeskForThisProcess,
                'desk_action' => $deskActions,
                'edge_path' => $moveToNextPath,
                'passed_desks_id' => $passed_desks_id,
                'passed_status_id' => $passed_status_id,
            ]);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1013]');
            return redirect()->back();
        }
    }

    public function getColor($i)
    {
        $colorArray = [
            '#800000',
            '#3cb44b',
            '#e6194b',
            '#911eb4',
            '#aa6e28',
            '#145A32',
            '#000080',
            '#000000',
            '#1B2631',
            '#1B4F72',
            '#008000',
            '#800080',
        ];
        try {
            return $colorArray[$i];
        } catch (\Exception $e) {

            return '#9d68d0';
        }
    }


    public function requestShadowFile(Request $request)
    {
        try {

            $jsonData['process_id'] = ($request->has('process_id') ? Encryption::decodeId($request->get('process_id')) : '');
            $jsonData['module_name'] = ($request->has('module_name') ? str_replace("", '', $request->get('module_name')) : '');
            $jsonData['process_type_id'] = ($request->has('process_type_id') ? Encryption::decodeId($request->get('process_type_id')) : '');
            $jsonData['app_id'] = ($request->has('ref_id') ? Encryption::decodeId($request->get('ref_id')) : '');
            $jsonInfo = json_encode($jsonData);

            ShadowFile::create([
                'file_path' => '',
                'user_id' => CommonFunction::getUserId(),
                'process_type_id' => $jsonData['process_type_id'],
                'ref_id' => $jsonData['app_id'],
                'shadow_file_perimeter' => $jsonInfo
            ]);

            return response()->json(['responseCode' => 1, 'status' => 'success']);
        } catch (Exception $e) {
            return response()->json(['responseCode' => 0, 'messages' => CommonFunction::showErrorPublic($e->getMessage())]);
        }
    }

    public function verifyProcessHistory($process_list_id)
    {
        try {
            $process_list_id = Encryption::decodeId($process_list_id);

            $process_history = DB::select(DB::raw("select  `process_list_hist`.`process_id`, `process_list_hist`.`status_id`,`as`.`status_name`,
                                if(`process_list_hist`.`desk_id`=0,\"-\",`ud`.`desk_name`) `deskname`,
                                concat_ws(' ', `users`.`user_first_name`, `users`.`user_middle_name`, `users`.`user_last_name`) as user_full_name,
                                `process_list_hist`.`id`,
                                `process_list_hist`.`process_id`,
                                `process_list_hist`.`ref_id`,
                                `process_list_hist`.`process_type_id`,
                                `process_list_hist`.`tracking_no`,
                                `process_list_hist`.`closed_by`,
                                `process_list_hist`.`locked_by`,
                                `process_list_hist`.`locked_at`,
                                `process_list_hist`.`desk_id`,
                                `process_list_hist`.`status_id`,
                                `process_list_hist`.`user_id`,
                                `process_list_hist`.`process_desc`,
                                `process_list_hist`.`created_by`,
                                `process_list_hist`.`on_behalf_of_user`,
                                `process_list_hist`.`updated_by`,
                                `process_list_hist`.`status_id`,
                                `process_list_hist`.`process_desc`,
                                `process_list_hist`.`process_id`,
                                `process_list_hist`.`updated_at`,
                                `process_list_hist`.`hash_value`,
                                 group_concat(`pd`.`file`) as files
                                from `process_list_hist`
                                left join `process_documents` as `pd` on `process_list_hist`.`id` = `pd`.`process_hist_id`
                                left join `user_desk` as `ud` on `process_list_hist`.`desk_id` = `ud`.`id`
                                left join `users` on `process_list_hist`.`updated_by` = `users`.`id`

                                left join `process_status` as `as` on `process_list_hist`.`status_id` = `as`.`id`
                                and `process_list_hist`.`process_type_id` = `as`.`process_type_id`
                                where `process_list_hist`.`process_id`  = '$process_list_id'
                                and `process_list_hist`.`hash_value` !=''
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`process_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, process_list_hist.updated_at
                    order by process_list_hist.updated_at desc
                    limit 20
                    "));
            return view("ProcessPath::history-verification", compact('process_history'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[PPC-1007]');
            return Redirect::back();
        }
    }

    public static function batchProcessSet(Request $request)
    {
        try {
            Session::forget('is_delegation');
            $single_process_id_encrypt_current = '';
            if (!empty($request->current_process_id)) {
                $single_process_id_encrypt_current = $request->current_process_id;
            }
            if ($request->get('is_delegation') == true) {
                Session::put('is_delegation', 'is_delegation');
                $processData = ProcessList::leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                    ->where('process_list.id', Encryption::decodeId($single_process_id_encrypt_current))
                    ->first(['process_type.form_url', 'process_list.ref_id', 'process_list.process_type_id', 'tracking_no']);

                return response()->json([
                    'responseType' => 'single',
                    'url' => url('process/' . $processData->form_url . '/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id))
                ]);
            }
            if (empty($request->process_id_array)) {
                return response()->json([
                    'responseType' => false,
                    'url' => '',
                ]);
            }

            Session::forget('batch_process_id');
            Session::forget('is_batch_update');
            Session::forget('single_process_id_encrypt');
            Session::forget('next_app_info');
            Session::forget('total_selected_app');
            Session::forget('total_process_app');

            $process_id_encryption = $request->process_id_array;
            $total_selected_app = count($process_id_encryption);

            $single_process_id_encrypt_next = null;
            $find_current_key = array_search($single_process_id_encrypt_current, $process_id_encryption); //find current key
            $keys = array_keys($process_id_encryption); //total key
            $nextKey = isset($keys[array_search($find_current_key, $keys) + 1]) ? $keys[array_search($find_current_key, $keys) + 1] : ''; //next key
            if (!empty($nextKey)) {
                $single_process_id_encrypt_next = $process_id_encryption[$nextKey]; //next process id
                $single_process_id_encrypt_next = Encryption::decodeId($single_process_id_encrypt_next);
            }
            $process_id = Encryption::decodeId($single_process_id_encrypt_current);

            Session::put('batch_process_id', $request->process_id_array);
            Session::put('is_batch_update', 'batch_update');
            Session::put('single_process_id_encrypt', $single_process_id_encrypt_current);
            Session::put('total_selected_app', $total_selected_app);
            Session::put('total_process_app', $find_current_key + 1);

            $processData = ProcessList::leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                ->where('process_list.id', $process_id)
                ->first(['process_type.form_url', 'process_list.ref_id', 'process_list.process_type_id', 'tracking_no']);
            $nextAppInfo = 'null';
            if ($single_process_id_encrypt_next != null) {
                $nextAppInfo = ProcessList::where('process_list.id', $single_process_id_encrypt_next)->first(['tracking_no'])->tracking_no;
            }

            Session::put('next_app_info', $nextAppInfo);
            return response()->json([
                'responseType' => 'single',
                'url' => url('process/' . $processData->form_url . '/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id))
            ]);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1011]');
            return redirect()->back();
        }
    }

    public function skipApplication($single_process_id_encrypt_current)
    {
        try {
            $batch_process_id = Session::get('batch_process_id');

            $single_process_id_encrypt_next = null;
            $single_process_id_encrypt_second_next_key = null;
            $find_current_key = array_search($single_process_id_encrypt_current, $batch_process_id); //find current key
            $keys = array_keys($batch_process_id); //total key
            $nextKey = isset($keys[array_search($find_current_key, $keys) + 1]) ? $keys[array_search($find_current_key, $keys) + 1] : ''; //next key
            $second_nextKey = isset($keys[array_search($find_current_key, $keys) + 2]) ? $keys[array_search($find_current_key, $keys) + 2] : ''; //second next key

            if (!empty($nextKey)) {
                $single_process_id_encrypt_next = $batch_process_id[$nextKey]; //next process id
            }
            if (!empty($second_nextKey)) {
                $single_process_id_encrypt_second_next_key = $batch_process_id[$second_nextKey]; //next process id
            }

            if (empty($nextKey)) {
                $existProcessInfo = ProcessList::where('process_list.id', Encryption::decodeId($batch_process_id[0]))
                    ->first(['process_list.process_type_id']);
                \Session::flash('error', 'Sorry data not found!.');
                $process_type = ProcessType::find($existProcessInfo->process_type_id);
            if($process_type->is_special){
                return redirect('special_service/service-list/'.Encryption::encodeId($existProcessInfo->process_type_id));
            }else{
                return redirect('process/list/' . Encryption::encodeId($existProcessInfo->process_type_id));
            }

            }

            Session::put('single_process_id_encrypt', $single_process_id_encrypt_next);
            $get_total_process_app = Session::get('total_process_app');
            Session::put('total_process_app', $get_total_process_app + 1);

            $nextAppInfo = 'null';
            if ($single_process_id_encrypt_second_next_key != null) {
                $nextAppInfo = ProcessList::where('process_list.id', Encryption::decodeId($single_process_id_encrypt_second_next_key))->first(['tracking_no'])->tracking_no;
            }
            Session::put('next_app_info', $nextAppInfo);

            $processData = ProcessList::leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                ->where('process_list.id', Encryption::decodeId($single_process_id_encrypt_next))
                ->first(['process_type.form_url', 'process_list.ref_id', 'process_list.process_type_id', 'tracking_no']);
            $redirectUrl = 'process/' . $processData->form_url . '/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id);
            return redirect($redirectUrl);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1003]');
            return redirect()->back();
        }
    }

    public function previousApplication($single_process_id_encrypt_current)
    {
        try {
            $batch_process_id = Session::get('batch_process_id');


            $single_process_id_encrypt_previous = null;
            $single_process_id_encrypt_next = null;
            $find_current_key = array_search($single_process_id_encrypt_current, $batch_process_id); //find current key
            $keys = array_keys($batch_process_id); //total key
            $previousKey = isset($keys[array_search($find_current_key, $keys) - 1]) ? $keys[array_search($find_current_key, $keys) - 1] : null; //next key

            if (!is_null($previousKey)) {
                $single_process_id_encrypt_previous = $batch_process_id[$previousKey]; //next process id
            }
            if (!empty($find_current_key)) {
                $single_process_id_encrypt_next = $batch_process_id[$find_current_key]; //next process id
            }


            if (is_null($previousKey)) {
                $existProcessInfo = ProcessList::where('process_list.id', Encryption::decodeId($batch_process_id[0]))
                    ->first(['process_list.process_type_id']);
                \Session::flash('error', 'Sorry data not found!.');
                $process_type = ProcessType::find($existProcessInfo->process_type_id);
            if($process_type->is_special){
                return redirect('special_service/service-list/'.Encryption::encodeId($existProcessInfo->process_type_id));
            }else{
                return redirect('process/list/' . Encryption::encodeId($existProcessInfo->process_type_id));
            }

            }

            Session::put('single_process_id_encrypt', $single_process_id_encrypt_previous);
            $get_total_process_app = Session::get('total_process_app');
            Session::put('total_process_app', $get_total_process_app - 1);

            $nextAppInfo = 'null';
            if ($single_process_id_encrypt_next != null) {
                $nextAppInfo = ProcessList::where('process_list.id', Encryption::decodeId($single_process_id_encrypt_next))->first(['tracking_no'])->tracking_no;
            }
            Session::put('next_app_info', $nextAppInfo);
            $processData = ProcessList::leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                ->where('process_list.id', Encryption::decodeId($single_process_id_encrypt_previous))
                ->first(['process_type.form_url', 'process_list.ref_id', 'process_list.process_type_id', 'tracking_no']);
            $redirectUrl = 'process/' . $processData->form_url . '/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id);
            return redirect($redirectUrl);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1009]');
            return redirect()->back();
        }
    }


    public function batchUpdateClass($request, $desk)
    {

        //this is for batch update code
        $class = [];
        if ($request->has('process_search')) { //work for search parameter
            $class['button_class'] = 'common_batch_update_search';
            $class['input_class'] = 'batchInputSearch';
        } elseif ($request->has('status_wise_list')) {
            $class['button_class'] = "status_wise_batch_update";
            $class['input_class'] = "batchInputStatus";

            if ($request->get('status_wise_list') == 'is_delegation') {
                $class['button_class'] = 'is_delegation';
            }
        } else {
            $class['button_class'] = "common_batch_update";
            $class['input_class'] = '';
            if ($desk == 'my-desk' || $desk == 'my-delg-desk') { //for batch update
                $class['input_class'] = 'batchInput';
            }
        }

        return $class;
    }


    /**
     * @param $app_id
     * @param $process_type_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function certificateRegeneration($app_id, $process_type_id): \Illuminate\Http\RedirectResponse
    {
        $app_id = Encryption::decodeId($app_id);
        $process_type_id = Encryption::decodeId($process_type_id);

        $process_type = ProcessType::select('acl_name', 'form_url')->find($process_type_id);

        //$certificateRegenerate = certificateGenerationRequest($app_id, $process_type_id, 0, 'regenerate');
        $certificateRegenerate = certificateGenerationRequest($app_id, $process_type_id, $process_type->acl_name, $process_type->form_url, 0, 'regenerate');
        if ($certificateRegenerate != true) {
            Session::flash('error', 'Sorry, something went wrong. [PPC-1045]');
            return redirect()->back();
        }

        Session::flash('success', 'Certificate regenerate process has been completed successfully.');
        return redirect()->back();
    }

    public static function statusWiseApps(Request $request)
    {
        $process_type_id = '';
        if (!empty($request->current_process_id)) {
            $process_type_id =  $request->current_process_id;
        }
//        $autoCancelData = ISPLicenseIssue::Where('auto_cancel',1)->count();
        $cancelData = ProcessList::where('bulk_status', 1)
            ->where('process_type_id', $process_type_id)
            ->count();

        $bulkData = ProcessList::where('bulk_status', 1)
            ->where('process_type_id', $process_type_id)
            ->count();

        $othersData = ProcessList::where('process_type_id',  $process_type_id)
            ->whereNotIn('status_id', ['-1', '1', '2', '5', '15', '16', '25'])
            ->where('bulk_status', '0')
            ->count();


        $ispData = [];
        if(in_array($process_type_id, [1,2,3,4])){
            $ispData[1] = ProcessList::where('process_type_id',1)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',2)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',3)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',4)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [1,2,3,4])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [5,6,7,8])){
            $ispData[1] = ProcessList::where('process_type_id',5)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',6)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',7)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',8)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [5,6,7,8])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [9,10,11,12])){
            $ispData[1] = ProcessList::where('process_type_id',9)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',10)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',11)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',12)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [9,10,11,12])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [13,14,15,16])){
            $ispData[1] = ProcessList::where('process_type_id',13)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',14)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',15)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',16)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [13,14,15,16])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [17,18,19,20])){
            $ispData[1] = ProcessList::where('process_type_id',17)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',18)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',19)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',20)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [17,18,19,20])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [21,22,23,24])){
            $ispData[1] = ProcessList::where('process_type_id',21)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',22)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',23)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',24)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [21,22,23,24])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [25,26,27,28])){
            $ispData[1] = ProcessList::where('process_type_id',25)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',26)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',27)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',28)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [25,26,27,28])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [29,30,83,84])){
            $ispData[1] = ProcessList::where('process_type_id',29)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',30)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',83)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',84)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [29,30,83,84])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [33,34,35,36])){
            $ispData[1] = ProcessList::where('process_type_id',33)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',34)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',35)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',36)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [33,34,35,36])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [37,38,39,40])){
            $ispData[1] = ProcessList::where('process_type_id',37)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',38)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',39)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',40)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [37,38,39,40])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [50,51,52,53])){
            $ispData[1] = ProcessList::where('process_type_id',50)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',51)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',52)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',53)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [50,51,52,53])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [54,55,56,57])){
            $ispData[1] = ProcessList::where('process_type_id',54)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',55)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',56)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',57)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [54,55,56,57])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [58,59,60,61])){
            $ispData[1] = ProcessList::where('process_type_id',58)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',59)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',60)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',61)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [58,59,60,61])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [62,63,64,65])){
            $ispData[1] = ProcessList::where('process_type_id',62)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',63)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',64)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',65)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [62,63,64,65])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [66,67,68,69])){
            $ispData[1] = ProcessList::where('process_type_id',66)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',67)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',68)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',69)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [66,67,68,69])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [70,71,72,73])){
            $ispData[1] = ProcessList::where('process_type_id',70)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',71)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',72)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',73)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [70,71,72,73])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [74,75,76,77])){
            $ispData[1] = ProcessList::where('process_type_id',74)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',75)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',76)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',77)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [74,75,76,77])->whereNotIn('status_id',[-1,-2])->count();
        }elseif (in_array($process_type_id, [78,79,80,81])){
            $ispData[1] = ProcessList::where('process_type_id',78)->whereNotIn('status_id',[-1,-2])->count();
            $ispData[2] = ProcessList::where('process_type_id',79)->where('status_id','!=','-1')->count();
            $ispData[3] = ProcessList::where('process_type_id',80)->where('status_id','!=','-1')->count();
            $ispData[4] = ProcessList::where('process_type_id',81)->where('status_id','!=','-1')->count();
            $ispData[5] = ProcessList::whereIn('process_type_id', [78,79,80,81])->whereNotIn('status_id',[-1,-2])->count();
        }

        $userType = Auth::user()->user_type;
        if (($userType == "1x101" || $userType == "10x101" || $userType == "4x404" || $userType == "5x505") && $process_type_id != '') {
            $status_wise_apps = ProcessList::statuswiseAppInDesks($process_type_id);
            $public_html = strval(view("ProcessPath::statuswiseApp", compact('status_wise_apps','cancelData', 'bulkData','ispData','othersData')));
        } else {
            $public_html = '';
        }


        return $public_html;
    }

    public function getDelegateUsers()
    {
        try {
            $delegateUser = Users::where('delegate_to_user_id', Auth::user()->id)->pluck('desk_id')->toArray();
            $tempArr = [];
            foreach ($delegateUser as $value) {
                $userDesk = explode(',', $value);
                $tempArr[] = $userDesk;
            }

            $arraySingle = [];
            if (!empty($tempArr)) {
                $arraySingle = call_user_func_array('array_merge', $tempArr);
            }
            return $arraySingle;
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1001]');
        }
    }

    //TODO:: Dynamic form handler methods
    public function commonAddForm($encoded_process_type_id) {
        $process_type_id = Encryption::decodeId($encoded_process_type_id);
        $process_info = ProcessType::where('id', $process_type_id)->first([
            'id as process_type_id',
            'acl_name',
            'form_id',
            'name'
        ]);
        $public_html = (new ReuseController($process_type_id, $process_info))->processContentAddForm();
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function commonStoreForm($encoded_process_type_id, Request $request) {
        try {
            $process_type_id = Encryption::decodeId($encoded_process_type_id);
            if($response=CommonFunction::warningProcessType($process_type_id)){
                if(array_key_exists('html',$response)){
                    return redirect()->to('/dashboard')->with('error', $response['html']);
                }else{
                    return redirect()->to('/dashboard')->with('error', "You have no access right! Please contact with system admin if you have any query.");
                }
            }
            $process_info = ProcessType::where('id', $process_type_id)->first([
                'id as process_type_id',
                'acl_name',
                'form_id',
                'name',
                'form_url'
            ]);

            $multiple_allow_array= ProcessType::where('name', 'like', '%' . 'Renew' . '%')
            ->orwhere('name', 'like', '%' . 'Ammendment' . '%')
            ->orwhere('name', 'like', '%' . 'Surrender' . '%')
            ->pluck('id')->toArray();

            if(empty($request->status_id) || (!empty($request->status_id) && $request->status_id == -1 && (!in_array($process_type_id,$multiple_allow_array)) ) ){

//                if(CommonFunction::checkExistModuleApplication($process_type_id, Auth::user()->working_company_id,$request->type_of_isp_licensese) && !env('CHECK_EXIST_APPLICATION') ){
//                    Session::flash('error', 'You are not eligible for multiple licenses of the same type.');
//                    return redirect()->back();
//                }
            }

            /*$res = */ return (new ReuseController($process_type_id, $process_info))->processContentStore($request);
//            if($res) {
//                $return_url = "client/".$process_info->form_url."/list/" . Encryption::encodeId( $process_type_id );
//                return redirect($return_url);
//            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash( 'error', CommonFunction::showErrorPublic( $e->getMessage() ) . "[IN-1025]" );
            return redirect()->back()->withInput();
        }
    }

    public function commonPreview($encoded_process_type_id) {
        $process_type_id = Encryption::decodeId($encoded_process_type_id);
        if($response=CommonFunction::warningProcessType($process_type_id)){
            if(array_key_exists('html',$response)){
                return redirect()->to('/dashboard')->with('error', $response['html']);
            }else{
                return redirect()->to('/dashboard')->with('error', "You have no access right! Please contact with system admin if you have any query.");
            }
        }
        $public_html = (new ReuseController($process_type_id))->preview();
        return $public_html;
    }

    public function commonFormEdit($form_url, $applicationId, $openMode = '', Request $request) {

        $process_type_id = $request->get('process_type_id');
        $decode_process_type_id = Encryption::decodeId($process_type_id);
//        if($response=CommonFunction::warningProcessType($decode_process_type_id)){
//            if(array_key_exists('html',$response)){
//                return redirect()->to('/dashboard')->with('error', $response['html']);
//            }else{
//                return redirect()->to('/dashboard')->with('error', "You have no access right! Please contact with system admin if you have any query.");
//            }
//        }
        $process_info = ProcessType::where('id', $decode_process_type_id)->first([
            'id as process_type_id',
            'acl_name',
            'form_id',
            'name'
        ]);

        return (new ReuseController($process_type_id, $process_info))->processContentEdit($applicationId, $openMode, $request);
    }

    public function applicationView($form_url, $applicationId, $openMode = '', Request $request) {
        $process_type_id =  Encryption::decodeId($request->get('process_type_id')) ;
        $process_info = ProcessType::where('id', $process_type_id)->first([
            'id as process_type_id',
            'acl_name',
            'form_id',
            'is_special',
            'type',
            'name'
        ]);
        return (new ReuseController($process_type_id, $process_info))->precessContentView($applicationId, $openMode, $request,$process_info);
    }

    public function fetchAppData($form_url, Request $request){
        $process_info = ProcessType::where('form_url', $form_url)->first([
            'id as process_type_id',
            'acl_name',
            'form_id',
            'name'
        ]);

        return (new ReuseController($process_info->process_type_id, $process_info))->fetchAppData($request);
    }

    public function getPaymentDataByLicenseType($form_url,Request  $request){
        $process_info = ProcessType::where('form_url', $form_url)->first([
            'id as process_type_id',
            'acl_name',
            'form_id',
            'name'
        ]);
        return (new ReuseController($process_info->process_type_id, $process_info))->getPaymentDataByLicense($request);
    }

    public function addRow($form_url, Request $request){
        $process_info = ProcessType::where('form_url', $form_url)->first([
            'id as process_type_id',
            'acl_name',
            'form_id',
            'name'
        ]);
        return (new ReuseController($process_info->process_type_id, $process_info))->addRow($request);
    }

    public function checkApplicationLimit($form_url, Request $request){
        $process_info = ProcessType::where('form_url', $form_url)->first([
            'id as process_type_id',
            'acl_name',
            'form_id',
            'name'
        ]);
        return (new ReuseController($process_info->process_type_id, $process_info))->checkApplicationLimitByArea($request);
    }

    public function checkOccupancyAvailability(){
        $query = "SELECT
                CASE
                WHEN ai.area_type=1 THEN 'Division'
                WHEN ai.area_type=2 THEN 'District'
                WHEN ai.area_type=3 THEN 'Thana/Upazilla'
                END AS Area_Type,
                ai.area_nm Area_Name,ai.app_limit Scope,
                COUNT(division_license.id) + COUNT(district_license.id) + COUNT(upazila_license.id) AS Existing,
                IF(ai.app_limit <=(COUNT(DISTINCT division_license.id)+COUNT(DISTINCT district_license.id)+COUNT(DISTINCT upazila_license.id)),'Full',
                (ai.app_limit - (COUNT(DISTINCT division_license.id)+COUNT(DISTINCT district_license.id)+COUNT(DISTINCT upazila_license.id)))) AS Occupancy

                FROM    area_info ai
                LEFT JOIN isp_license_issue AS division_license  ON division_license.isp_license_division = ai.area_id  AND division_license.isp_license_type = ai.area_type + 1 AND division_license.`license_no` IS NOT NULL
                LEFT JOIN isp_license_issue AS district_license  ON district_license.isp_license_district = ai.area_id  AND district_license.isp_license_type = ai.area_type + 1 AND district_license.license_no IS NOT NULL
                LEFT JOIN isp_license_issue AS upazila_license  ON upazila_license.isp_license_upazila = ai.area_id  AND upazila_license.isp_license_type = ai.area_type + 1 AND upazila_license.license_no IS NOT NULL
                GROUP BY ai.area_id";

        $occupancy_information = DB::select(DB::raw($query));
        // LEFT JOIN isp_license_issue AS division_license  ON division_license.isp_license_division = ai.area_id  AND division_license.isp_license_type = ai.area_type + 1 AND division_license.`license_no` IS NOT NULL AND division_license.`cancellation_tracking_no` IS  NULL
        // LEFT JOIN isp_license_issue AS district_license  ON district_license.isp_license_district = ai.area_id  AND district_license.isp_license_type = ai.area_type + 1 AND district_license.license_no IS NOT NULL AND division_license.`cancellation_tracking_no` IS  NULL
        // LEFT JOIN isp_license_issue AS upazila_license  ON upazila_license.isp_license_upazila = ai.area_id  AND upazila_license.isp_license_type = ai.area_type + 1 AND upazila_license.license_no IS NOT NULL AND division_license.`cancellation_tracking_no` IS  NULL

        return view('REUSELicenseIssue::ISP.Issue.checkOccupancyAvailabilityOfISP', compact('occupancy_information'));
    }

    public function generateLicenseJson($index=0){

        $process_list= ProcessList::leftjoin('isp_license_issue','isp_license_issue.id','process_list.ref_id')
        ->whereIn('process_list.process_type_id',1)
        ->whereNull('process_list.license_json')
        ->select([
            'isp_license_issue.isp_license_type',
            'isp_license_issue.isp_license_division',
            'isp_license_issue.isp_license_district',
            'isp_license_issue.isp_license_upazila',
            'process_list.id',
            'process_list.ref_id',
        ])
        ->get();
        $i=0;
        foreach($process_list as $process_data){

            $license_json_data=[];
            $license_name = DB::table('license_type')
            ->where('id', $process_data->isp_license_type)
            ->pluck('name')
            ->first();
            $div_name = DB::table('area_info')
            ->where('area_id',$process_data->isp_license_division)
            ->where('area_type', 1)
            ->pluck('area_nm')
            ->first();
            $dist_name = DB::table('area_info')
            ->where('area_id', $process_data->isp_license_district)
            ->where('area_type', 2)
            ->pluck('area_nm')
            ->first();
            $upz_name = DB::table('area_info')
            ->where('area_id', $process_data->isp_license_upazila)
            ->where('area_type', 3)
            ->pluck('area_nm')
            ->first();

            if(isset($license_name)){
                $license_json_data['License Type']= ($license_name)?$license_name :'' ;
            }else{
                $license_json_data['License Type']= null;
            }

            if(isset($div_name)){
                $license_json_data['Division']= ($div_name)? $div_name: ''  ;
            }
            if(isset($dist_name)){
                $license_json_data['District']= ($dist_name)? $dist_name: '';
            }
            if(isset($upz_name)){
                $license_json_data['Upazilla']= ($upz_name)?$upz_name : '';
            }
            $processListObj=ProcessList::find($process_data->id);
            $processListObj->license_json =json_encode($license_json_data);
            $processListObj->save();
            $i++;
            if($i==$index){
                break;
            }

        }

      return "License generated successfully";
    }
}
