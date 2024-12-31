<?php
namespace App\Modules\ReportsV2\Http\Controllers;

use App\Exports\ReportExportCsv;
use App\Http\Requests\ReportsRequest;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\UtilFunction;
use App\Libraries\Utility;
use App\Modules\ReportsV2\Models\FavReports;
use App\Modules\ReportsV2\Models\ReportCategory;
use App\Modules\ReportsV2\Models\ReportRecentActivates;
use App\Modules\reports\Models\ReportRequestList;
use App\Modules\ReportsV2\Models\Reports;
use App\Modules\ReportsV2\Models\ReportsMapping;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UserTypes;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
//use App\Libraries\ReportHelper;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\DB;
use App\Modules\ReportsV2\Models\ReportHelperModel;
use App\Modules\ReportsV2\Models\HelperModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use yajra\Datatables\Datatables;

class ReportsControllerV2 extends Controller {

    public function __construct(){
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));

    }

    public function index()
    {

        $getList['result'] = Reports::leftJoin('custom_reports_mapping as rm','rm.report_id','=','custom_reports.report_id')
            ->leftJoin('report_category', 'report_category.id', '=', 'custom_reports.category_id')
            ->where(function($query){
                //Sys Admin and MIS user will get all pluck
                if(Auth::user()->user_type != '1x101' and Auth::user()->user_type != '10x101' and  Auth::user()->user_type != '15x151' and Auth::user()->user_type != '7x707'){
                    $query->where('rm.user_type', Auth::user()->user_type)
                        ->where('custom_reports.status', 1)
                        ->where('rm.selection_type',2);
                }
            })
            ->orWhere(function($query){
                if(Auth::user()->user_type != '1x101' and Auth::user()->user_type != '10x101' and  Auth::user()->user_type != '15x151' and Auth::user()->user_type != '7x707') {
                    $query->where('rm.user_id', Auth::user()->id)
                        ->where('custom_reports.status', 1)
                        ->where('rm.selection_type', 1);
                }
            })

            ->orWhere(function($query){
                if(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '10x101' || Auth::user()->user_type == '15x151' || Auth::user()->user_type == '7x707') {
                    $query->where('custom_reports.status', 1);
                }
            })
            ->groupBy('custom_reports.report_id')
            ->get(['custom_reports.report_id','report_title','custom_reports.status', 'custom_reports.updated_at', 'report_category.category_name']);

        $getFavouriteList['fav_report'] = FavReports::join('custom_reports','custom_reports.report_id','=','custom_favorite_reports.report_id')
            ->leftJoin('report_category', 'report_category.id', '=', 'custom_reports.category_id')
                                        ->where('custom_favorite_reports.user_id', Auth::user()->id)
                                        ->where('custom_favorite_reports.status',1)
                                        ->orderBy('custom_reports.report_title')
                                        ->get(['custom_reports.report_id','report_title','custom_reports.status', 'report_category.category_name', 'custom_reports.updated_at']);



        $getLast4Reports = ReportRecentActivates::leftJoin('custom_reports', 'custom_reports.report_id', '=', 'report_recent_activates.report_id')
            ->leftJoin('report_category', 'report_category.id', '=', 'custom_reports.category_id')
            ->where('report_recent_activates.is_active', 1)
            ->where('report_recent_activates.user_id', Auth::user()->id)
            ->where("report_recent_activates.updated_at",">", Carbon::now()->subMonths(6))
            ->orderBy('report_recent_activates.updated_at', 'desc')
            ->limit(4)
            ->get([
                'custom_reports.report_id',
                'custom_reports.report_title',
            ]);



        // query for getting unpublished pluck
        $query = Reports::leftJoin('custom_reports_mapping as rm', 'rm.report_id', '=', 'custom_reports.report_id')
            ->leftJoin('report_category', 'report_category.id', '=', 'custom_reports.category_id');
        if(Auth::user()->user_type != '1x101' and Auth::user()->user_type != '10x101' and Auth::user()->user_type != '15x151' and Auth::user()->user_type != '7x707')
        {
            $query = $query->where('rm.user_type', Auth::user()->user_type);
        }
        $getUnpublishedList = $query->where('custom_reports.status',0)
            ->groupBy('custom_reports.report_id')
            ->get(['custom_reports.report_id','report_title','custom_reports.status', 'custom_reports.updated_at', 'report_category.category_name']);


        // query for getting favourite reports
//        $favouriteReports = ReportRecentActivates::leftJoin('custom_reports', 'custom_reports.report_id', '=', 'report_recent_activates.report_id')
//            ->where('report_recent_activates.type', 'Favourites')
//            ->where('report_recent_activates.is_active', 1)
//            ->where('report_recent_activates.user_id', Auth::user()->id)
//            ->where("report_recent_activates.updated_at",">", Carbon::now()->subMonths(6))
//            ->orderBy('report_recent_activates.updated_at', 'desc')
//            ->get([
//                'report_recent_activates.*',
//                'custom_reports.report_title',
//            ]);

        // query for getting published reports
//        $publishedReports = ReportRecentActivates::leftJoin('custom_reports', 'custom_reports.report_id', '=', 'report_recent_activates.report_id')
//            ->where('report_recent_activates.type', 'Published')
//            ->where('report_recent_activates.is_active', 1)
//            ->where('report_recent_activates.user_id', Auth::user()->id)
//            ->where("report_recent_activates.updated_at",">", Carbon::now()->subMonths(6))
//            ->orderBy('report_recent_activates.updated_at', 'desc')
//            ->get([
//                'report_recent_activates.*',
//                'custom_reports.report_title',
//            ]);


        // query for getting category reports

        $Categories = ReportRecentActivates::leftJoin('custom_reports', 'custom_reports.report_id', '=', 'report_recent_activates.report_id')
            ->join('report_category', 'custom_reports.category_id', '=', 'report_category.id')
            ->whereIn('report_recent_activates.type', ['Published','Favourites'])
            ->whereNotNull('custom_reports.category_id')
            ->where('report_recent_activates.is_active', 1)
            ->where('report_recent_activates.is_archived', 0)
            ->where('report_recent_activates.user_id', Auth::user()->id)
            ->where("report_recent_activates.updated_at",">", Carbon::now()->subMonths(6))
            ->orderBy('report_category.category_name')
            ->groupBy('custom_reports.category_id')
            ->get([
                DB::raw('GROUP_CONCAT(custom_reports.report_id, "=", custom_reports.report_title   order by custom_reports.report_title SEPARATOR "@") AS groupData'),
                'custom_reports.category_id',
                'report_category.category_name',
            ]);
//        dd($Categories);


        $uncategorized = ReportRecentActivates::leftJoin('custom_reports', 'custom_reports.report_id', '=', 'report_recent_activates.report_id')
            ->where('report_recent_activates.type', 'Published')
            ->whereNull('custom_reports.category_id')
            ->where('report_recent_activates.is_active', 1)
            ->where('report_recent_activates.is_archived', 0)
            ->where('report_recent_activates.user_id', Auth::user()->id)
            ->where("report_recent_activates.updated_at",">", Carbon::now()->subMonths(6))
            ->orderBy('custom_reports.report_title')
            ->get([
                'report_recent_activates.*',
                'custom_reports.report_title',
                'custom_reports.category_id',
            ]);

        return view("ReportsV2::list", compact('getList',
            'getFavouriteList','getUnpublishedList',
            'Categories','uncategorized', 'getLast4Reports'));
    }

    public function create()
    {
        $usersList = UserTypes::orderBy('type_name')->pluck('type_name','id')->toArray();
        return view("ReportsV2::create", ['usersList' => $usersList]);
    }

    public function store(ReportsRequest $request)
    {

        $sqlcontent = $request->getContent();
        $queryarray = parse_str($sqlcontent, $output);
        try{
            DB::beginTransaction();
            if(!ACL::getAccsessRight('reportv2','A')) die ('no access right!');
            $selection_type=$request->get('selection_type');

            if (!empty($request->get('tags_value'))){

                $reportCategory_id = $request->get('tags_value');

            }else{
                if (!empty($request->get('report_category'))){
                    $reportCategory = new ReportCategory();
                    $reportCategory->category_name = $request->get('report_category');
                    $reportCategory->status = 1;
                    $reportCategory->is_archive = 0;
                    $reportCategory->save();
                    $reportCategory_id = $reportCategory->id;
                }else{
                    $reportCategory_id = null;
                }
            }

            $reports = Reports::create([
                'category_id' => $reportCategory_id,
                'report_title' => $request->get('report_title'),
                'report_para1' => Encryption::dataEncode($output['report_para1']),
                'selection_type'=>$selection_type,
                'status' => $request->get('status'),
                'user_id' => 0,
                'updated_by' => 1
            ]);


            if ($selection_type==1){
                if($request->get('users')) {
                    foreach ($request->get('users') as $user_id) {
                        ReportsMapping::create([
                            'user_type' =>$this->getusertype($user_id),
                            'report_id' => $reports->id,
                            'selection_type'=>$selection_type,
                            'user_id'=> $user_id
                        ]);
                    }
                }
            }elseif($request->get('selection_type')==2){
                if($request->get('user_id')) {
                    foreach ($request->get('user_id') as $user_type) {
                        ReportsMapping::create([
                            'user_type' => $user_type,
                            'selection_type'=>$selection_type,
                            'report_id' => $reports->id
                        ]);
                    }
                }
            }else{
                dd(13);
            }

            DB::commit();
            Session::flash('success', 'Successfully Saved the Report.');
            return $request->redirect_to_new == 1 ? redirect('/reportv2/view/' . Encryption::encode($reports->id.'/save_and_run')) : redirect('/reportv2/edit/' . Encryption::encodeId($reports->id));
        }catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', Utility::eMsg($e, 'RPC001'));
            return Redirect::back()
//                ->withMessage($message_fail)
//                ->withErrors($validator)
                ->withInput();
        }
    }

    function getusertype($user_id){
        $user_type=Users::where('id',$user_id)->first(['user_type']);
        return $user_type->user_type;

    }


    public function edit($id, Request $request)
    {
        if(!ACL::getAccsessRight('reportv2','E')) die ('no access right!');
        $report_id = Encryption::decodeId($id);
        $report_data = Reports::leftJoin('report_category', 'report_category.id', '=', 'custom_reports.category_id')
            ->where('custom_reports.report_id', $report_id)->first([
                'custom_reports.*',
                'report_category.category_name',
            ]);
        $usersList = UserTypes::orderBy('type_name')->pluck('type_name','id')->toArray();
        $selected_user_type = ReportsMapping::where('report_id',$report_id)
            ->groupBy('user_type')
            ->pluck('user_type')->toArray();
        $selection_type=$report_data->selection_type;
        $select=[];
        $selected_users=Users::select('id','user_email',DB::raw('CONCAT(user_first_name) AS user_full_name'))
            ->whereIn('user_type',$selected_user_type)->get();
        if($report_data->selection_type==1){
            $select= ReportsMapping::where('report_id',$report_id)->pluck('user_id')->toArray();
        }
        return view("ReportsV2::edit",compact('report_data','selection_type','usersList','selected_users','select','selected_user_type'));
    }


    public function update($id, ReportsRequest $request)
    {
        $sqlcontent = $request->getContent();
        $queryarray = parse_str($sqlcontent, $output);
        if(!ACL::getAccsessRight('reportv2','E')) die ('no access right!');
        $report_id = Encryption::decodeId($id);
        $selection_type=$request->get('selection_type');
        if (!empty($request->get('tags_value'))){
            Reports::where('report_id', $report_id)->update([
                'category_id' => $request->get('tags_value'),
                'report_title' => $request->get('report_title'),
                'report_para1' => Encryption::dataEncode($output['report_para1']),
                'selection_type'=>$selection_type,
                'status' => $request->get('status'),
                'user_id' => 0,
                'updated_by' => CommonFunction::getUserId()
            ]);
        }else{
            if (!empty($request->get('report_category'))){
                $reportCategory = new ReportCategory();
                $reportCategory->category_name = $request->get('report_category');
                $reportCategory->status = 1;
                $reportCategory->is_archive = 0;
                $reportCategory->save();
                $reportCategory_id = $reportCategory->id;
            }else{
                $reportCategory_id = null;
            }


            Reports::where('report_id', $report_id)->update([
                'category_id' => $reportCategory_id,
                'report_title' => $request->get('report_title'),
                'report_para1' => Encryption::dataEncode($output['report_para1']),
                'selection_type'=>$selection_type,
                'status' => $request->get('status'),
                'user_id' => 0,
                'updated_by' => CommonFunction::getUserId()
            ]);

        }

        Reports::where('report_id', $report_id)->update([
            'report_title' => $request->get('report_title'),
            'report_para1' => Encryption::dataEncode($output['report_para1']),
            'selection_type'=>$selection_type,
            'status' => $request->get('status'),
            'user_id' => 0,
            'updated_by' => CommonFunction::getUserId()
        ]);

        ReportsMapping::where('report_id',$report_id)->delete();
        if ($selection_type==1){
            if($request->get('users')) {
                foreach ($request->get('users') as $user_id) {
                    ReportsMapping::create([
                        'user_type' =>$this->getusertype($user_id),
                        'selection_type'=>$selection_type,
                        'report_id' => $report_id,
                        'user_id'=> $user_id
                    ]);
                }
            }
        }elseif($request->get('selection_type')==2){
            if($request->get('user_id')) {
                foreach ($request->get('user_id') as $user_type) {
                    ReportsMapping::create([
                        'user_type' => $user_type,
                        'selection_type'=>$selection_type,
                        'report_id' => $report_id
                    ]);
                }
            }
        }else{
            dd(13);
        }
        Session::flash('success', 'Successfully Updated the Report.');
//        dd($request->all());
        return $request->redirect_to_new == 1 ? redirect('/reportv2/view/' . Encryption::encode($report_id.'/save_and_run')) : redirect('/reportv2/edit/' . Encryption::encodeId($report_id));
    }


    public function reportsVerify(Request $request) {

        $obj = new HelperModel();
        $sqlcontent = $request->getContent();
        $queryarray = parse_str($sqlcontent, $output);
        $sql = $output['sql'];

        $sql = preg_replace('/&gt;/','>',$sql);
        $sql = preg_replace('/&lt;/','<',$sql);

        echo '<hr /><code>'.$sql.'</code><hr />';
        $sql = $this->sqlSecurityGate($sql);
        $result=null;
        try {
            $result = DB::select(DB::raw($sql));
        } catch(QueryException $e) {
            echo $e->getMessage();
        }

        if($result){
            $result2 = array();
            foreach ($result as $value):
                $result2[] = $value;
                if (count($result2) > 99){
                    break;
                }
            endforeach;
            echo '<p></p><pre>';
            echo $obj->createHTMLTable($result2);
            echo '</pre>';
            echo 'showing ' . count($result2) . ' of '.count($result);
            echo '</p>';
        }
    }

    public function sqlSecurityGate($sql) {
        $sql = trim($sql);
        if(strlen($sql)<8){
            dd('Sql is not Valid: ' . $sql);
        }
        $select_keyword = strtoupper(substr($sql, 0, 7));
        $semicolon = strpos($sql, ';');
        if (($select_keyword == 'SELECT ') AND $semicolon == '') {
            return $sql;
        }elseif ((substr($select_keyword,0,5) == 'SHOW ' OR $select_keyword== 'EXPLAIN' OR substr($select_keyword,0,5) == 'DESC ')
            AND $semicolon == '' AND (Auth::user()->user_type=='1x101' OR Auth::user()->user_type=='10x101' OR Auth::user()->user_type=='15x151' OR Auth::user()->user_type=='7x707')) {
            return $sql;
        } else {
            dd('Sql is not Valid: ' . $sql);
        }
    }

    public function showTables(Request $request) {

        if($request->session()->has('db_tables')){
            echo $request->session()->get('db_tables');
        } else {
            $tables = DB::select(DB::raw('show tables'));
            $count = 1;
            $ret = '<ul class="table_pluck">';
            foreach ($tables as $table) {
                $table2 = json_decode(json_encode($table), true);

                $ret .= '<li class="table_name table_' . $count . '"><strong>' . $table2[key($table2)] .'</strong><br/>';
                $fields = DB::select(DB::raw('show fields from ' . $table2[key($table2)]));

                $fileds='';
                foreach ($fields as $field) {
                    $fileds .=  strlen($fileds)>0? ', '.$field->Field:''.$field->Field;
                }
                $ret .= $fileds;

                $ret .= '</li>';
                $count++;
            }
            $ret .= '</ul>';
            $request->session()->put('db_tables', $ret);
            echo $ret;
        }
    }
    public function view($report_id = '')
    {
//        $objRh = new ReportHelperModel();
        $report_ids = explode("/", Encryption::decode($report_id));
        $report_id2 = $report_ids[0];// report id
        $report_id = Encryption::encodeId($report_id2);

        $fav_report_info = FavReports::where('report_id', $report_id2)
            ->where('user_id',Auth::user()->id)
            ->first();
        $report_unpublished_info = Reports::where('report_id', $report_id2)
            ->first();

        // Report Admins are out of this check
        // check that the favourite report is published or not
        // check that the favourite report is assigned or not
        if (in_array(Auth::user()->user_type,Reports::isReportAdmin()) != true)
        {
            if ($fav_report_info != null)
            {
                $is_publish = Reports::where([
                    'report_id' => $report_id2,
                    'status' => 1
                ])->count();
                $is_assigned = ReportsMapping::where([
                    'report_id' => $report_id2,
                    'user_type' => Auth::user()->user_type
                ])->count();
                if ($is_publish == 0 || $is_assigned == 0)
                {
                    Session::flash('error', 'Sorry, This Report is unpublished or unassigned to your user type.');
                    return redirect('reports');
                }
            }
            if ($report_unpublished_info != null)
            {
                if ($report_unpublished_info->status == 0)
                {
                    Session::flash('error', 'Sorry, This Report is unpublished or unassigned to your user type.');
                    return redirect('reportv2');
                }
            }
        }
        $encode_SQL = '';
        $search_keys = '';
        $report_data = Reports::where('report_id', $report_id2)->first();
        $content = Encryption::dataDecode($report_data->report_para1);
//        $reportParameter=$objRh->getSQLPara($content);

        if(isset($report_ids[1]) && $report_ids[1] != "save_and_run"){
            CommonFunction::storeReportRecentActivates($report_id2, $report_ids[1] ?? "");
        }

        $token = $this->getReportToken();
        $data = array("content" => $content);

        $postData = json_encode($data);
        $report_api_url = config('stackholder.report_api_url');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $report_api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token ",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            Session::flash('error', 'API calling error: '. $error_msg);
            return \redirect()->back();
        }
        curl_close($curl);

        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
        Session::put('results',$results);

        return view('ReportsV2::reportInputForm',compact('report_id','report_data','fav_report_info','encode_SQL','search_keys', 'results'));


    }

    public function showReport($report_id, Request $request)
    {

        //the report version 2 system is being temporarily paused and analyzed for errors

        //         $objRh = new ReportHelperModel();

        //         $report_id2 = Encryption::decodeId($report_id);
        //         $fav_report_info = FavReports::where('report_id', $report_id2)
        //             ->where('user_id',Auth::user()->id)
        //             ->first();
        //         $report_unpublished_info = Reports::where('report_id', $report_id2)
        //             ->first();

        //         // Report Admins are out of this check
        //         // check that the favourite report is published or not
        //         // check that the favourite report is assigned or not
        //         if (in_array(Auth::user()->user_type,Reports::isReportAdmin()) != true)
        //         {
        //             if ($fav_report_info != null)
        //             {
        //                 $is_publish = Reports::where([
        //                     'report_id' => $report_id2,
        //                     'status' => 1
        //                 ])->count();
        //                 $is_assigned = ReportsMapping::where([
        //                     'report_id' => $report_id2,
        //                     'user_type' => Auth::user()->user_type
        //                 ])->count();
        //                 if ($is_publish == 0 || $is_assigned == 0)
        //                 {
        //                     Session::flash('error', 'Sorry, This Report is unpublished or unassigned to your user type.');
        //                     return redirect('reportv2');
        //                 }
        //             }
        //             if ($report_unpublished_info != null)
        //             {
        //                 if ($report_unpublished_info->status == 0)
        //                 {
        //                     Session::flash('error', 'Sorry, This Report is unpublished or unassigned to your user type.');
        //                     return redirect('reportv2');
        //                 }
        //             }
        //         }

        //         if (!$request->all()) {
        //             return redirect('reportv2/view/' . $report_id);
        //         }
        //         $reportId = Encryption::decodeId($report_id);
        //         $reportId = is_numeric($reportId) ? $reportId : null;
        //         if (!$reportId) {
        //             return redirect('dashboard');
        //         }

        //         $searchKey = array();
        //         $data = array();
        //         foreach ($request->all() as $key => $row) {
        //             if (substr($key, 0, 4) == 'rpt_') {
        //                 $searchKey[] = $row;
        //                 $data[$key] = $request->get($key);
        //                 $request->session()->put($key, $request->get($key));
        //             }
        //             elseif (substr($key, 0, 5) == 'sess_') {
        //                 $searchKey[] = $row;
        //                 $data[$key] = session($key);
        //             } else {
        //                 $searchKey[] = $row;
        //                 $data[$key] = $request->get($key);
        //             }
        //         }
        //         if ($request->get('export_csv')) {
        //             $this->exportCSV($reportId, $data);
        //         } elseif ($request->get('export_csv_zip')) {
        //             $this->exportCSV_Zip($reportId, $data);
        //         }  elseif ($request->get('crystal_report')) {
        //             return $this->generateCrystalReport($reportId, $data, $searchKey,$request->ajax());
        //         } else{
        //             $report_data = Reports::where('report_id', $reportId)->first();
        //             $report_para1 = Encryption::dataDecode($report_data->report_para1);

        //             $SQL = $objRh->ConvParaEx($report_para1, $data);

        //             $token = $this->getReportToken();
        //             unset($data["_token"]);
        //             $data = array("content" => $report_para1,"params" => $data);

        //             $postData = json_encode($data);
        //             $report_api_url = config('stackholder.report_api_url_replace_params');
        //             $curl = curl_init();
        //             curl_setopt_array($curl, array(
        //                 CURLOPT_URL => $report_api_url,
        //                 CURLOPT_RETURNTRANSFER => true,
        //                 CURLOPT_ENCODING => '',
        //                 CURLOPT_MAXREDIRS => 10,
        //                 CURLOPT_TIMEOUT => 0,
        //                 CURLOPT_SSL_VERIFYHOST => 0,
        //                 CURLOPT_SSL_VERIFYPEER => 0,
        //                 CURLOPT_FOLLOWLOCATION => true,
        //                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //                 CURLOPT_CUSTOMREQUEST => 'POST',
        //                 CURLOPT_POSTFIELDS => $postData,
        //                 CURLOPT_HTTPHEADER => array(
        //                     "Authorization: Bearer $token ",
        //                     "Content-Type: application/json"
        //                 ),
        //             ));

        //             $response = curl_exec($curl);

        //             $decoded_response = json_decode($response, true);
        //             $results = $decoded_response['data'];



        //             $searchKey = implode(',',$searchKey);
        //             $search_keys = Encryption::dataEncode($searchKey);
        // //            $encode_SQL = Encryption::dataEncode($SQL);

        //             try {
        //                 $recordSet = DB::select(DB::raw($results));

        // //                dd($recordSet);
        //                 return view('ReportsV2::reportGenerate', compact('recordSet', 'report_id', 'report_data','search_keys', 'results', 'fav_report_info'));

        //             } catch (QueryException $e) {
        //                 Session::flash('error', $e->getMessage());
        //                 return redirect('reportv2');
        //             }
        //         }

        // restart the updated version and close the old version after the analysis is complete.

        $objRh = new ReportHelperModel();
        $report_id2 = Encryption::decodeId($report_id);
        $fav_report_info = FavReports::where('report_id', $report_id2)
        ->where('user_id', Auth::user()->id)
        ->first();

        if (!$request->all()) {
            return redirect('reports/view/' . $report_id);
        }
        $reportId = Encryption::decodeId($report_id);
        $reportId = is_numeric($reportId) ? $reportId : null;
        if (!$reportId) {
            return redirect('dashboard');
        }

        $searchKey = array();
        $data = array();
        foreach ($request->all() as $key => $row) {
            if (substr($key, 0, 4) == 'rpt_') {
                $searchKey[] = $row;
                $data[$key] = $request->get($key);
                $request->session()->put($key, $request->get($key));
            } elseif (substr($key, 0, 5) == 'sess_') {
                $searchKey[] = $row;
                $data[$key] = session($key);
            } else {
                $searchKey[] = $row;
                $data[$key] = $request->get($key);
            }
        }
        if ($request->get('export_csv')) {
            return $this->exportCSV($reportId, $data);
        } elseif ($request->get('export_csv_zip')) {
            $this->exportCSV_Zip($reportId, $data);
        } elseif ($request->get('crystal_report')) {
            return $this->generateCrystalReport($reportId, $data, $searchKey, $request->ajax());
        } else {
            $report_data = Reports::where('report_id', $reportId)->first();
            $reportParameter = $objRh->getSQLPara(Encryption::dataDecode($report_data->report_para1));
            $SQL = $objRh->ConvParaEx(Encryption::dataDecode($report_data->report_para1), $data);
            $searchKey = implode(',', $searchKey);
            $search_keys = Encryption::dataEncode($searchKey);
            $encode_SQL = Encryption::dataEncode($SQL);
            try {
                $recordSet = DB::select(DB::raw($SQL));
                return view('ReportsV2::reportGenerate', compact('recordSet', 'report_id', 'report_data', 'reportParameter', 'encode_SQL', 'search_keys', 'fav_report_info'));
            } catch (QueryException $e) {
                Session::flash('error', $e->getMessage());
                return redirect('reports');
            }
        }

    }

    public function addToFavourite($id)
    {
        $report_id = Encryption::decodeId($id);
        try
        {
            $existing_fav_report = FavReports::where('report_id',$report_id)
                                        ->where('user_id',Auth::user()->id)
                                        ->count();
            if ($existing_fav_report > 0)
            {
                FavReports::where('report_id',$report_id)
                    ->where('user_id',Auth::user()->id)
                    ->update([
                        'status' => 1,
                        'updated_by' => CommonFunction::getUserId()
                    ]);
            }
            else{
                FavReports::create([
                    'user_id' => Auth::user()->id,
                    'report_id' => $report_id,
                    'status' => 1
                ]);
            }
            return Redirect()->back();
        }
        catch (\Exception $e)
        {
            Session::flash('error', Utility::eMsg($e, 'RPC002'));
            return Redirect::back();
        }
    }

    public function addRemoveFavouriteAjax(Request $request){
        $report_id = Encryption::decodeId($request->report_id);
        $report_status = $request->status;

        try
        {
            $existing_fav_report = FavReports::where('report_id',$report_id)
                ->where('user_id',Auth::user()->id)
                ->count();
            if ($report_status == 'add_fav'){
                if ($existing_fav_report > 0)
                {
                    FavReports::where('report_id',$report_id)
                        ->where('user_id',Auth::user()->id)
                        ->update([
                            'status' => 1,
                            'updated_by' => CommonFunction::getUserId()
                        ]);
                }
                else{
                    FavReports::create([
                        'user_id' => Auth::user()->id,
                        'report_id' => $report_id,
                        'status' => 1
                    ]);
                }
            }else{
                FavReports::where('report_id',$report_id)
                    ->where('user_id',Auth::user()->id)
                    ->update([
                        'status' => 0,
                        'updated_by' => CommonFunction::getUserId()
                    ]);
            }

            return response()->json();
        }
        catch (\Exception $e)
        {
            Session::flash('error', Utility::eMsg($e, 'RPC002'));

        }
    }

    public function removeFavourite($id)
    {
        $report_id = Encryption::decodeId($id);
        try
        {
            FavReports::where('report_id',$report_id)
                        ->where('user_id',Auth::user()->id)
                        ->update([
                            'status' => 0,
                            'updated_by' => CommonFunction::getUserId()
                        ]);
            return Redirect::back();
        }
        catch (\Exception $e)
        {
            Session::flash('error', Utility::eMsg($e, 'RPC003'));
            return Redirect::back();
        }
    }

    public function exportCSV($id, $data) {

        $objRh = new ReportHelperModel();
        $reportData = DB::select(DB::raw("SELECT * FROM custom_reports WHERE REPORT_ID='$id'"));
        $reportData = json_decode(json_encode($reportData));
        $name = $reportData['0']->report_title.'-'.$id.'-'.Carbon::now().'.csv';
        $report_name = str_replace(' ','_',$name) ;
        $report_name = str_replace('/','', $report_name);

        try {
            $SQL = base64_decode($reportData['0']->report_para1);
            $SQL = $objRh->ConvParaEx($SQL, $data);
            $data = DB::select(DB::raw($SQL));

            if(empty($data)){
                return redirect()->back()->with('error', 'Data Not Found');
            }

            $headers= array_keys((array) $data[0]); // get header row from first index of array
            array_unshift($data, $headers); // push to to index
            $export = new ReportExportCsv($data); // get collection data
            return Excel::download($export, $report_name);

            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=$report_name");

            if ($data && count($data[0]) > 0) {
                $rc = 0;
                foreach ($data[0] as $key => $value) {
                    if ($rc > 0) {
                        echo ',';
                    } $rc++;
                    echo "$key";
                }
                echo "\r\n";
                foreach ($data as $row):
                    $rc = 0;
                    foreach ($row as $key => $field_value):
                        if ($rc > 0) {
                            echo ',';
                        } $rc++;
                        if (empty($field_value)) {
                            //
                        } else if (strlen($field_value) > 10) {
                            echo '"' . addslashes($field_value) . '"';
                        } else if (is_numeric($field_value)) {
                            echo $field_value;
                        } else {
                            echo '"' . addslashes($field_value) . '"';
                        }
                    endforeach;
                    echo "\r\n";
                endforeach;
            } else {
                echo "Data Not Found!";
            }

            // This exit will remaining
            exit();
        } catch (QueryException $e) {
            echo "CSV can't generate for following error: ";
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            return redirect('re');

        }
    }
    public function exportCSV_Zip($id, $data) {

        $this->exportCSV($id, $data);
        exit();
        dd('zip library not found');
        $objRh = new ReportHelperModel();
        $this->load->library('zip');


        $reportData = $this->db->query("SELECT REPORT_PARA1 FROM custom_reports WHERE REPORT_ID='$id'")->result_array();

        $SQL = $reportData['0']['REPORT_PARA1'];
        $SQL = $objRh->ConvParaEx($SQL, $data);
        $data = $this->db->query($SQL)->result_array();
        $csv_data = '';
        if ($data && count($data[0]) > 0) {
            $rc = 0;
            foreach ($data[0] as $key => $value) {
                if ($rc > 0) {
                    $csv_data .= ',';
                } $rc++;
                $csv_data .= "$key";
            }
            $csv_data .= "\r\n";
            foreach ($data as $row):
                $rc = 0;
                foreach ($row as $key => $field_value):
                    if ($rc > 0) {
                        $csv_data .= ',';
                    } $rc++;
                    if (empty($field_value)) {
                        //
                    } else if (strlen($field_value) > 10) {
                        $csv_data .= '"' . addslashes($field_value) . '"';
                    } else if (is_numeric($field_value)) {
                        $csv_data .= $field_value;
                    } else {
                        $csv_data .= '"' . addslashes($field_value) . '"';
                    }
                endforeach;
                $csv_data .= "\r\n";
            endforeach;
        } else {
            $csv_data .= "Data Not Found!";
        }

        $folder_name = "reports_of_$id";
        $name = "report_$id.csv";
        $this->zip->add_data($name, $csv_data);

// Write the zip file to a folder on your server. Name it "report_id.zip"
//        $this->zip->archive(base_url()."csv/$name.zip");
// Download the file to your desktop. Name it "report_id.zip"
        $this->zip->download("$folder_name.zip");
//        redirect(site_url('reports/index' . $this->encryption->encode($id)));
    }
    public function getusers(Request $request){
        $users=Users::select('id','user_email',DB::raw('CONCAT(user_first_name) AS user_full_name'))
        ->whereIn('user_type',$request->get('types'))->get();
        return response()->json($users);
    }

    public function generateCrystalReport($id, $requestData, $searchKey, $requestSubmittedByAjax) {

        if(!$requestSubmittedByAjax){ // go back if not submit by ajax request
            return redirect()->back();
        }
        $pdfReportController = new PdfReportControllerV2();
        $objRh = new ReportHelperModel();

        $searchKey = implode(',',$searchKey);
        $searchKeys = Encryption::dataEncode($searchKey); // searchkey will sent to view page, so encoded.

        $report_data = Reports::where('report_id', $id)->first();
        $SQL = Encryption::dataDecode($report_data->report_para1);
        $SQL = $objRh->ConvParaEx($SQL, $requestData);  // generate the complete sql with parameter

        $response = (object)[]; // Cast empty array to object. result an empty object

        try {

            $response->report_id = $id;
            $response->report_sql = Encryption::dataEncode($SQL);
            $response->search_key = $searchKeys;
            $response->pdfurl = env('PDF_API_BASE_URL');
            $responseFromPdfGenerate =  $pdfReportController->generateCrystalReport($response);

            return response()->json($responseFromPdfGenerate);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Sorry Can not Generate Crystal Report ',
                'error_details' => $e->getMessage(),
            ]);

        }
    }

    public function getReportCategory(Request $request){

        $search = $request->get('term');

        $result = ReportCategory::where('category_name', 'LIKE', '%'. $search. '%')->get();

        return response()->json($result);
    }


    public function getReportToken(){
        if (Cache::has('report-client-token')) {
            return Cache::get('report-client-token');
        }
        // Get credentials from env
        $report_idp_url = config('stackholder.report_token_url');
        $report_client_id = config('stackholder.report_client_id');
        $report_client_secret = config('stackholder.report_client_secret');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $report_client_id,
            'client_secret' => $report_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$report_idp_url");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if(!$result){
            $data = ['responseCode' => 0, 'msg' => 'Area API connection failed!'];
            return response()->json($data);
        }
        curl_close($curl);
        $decoded_json = json_decode($result,true);
        $token = $decoded_json['access_token'];
        $expired_time = config('stackholder.report_token_expired_time');
        Cache::put('report-client-token', $token, Carbon::now()->addMinute($expired_time));
        return $token;
    }
}
