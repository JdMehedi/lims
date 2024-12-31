<?php
namespace App\Modules\ProcessPath\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\ApplicationGuideline;
use App\Modules\Settings\Models\ApplicationGuidelineDetails;
use App\Modules\Settings\Models\ApplicationGuidelineDoc;
use App\Modules\Settings\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClientProcessPathController extends Controller
{
    public $processPathTable = 'process_path';
    public $processStatus = 'process_status';
    public $processType = 'process_type';
    public $shortFallId = '5,6';
    protected $aclName;

    public function __construct()
    {
        $this->aclName = 'processPath';
    }

    /**
     * Show application list
     * @param string $id
     * @param string $processStatus
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processListById(Request $request, $id = '', $processStatus = '')
    {
        try{
            $userType = Auth::user()->user_type;
            if (CommonFunction::checkEligibility() != 1 and $userType == '5x505') {
                Session::flash('error', 'You are not eligible for apply ! [CPPC-1020]');
                return redirect('dashboard');
            }
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


            $ProcessType = ProcessType::select(
                DB::raw("CONCAT(name_bn,' ',group_name) AS name"),'id')
                ->whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            $ProcessTypeData = ProcessType::whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->orderBy('name_bn')
                ->groupBy('group_name')
                ->get();


            $process_info = ProcessType::where('id', $process_type_id)->first(['id', 'acl_name', 'form_url', 'name']);


            $processStatus = null;
            $status = ['' => 'Select one'] + ProcessStatus::where('process_type_id', $process_type_id != 0 ? $process_type_id : -1)// -1 means this service not available
                ->where('id', '!=', -1)
                    ->where('status', 1)
                    ->orderBy('status_name','ASC')
                    ->pluck('status_name', 'id')
                    ->toArray();
            $searchTimeLine = [
                '' => 'select One',
                '1' => '1 Day',
                '7' => '1 Week',
                '15' => '2 Weeks',
                '30' => '1 Month',
            ];
            $aclName = $this->aclName;

            // Global search or dashboard search option
            $search_by_keyword = '';
            if ($request->isMethod('post')) {
                $search_by_keyword = $request->get('search_by_keyword');
            }

            $number_of_rows = Configuration::where('caption', 'PROCESS_ROW_NUMBER')->value('value');


            $status_wise_apps = '';
            if ($userType == "1x101" || $userType == "10x101" || $userType == "4x404") {
                $status_wise_apps = ProcessList::statuswiseAppInDesks($process_type_id);
            }
            return view("ProcessPath::client-common-list", compact('status', 'ProcessType', 'processStatus', 'searchTimeLine', 'process_type_id', 'process_info', 'aclName', 'search_by_keyword', 'status_wise_apps', 'number_of_rows', 'ProcessTypeData'));
        } catch (\Exception $e){
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage(). '[CPPC-1033]');
            return redirect()->back();
        }
    }

    public function processDetails($perm)
    {
        try{
            Session::forget('commonPullId');
            $perm = Encryption::decode($perm);
            $userType = Auth::user()->user_type;
            $data['ProcessTypeData'] = ProcessType::whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->orderBy('child_order_id')
                ->where('group_name', $perm)
                ->get();

            $data['app'] = ApplicationGuideline::where('group_nm_bn', $perm)->first();

            $data_id = $data['app']['id'] ?? 0;
            $data['details'] = ApplicationGuidelineDetails::where('app_guideline_id', $data_id)->get();
            $data['docs'] = ApplicationGuidelineDoc::where('app_guideline_id', $data_id)->get();

            return view("ProcessPath::client-common-details", $data);
        } catch (\Exception $e){
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage(). '[CPPC-1003]');
            return redirect()->back();
        }
    }

    public function checkCancellation(Request $request){
        $process_type_id = Encryption::decodeId($request->process_type_id);

        $industryInfo = cancellationRequest($process_type_id);

        if (count([])){
            return response()->json(['responseCode' => 1, 'data' => $industryInfo]);
        }else{
            return response()->json(['responseCode' => 0]);
        }


    }

    public function setCanApp(Request  $request){
        Session::put('commonPullId', $request->id);
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }


}
