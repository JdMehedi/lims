<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Dashboard\Models\Dashboard;
use App\Modules\Dashboard\Models\DynamicPayment;
use App\Modules\Dashboard\Models\SpecialServiceAmmendment;
use App\Modules\Dashboard\Models\SpecialServiceIssue;
use App\Modules\Dashboard\Models\SpecialServiceMaster;
use App\Modules\Dashboard\Models\SpecialServiceRenew;
use App\Modules\Dashboard\Models\SpecialServiceSurrender;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\DynamicProcess;
use App\Modules\Settings\Models\EmailQueue;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use DB;

class DashboardController extends Controller
{
    use SPAfterPaymentManager;
    public function index(Dashboard $dashboard)
    {
        if (!auth()->check() && env('IS_MOBILE')) {
            if ($userId = CommonFunction::isMobileLogin()) {
                Auth::loginUsingId($userId);
            } else {
                return redirect()->route('login');
            }
        }

        $log = date('H:i:s', time());
        $dbMode = Session::get('DB_MODE');
        $log .= ' - ' . date('H:i:s', time());
        $log .= ' - ' . date('H:i:s', time());
        $dashboardObject = $dashboard->getDashboardObject();
        $pageTitle = 'Dashboard';

        $companyId = CommonFunction::getUserCompanyWithZero();
        $userDeskIds = CommonFunction::getUserDeskIds();
        $userOfficeIds = CommonFunction::getUserOfficeIds();
        $notices = CommonFunction::getNotice();


        if (!empty($companyId)) {
            Session::put('associated_company_name', CommonFunction::getCompanyNameById($companyId));
        } else {
            Session::forget('associated_company_name');
        }

        $userType = Auth::user()->user_type;

        $servicesWiseApplication = null;

        $dashboardObjectBarChart = null;
        $comboChartData = null;
        $appSubmitCount = 0;
        $appApproveCount = 0;
        $deshboardObject = [];
        $services = null;
        if ($userType == '1x101' || $userType == '10x101') {
            $deshboardObject = DB::table('dashboard_object')
                ->where('db_obj_caption', 'dashboard_old')
                ->where('db_obj_status', 1)
                ->get();
            $dashboardObjectBarChart = DB::table('dashboard_object')->where(
                'db_obj_type',
                'BAR_CHART'
            )->where('db_obj_status', 1)->get();
        }
        $userApplicaitons = [];

        if ($userType == '5x505') {
            $userApplicaitons = ProcessList::where('company_id', Auth::user()->working_company_id)->pluck('status_id');

            $approvedapp = 0;
            $processingapp = 0;
            $draftapp = 0;
            $rejectapp = 0;
            $shortfallapp = 0;

            if (count($userApplicaitons) > 0) {
                foreach ($userApplicaitons as $appStatus) {
                    if ($appStatus == 25) {
                        $approvedapp = $approvedapp + 1;
                    } elseif ($appStatus == '-1') {
                        $draftapp = $draftapp + 1;
                    } elseif ($appStatus == 5) {
                        $shortfallapp = $shortfallapp + 1;
                    } elseif ($appStatus == 6) {
                        $rejectapp = $rejectapp + 1;
                    } else {
                        $processingapp = $processingapp + 1;
                    }
                }
            }

            $totalapp = $approvedapp + $processingapp + $draftapp + $shortfallapp + $rejectapp;

            $userApplicaitons = [
                'draft' => $draftapp, 'processing' => $processingapp, 'approved' => $approvedapp,
                'totalapp' => $totalapp, 'shortfallapp' => $shortfallapp, 'rejectapp' => $rejectapp
            ];

            $servicesWiseApplication = ProcessType::whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->where('type_key', 'like', '%issue')
                ->where('is_special', 0)
                ->groupBy('group_name')
                ->orderBy('master_order_id', 'asc')
                ->get([DB::raw('group_concat(id) as process_type'), 'group_name', 'id', 'form_url', 'type_key']);
        } else {

            $services = DB::table('process_type')
                ->leftJoin('process_list', function ($on) use ($companyId, $userDeskIds, $userOfficeIds, $userType) {
                    $on->on('process_list.process_type_id', '=', 'process_type.id')
                        // ->where('process_list.desk_id', '!=', 0)
                        ->whereNotIn('process_list.status_id', [-1, 5]);


                    if ($userType == '4x404') {
                        $getSelfAndDelegatedUserDeskOfficeIds = CommonFunction::getSelfAndDelegatedUserDeskOfficeIds();
                        $on->where(function ($query1) use ($getSelfAndDelegatedUserDeskOfficeIds) {
                            $i = 0;
                            foreach ($getSelfAndDelegatedUserDeskOfficeIds as $data) {
                                $queryInc = '$query' . $i;

                                if ($i == 0) {
                                    $query1->where(function ($queryInc) use ($data) {
                                        $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                            ->where(function ($query3) use ($data) {
                                                $query3->where('process_list.user_id', $data['user_id'])
                                                    ->orWhere('process_list.user_id', 0);
                                            })
                                            ->whereIn('process_list.office_id', $data['office_ids']);
                                    });
                                } else {
                                    $query1->orWhere(function ($queryInc) use ($data) {
                                        $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                            ->where(function ($query3) use ($data) {
                                                $query3->where('process_list.user_id', $data['user_id'])
                                                    ->orWhere('process_list.user_id', 0);
                                            })
                                            ->whereIn('process_list.office_id', $data['office_ids']);
                                    });
                                }
                                $i++;
                            }
                        });
                    }
                })
                ->groupBy('process_type.id')
                ->select([
                    'process_type.name', 'process_type.name_bn', 'process_type.id', 'process_type.form_url',
                    'process_type.panel', 'process_type.icon', DB::raw('COUNT(process_list.process_type_id) as
                    totalApplication'), DB::raw('COUNT(process_list.process_type_id) as totalApplication')
                ])
                ->orderBy('process_type.id', 'asc')
                ->where('process_type.status', '=', 1)
                ->get();

            $lastSixMonthData = DB::select(DB::raw("SELECT  DATE_FORMAT(updated_at,'%m-%Y') AS month_year,
                                    COUNT(CASE WHEN status_id = 1 then 1 ELSE NULL END) as 'Submit',
                                    COUNT(CASE WHEN status_id = 25 then 1 ELSE NULL END) as 'Approved'
                                    FROM process_list
                                    WHERE updated_at BETWEEN CURDATE() - INTERVAL 6 MONTH AND CURDATE()
                                    AND status_id in (1,25)
                                    GROUP BY DATE_FORMAT(updated_at,'%m-%Y')
                                    ORDER BY updated_at ASC "));

            $appApproveCount = ProcessList::whereIn('status_id', [25])
                ->count();
            $appSubmitCount = ProcessList::whereIn('status_id', [1])
                ->count();

            $comboChartArray = [];
            if (count($lastSixMonthData) > 0) {
                foreach ($lastSixMonthData as $key => $data) {
                    $comboChartArray[$key][0] = $data->month_year;
                    $comboChartArray[$key][1] = $data->Submit;
                    $comboChartArray[$key][2] = $data->Approved;
                }
            } else {
                $comboChartArray[0][0] = 20;
                $comboChartArray[0][1] = 50;
                $comboChartArray[0][2] = 10;
            }
            array_unshift($comboChartArray, ['Month', 'Submit', 'Approved']);
            $comboChartData = (array_values($comboChartArray));
        }


        return view(
            'Dashboard::index',
            compact(
                'log',
                'dbMode',
                'services',
                'deshboardObject',
                'dashboardObject',
                'pageTitle',
                'dashboardObjectBarChart',
                'userApplicaitons',
                'servicesWiseApplication',
                'notices',
                'comboChartData',
                'appApproveCount',
                'appSubmitCount',
            )
        );
    }

    public function dashboard()
    {

        if (Auth::check()) {
            return view('Dashboard::index');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }


    public function notifications()
    {
        $notifications = EmailQueue::where('email_to', Auth::user()->user_email)
            ->where('web_notification', 0)
            ->whereNotIn('caption', ['PASSWORD_RESET_REQUEST', 'ONE_TIME_PASSWORD', 'TWO_STEP_VERIFICATION'])
            ->orWhere('email_cc', Auth::user()->user_email)
            ->orderby('created_at', 'desc')->get([
                'id',
                'email_subject',
                'web_notification',
                'created_at'
            ]);
//        dd($notifications);

        $new_data = $notifications->map(function ($notification) {
            return [
                'id' => Encryption::encodeId($notification->id),
                'email_subject' => $notification->email_subject,
                'web_notification' => $notification->web_notification,
                'created_at' => $notification->created_at
            ];
        });
        return response()->json($new_data);
    }

    public function notificationCount()
    {
        /*
         * Query cache.
         * after every five minutes query will execute
         */
        $notificationsCount = Cache::remember('notificationCount' . Auth::user()->user_email, 5, function () {
            return EmailQueue::where('email_to', Auth::user()->user_email)
                ->whereNotIn('caption', ['PASSWORD_RESET_REQUEST', 'ONE_TIME_PASSWORD', 'TWO_STEP_VERIFICATION'])
                ->where('web_notification', 0)
                ->orWhere('email_cc', Auth::user()->user_email)
                ->orderby('created_at', 'desc')
                ->count();
        });

        return response()->json($notificationsCount);
    }

    public function notificationSingle($id)
    {
        $id = Encryption::decodeId($id);
        EmailQueue::where('id', $id)
            ->update([
                'web_notification' => 1,
            ]);

        $singleNotificInfo = EmailQueue::where('id', $id)->first();

        return view('Dashboard::singleNotificInfo', compact('singleNotificInfo'));
    }

    public function notificationAll()
    {
        EmailQueue::where('email_to', Auth::user()->user_email)
            ->orWhere('email_cc', Auth::user()->user_email)
            ->whereNotIn('caption', ['PASSWORD_RESET_REQUEST', 'ONE_TIME_PASSWORD', 'TWO_STEP_VERIFICATION'])
            ->update([
                'web_notification' => 1,
            ]);
        $notificationsAll = EmailQueue::where('email_to', Auth::user()->user_email)
            ->orWhere('email_cc', Auth::user()->user_email)
            ->orderby('created_at', 'desc')->get();

        return view('Dashboard::singleNotificInfo', compact('notificationsAll'));
    }

    public function serverInfo()
    {
        if (!in_array(Auth::user()->user_type, ['1x101', '2x202'])) {
            Session::flash('error', 'Invalid URL ! This incident will be reported.');
            return redirect('/');
        }

        $start_time = microtime(TRUE);

        // When used without any option, the free command will display information about the memory and swap in kilobyte.
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        // removes nulls from array
        $mem = array_filter($mem, function ($value) {
            return ($value !== null && $value !== false && $value !== '');
        });
        $mem = array_merge($mem);

        // $mem data format
//        [
//          0 => "Mem:"
//          1 => total
//          2 => used (used = total – free – buff/cache)
//          3 => free (free = total – used – buff/cache)
//          4 => shared
//          5 => buff/cache
//          6 => available
//        ]

        $kb_to_gb_conversion_unit = 1000 * 1000;
        $total_ram_size = round($mem[1] / $kb_to_gb_conversion_unit, 2);
        $used_ram_size = round($mem[2] / $kb_to_gb_conversion_unit, 2);
        $free_ram_size = round($mem[3] / $kb_to_gb_conversion_unit, 2);
        $buffer_cache_memory_size = round($mem[5] / $kb_to_gb_conversion_unit, 2);

        // Formula 1
        // Percentage = (memory used - memory buff/cache) / total ram * 100
        // $total_ram_usage = round(($used_ram_size - $buffer_cache_memory_size) / $total_ram_size * 100, 2);

        // Formula 2.e
        // Percentage = (memory used / total memory) * 100
        // Or
        // Percentage = 100 -(((free + buff/cache) * 100) / total)
        $total_ram_usage = round(($mem[2] / $mem[1]) * 100, 2);


        //$connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`;
        //$totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`;


        /*
         * If the averages are 0.0, then your system is idle.
         * If the 1 minute average is higher than the 5 or 15 minute averages, then load is increasing.
         * If the 1 minute average is lower than the 5 or 15 minute averages, then load is decreasing.
         * If they are higher than your CPU count, then you might have a performance problem (it depends).
         *
         * For example, one can interpret a load average of "1.73 0.60 7.98" on a single-CPU system as:
         * during the last minute, the system was overloaded by 73% on average (1.73 runnable processes, so that 0.73 processes had to wait for a turn for a single CPU system on average).
         * during the last 5 minutes, the CPU was idling 40% of the time on average.
         * during the last 15 minutes, the system was overloaded 698% on average (7.98 runnable processes, so that 6.98 processes had to wait for a turn for a single CPU system on average).
         */
        $load = sys_getloadavg();
        $cpu_load = $load[0];

        // disk_total_space() and disk_free_space() return value as Byte format
        $total_disk_size = round(disk_total_space(".") / 1000000000); // total space in GB
        $free_disk_size = round(disk_free_space(".") / 1000000000); // Free space in GB
        $used_disk_size = round($total_disk_size - $free_disk_size); // used space in GB
        $disk_usage_percentage = round(($used_disk_size / $total_disk_size) * 100); // Disk usage ratio in Percentage(%)

        if ($total_ram_usage > 85 || $cpu_load > 2 || $disk_usage_percentage > 95) {
            $text_class = 'progress-bar-danger';
        } elseif ($total_ram_usage > 70 || $cpu_load > 1 || $disk_usage_percentage > 85) {
            $text_class = 'progress-bar-warning';
        } else {
            $text_class = 'progress-bar-success';
        }

        $db_version = \Illuminate\Support\Facades\DB::select(DB::raw("SHOW VARIABLES like 'version'"));
        $db_version = isset($db_version[0]->Value) ? $db_version[0]->Value : '-';

        $end_time = microtime(TRUE);
        $time_taken = $end_time - $start_time;
        $total_time_of_loading = round($time_taken, 4);

        return view("Dashboard::server-info", compact('cpu_load',
            'total_ram_size', 'used_ram_size', 'free_ram_size', 'buffer_cache_memory_size', 'total_ram_usage',
            'total_disk_size', 'used_disk_size', 'free_disk_size', 'disk_usage_percentage', 'db_version',
            'total_time_of_loading', 'text_class'));
    }

    public function specialServices(Dashboard $dashboard)
    {
        $special_types= ProcessType::where('is_special',1)->pluck('id')->toArray();
        $userType = Auth::user()->user_type;
        $dashboardObject = $dashboard->getDashboardObject();
        $servicesWiseApplication = null;
        $servicesWiseApplication = ProcessType::whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->where('is_special', 1)
                ->groupBy('group_name')
                ->orderBy('master_order_id', 'asc')
                ->get([DB::raw('group_concat(id) as process_type'), 'group_name',  DB::raw("MIN(id) as id"), 'form_url', 'type_key']);

            $userApplicaitons = ProcessList::where('company_id', Auth::user()->working_company_id)
            ->whereIn('process_type_id', $special_types)
            ->pluck('status_id');

            $approvedapp = 0;
            $processingapp = 0;
            $draftapp = 0;
            $rejectapp = 0;
            $shortfallapp = 0;

            if (count($userApplicaitons) > 0) {
                foreach ($userApplicaitons as $appStatus) {
                    if ($appStatus == 25) {
                        $approvedapp = $approvedapp + 1;
                    } elseif ($appStatus == '-1') {
                        $draftapp = $draftapp + 1;
                    } elseif ($appStatus == 5) {
                        $shortfallapp = $shortfallapp + 1;
                    } elseif ($appStatus == 6) {
                        $rejectapp = $rejectapp + 1;
                    } else {
                        $processingapp = $processingapp + 1;
                    }
                }
            }

            $totalapp = $approvedapp + $processingapp + $draftapp + $shortfallapp + $rejectapp;

            $userApplicaitons = [
                'draft' => $draftapp, 'processing' => $processingapp, 'approved' => $approvedapp,
                'totalapp' => $totalapp, 'shortfallapp' => $shortfallapp, 'rejectapp' => $rejectapp
            ];
               
        return view('Dashboard::special-services',compact('servicesWiseApplication','dashboardObject','userApplicaitons'));
    }


    public function specialServicesList(Request $request, $id = '', $processStatus = '')
    {
       
        $userType = Auth::user()->user_type;
        

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
            
            if (!session()->has('active_process_list')) {
                session()->put('active_process_list', $process_type_id);
            }


            $ProcessType = ProcessType::select(DB::raw("CONCAT(name) AS name"),'id')
                ->whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();
            $process_info = ProcessType::where('id', $process_type_id)->first(['id', 'acl_name', 'form_url', 'name','group_name', 'drop_down_label']);
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

            return view("Dashboard::special-service-list", compact(
                'status',
                'ProcessType',
                'processStatus',
                'searchTimeLine',
                'process_type_id',
                'process_info',
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

    public function specialServicesCreate(Request $request, $id = '')
    {
       
      
        $userType = Auth::user()->user_type;
        $id = Encryption::decodeId($id);
        
        $dynamic_form = DynamicProcess::where('process_type_id',$id)->first();
        if( !$dynamic_form){

        return 'Please add form data for this process';
        }

        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        $data['process_type_id']  = $dynamic_form->process_type_id;
        $data['application_type'] = ProcessType::Where('id', $dynamic_form->process_type_id)->value('name');
        $data['districts']        = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division']         = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['bank_list']        = Bank::orderBy('name')->where('is_active', 1)->pluck('name', 'id')->toArray();
        $data['dynamic_form']        = $dynamic_form;
        $data['dynamic_form_data']        = json_decode($dynamic_form->dynamic_data,true) ;
        $data['dynamic_form_attachments']        = $data['dynamic_form_data'][0]['attachments'] ;
        $data['process_data'] = ProcessType::find($id);
        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

                unset($data['dynamic_form_data'][0]['attachments']); 

        try {
        
            return view("Dashboard::special-service-add", $data);
        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PPC-1044]');
            return redirect()->back();
        }
    }

    public function specialServicesDataStore(Request $request ) {
        
        $app_id                = $request->get('app_id');
        $process_type_id = $request->get('process_type');
        $process_type = ProcessType::find($process_type_id);
        $type = $process_type->type;
        $process_name = '';
        try{
        DB::beginTransaction();
        if ($request->get('app_id')) {
            // $masterData = SpecialServiceMaster::where();
            if($type==1){
                $appData     = SpecialServiceIssue::find(Encryption::decodeId($app_id));
                $table = 'special_license_issue';
                $process_name = 'issue';
            }elseif($type==2){
                $appData     = SpecialServiceRenew::find(Encryption::decodeId($app_id));
                $table = 'special_license_renew';
                $process_name = 'renew';
            }elseif($type==3){
                $appData     = SpecialServiceAmmendment::find(Encryption::decodeId($app_id));
                $table = 'special_license_amendment';
                $process_name = 'amendment';
            }elseif($type==4){
                $appData     = SpecialServiceSurrender::find(Encryption::decodeId($app_id));
                $table = 'special_license_surrender';
                $process_name = 'surrender';
            }

            $processData = ProcessList::where([
                'process_type_id' => $process_type_id,
                'ref_id' => $appData->id
            ])->first();
        } else {
            $masterData = new SpecialServiceMaster();
            if($type==1){
                $appData     = new SpecialServiceIssue();
                $table = 'special_license_issue';
                $process_name = 'issue';
            }elseif($type==2){
                $appData     = new SpecialServiceRenew();
                $table = 'special_license_renew';
                $process_name = 'renew';
            }elseif($type==3){
                $appData     = new SpecialServiceAmmendment();
                $table = 'special_license_amendment';
                $process_name = 'amendment';
            }elseif($type==4){
                $appData     = new SpecialServiceSurrender();
                $table = 'special_license_surrender';
                $process_name = 'surrender';
            }
           
            $processData = new ProcessList();
        }
        
      

        $collection =  collect($request->all())->reject(function($item, $key){
            if (strpos($key,'dynamic_') !== false) {
                return false;
            } else {
                return true;
            }
        })->toArray();
       
       

        $appData->reg_office_district = $request->get('reg_office_district');
        $appData->reg_office_thana    = $request->get('reg_office_thana');
        $appData->reg_office_address  = $request->get('reg_office_address');
        
        $appData->op_office_district  = $request->get('op_office_district');
        $appData->op_office_thana     = $request->get('op_office_thana');
        $appData->op_office_address   = $request->get('op_office_address');
        
        $appData->applicant_name      = $request->get('applicant_name');
        $appData->applicant_mobile    = $request->get('applicant_mobile');
        $appData->applicant_telephone = $request->get('applicant_telephone');
        $appData->applicant_email     = $request->get('applicant_email');
        $appData->applicant_district  = $request->get('applicant_district');
        $appData->applicant_thana     = $request->get('applicant_thana');
        $appData->applicant_address   = $request->get('applicant_address');
       
        

        $doc_index=1;
        if ($request->hasFile('documents')){
            foreach($request->file('documents') as $document){

                    $yearMonth = date('Y') . '/' . date('m') . '/';
                    $path      = 'uploads/special-services/' . $yearMonth;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $_file_path = $document;
                    $file_path  = trim(uniqid('DYN_FORM-' . '-', true) . $_file_path->getClientOriginalName());
                    $_file_path->move($path, $file_path);
    
                    $collection['doc_'.$doc_index] = $path . $file_path;
             
                $doc_index++;
            } 
        }else{
            $collection_doc = collect(json_decode( $appData->json_object))->reject(function($item, $key){
                if (strpos($key,'doc_') !== false) {
                    return false;
                } else {
                    return true;
                }
            })->toArray();
            $collection=array_merge($collection,$collection_doc);
        }
        
        $appData->status              = 1;
        $appData->created_at          = Carbon::now();
        $appData->company_id          = CommonFunction::getUserCompanyWithZero();
        $appData->json_object          = json_encode($collection);
        
        $appData->save();
        

              


     
//=================================================payment code==========================
           $payment_exist = DynamicPayment::where('app_id',$appData->id)->where('process_type_id',$process_type_id)->first();
            if(!$payment_exist){
                $dynamic_payment= new DynamicPayment();
                
            }else{
                $dynamic_payment= $payment_exist;
            }

            $dynamic_payment->app_id  = $appData->id;
                $dynamic_payment->process_type_id  = $process_type_id;
                // $dynamic_payment->app_tracking_no  = $tracking_no;
                $dynamic_payment->payment_status  = 1;
                $dynamic_payment->payment_type  = $process_name;
                $dynamic_payment->pay_order_number  = $request->get('pay_order_number');
                $dynamic_payment->pay_order_date  = date("Y-m-d", strtotime($request->get('pay_order_date'))) ;
                
                $dynamic_payment->bank_id  = $request->get('bank_name');
                $dynamic_payment->branch_id  = $request->get('branch_name');
                if ($request->hasFile('pay_order_copy')) {
                    $yearMonth = date('Y') . '/' . date('m') . '/';
                    $path      = 'uploads/special-payment/' . $yearMonth;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $_file_path = $request->file('pay_order_copy');
                    $file_path  = trim(uniqid('DYN_SER-' . '-', true) . $_file_path->getClientOriginalName());
                    $_file_path->move($path, $file_path);
                    $dynamic_payment->pay_order_copy  = $path . $file_path;
                }
                
                
                

   
        
        
        $processData = $this->storeProcessListData($request, $processData, $appData);
 


//       
        

        if(!empty($appData->tracking_no)){
            $dynamic_payment->app_tracking_no= $appData->tracking_no;
        }
        //         //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            $tracking_no= CommonFunction::generateUniqueTrackingNumber('DYN', $process_type_id, $processData->id, $table, 'DYN', $appData->id);
            $dynamic_payment->app_tracking_no= $tracking_no;
        }
        

        $dynamic_payment->save();
      //=================================================payment code end==========================  
      DB::commit();
        if ($request->get('actionBtn') == 'draft') {
            Session::flash('success', 'Successfully updated the Application!');
        } elseif ($request->get('actionBtn') == 'submit') {
            Session::flash('success', 'Successfully Application Submitted !');
        } else {
            Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]');
        }

        return redirect('special_service/service-list/' . Encryption::encodeId($process_type_id));
    } catch (\Exception $e) {
        Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
        Session::flash('error', 'Data not saved.');
        return false;
    }
        
    }


    private function storeProcessListData($request, $processListObj, $appData)
    {
        $processListObj->company_id = CommonFunction::getUserCompanyWithZero();

        //Set category id for process differentiation
        $processListObj->cat_id = 1;
        if ($request->get('actionBtn') === 'draft') {
            $processListObj->status_id = -1;
            $processListObj->desk_id   = 0;
        } elseif ($processListObj->status_id === 5) {
            // For shortfall
            $submission_sql_param = [
                'app_id' => $appData->id,
                'process_type_id' => $request->get('process_type'),
            ];

            $process_type_info = ProcessType::where('id', $request->get('process_type'))
                ->orderBy('id', 'desc')
                ->first([
                    'form_url',
                    'process_type.process_desk_status_json',
                    'process_type.name'
                ]);

            $resubmission_data              = $this->getProcessDeskStatus('resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param);
            $processListObj->status_id      = $resubmission_data['process_starting_status'];
            $processListObj->desk_id        = $resubmission_data['process_starting_desk'];
            $processListObj->process_desc   = 'Re-submitted form applicant';
            $processListObj->resubmitted_at = Carbon::now(); // application resubmission Date

            $license_json_data=[];
            
            $div_name = DB::table('area_info')
            ->where('area_id', $request->get('isp_licensese_area_division'))
            ->where('area_type', 1)
            ->pluck('area_nm')
            ->first();
            $dist_name = DB::table('area_info')
            ->where('area_id', $request->get('isp_licensese_area_district'))
            ->where('area_type', 2)
            ->pluck('area_nm')
            ->first();
            $upz_name = DB::table('area_info')
            ->where('area_id', $request->get('isp_licensese_area_thana'))
            ->where('area_type', 3)
            ->pluck('area_nm')
            ->first();
            
            if(isset($div_name)){
                $license_json_data['Division']= ($div_name)? $div_name: ''  ;
            }
            if(isset($dist_name)){
                $license_json_data['District']= ($dist_name)? $dist_name: '';
            }
            if(isset($upz_name)){
                $license_json_data['Upazilla']= ($upz_name)?$upz_name : '';
            }

            $processListObj->license_json =json_encode($license_json_data);

            $resultData = "{$processListObj->id}-{$processListObj->tracking_no}{$processListObj->desk_id}-{$processListObj->status_id}-{$processListObj->user_id}-{$processListObj->updated_by}";

            $processListObj->previous_hash = $processListObj->hash_value ?? '';
            $processListObj->hash_value    = Encryption::encode($resultData);

        } else {
            $processListObj->status_id = 1;
            $processListObj->desk_id   = 3;
            $processListObj->submitted_at = Carbon::now();
            $license_json_data=[];
           
            $div_name = DB::table('area_info')
            ->where('area_id', $request->get('isp_licensese_area_division'))
            ->where('area_type', 1)
            ->pluck('area_nm')
            ->first();
            $dist_name = DB::table('area_info')
            ->where('area_id', $request->get('isp_licensese_area_district'))
            ->where('area_type', 2)
            ->pluck('area_nm')
            ->first();
            $upz_name = DB::table('area_info')
            ->where('area_id', $request->get('isp_licensese_area_thana'))
            ->where('area_type', 3)
            ->pluck('area_nm')
            ->first();

            
            if(isset($div_name)){
                $license_json_data['Division']= ($div_name)? $div_name: ''  ;
            }
            if(isset($dist_name)){
                $license_json_data['District']= ($dist_name)? $dist_name: '';
            }
            if(isset($upz_name)){
                $license_json_data['Upazilla']= ($upz_name)?$upz_name : '';
            }

            $processListObj->license_json =json_encode($license_json_data);
        }

        $processListObj->ref_id          = $appData->id;
        $processListObj->process_type_id = $request->get('process_type');
        $processListObj->office_id       = 0;
        
        $jsonData['Applicant Name']      = Auth::user()->user_first_name;
        $jsonData['Company Name']        = $request->company_name;
        $jsonData['Email']               = Auth::user()->user_email;
        $jsonData['Phone']               = Auth::user()->user_mobile;
        $processListObj['json_object']   = json_encode($jsonData);
        $processListObj->save();

        return $processListObj;

    }


    public function specialServicesPaymentStore(Request $request ) {
        
        $app_id                = Encryption::decodeId($request->get('app_id'));
        $process_type_id = $request->get('process_type');
        $process_data = ProcessList::where('ref_id',$app_id)->where('process_type_id',$process_type_id)->first();
        
        $process_data->desk_id= 5;
        $process_data->status_id= 16;
        try{
        DB::beginTransaction();

       

        $payment = new DynamicPayment();
        $payment->pay_order_number = $request->get('pay_order_number');
        $payment->process_type_id = $process_type_id;
        $payment->app_id = $app_id;
        $payment->app_tracking_no = $process_data->tracking_no;
        $payment->bank_id=$request->get('bank_name');
        $payment->branch_id=$request->get('branch_name');


        if ($request->hasFile('pay_order_copy')) {
            $yearMonth = date('Y') . '/' . date('m') . '/';
            $path      = 'uploads/special-payment/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $_file_path = $request->file('pay_order_copy');
            $file_path  = trim(uniqid('DYN_SER-' . '-', true) . $_file_path->getClientOriginalName());
            $_file_path->move($path, $file_path);
            $payment->pay_order_copy  = $path . $file_path;
        }
        $payment->save();
        $process_data->save();
        DB::commit();

      
            Session::flash('success', 'Successfully updated the Application Payment!');
        

        return redirect('special_service/service-list/' . Encryption::encodeId($process_type_id));
    } catch (\Exception $e) {
        DB::rollback();
        Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
        Session::flash('error', 'Data not saved.');
        return false;
    }
        
    }

    public function fetchSpecialData(Request $request ) {
       
        if ( $request->license_no==null ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        $process_type_id   = $request->process_type;
        $process_type_data = ProcessType::find($process_type_id);
        $issue_type_data = ProcessType::where('group_name',$process_type_data->group_name)
        ->where('type',1)->first();
        

        $data['appInfo'] = ProcessList::leftJoin( 'special_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->where( 'apps.license_no', $request->license_no )
                                      ->where( 'process_list.process_type_id',  $issue_type_data->id   )
                                      ->first( [
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
                                        'apps.*',
                                        ] );

        // if ( $data['appInfo']!= null && $data['appInfo']->cancellation_tracking_no != null  ) {
        //     return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

        // }
        if ( $data['appInfo']== null ) {
                return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide a valid license no' ] );
    
            }
        $data['thana']        = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        
        $data = ['responseCode' => 1, 'data' => $data];
        return response()->json($data);

    }


}
