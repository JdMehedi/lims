<?php


namespace App\Modules\DynamicApiEngine\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\Encryption;
use App\Modules\DynamicApiEngine\accessPermissions;
use App\Modules\DynamicApiEngine\Models\DynamicApiOperationsSet;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use DB;

class DynamicApiOperationsCmsController extends Controller
{
    protected $aclName;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $permission = accessPermissions::checkUserTypePermission(Auth::user());
            if($permission == false){die('No access right !');exit();}else{return $next($request);}
        });
        $this->aclName = 'settings';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {

    }


    public function getOperationList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $apiID = Encryption::decodeId($request->get('api_id'));

        $api_operations = DynamicApiOperationsSet::where('api_id',$apiID)
            ->get();

        return DataTables::of($api_operations)
            ->addColumn('action', function ($api_operations) {
                $action = '<button type="button" class="btn btn-info btn-xs" data-operation_id="'.Encryption::encodeId($api_operations->id).'" onclick="openOperationModal(this)"><b><i class="fa fa-edit"></i> Edit</b></button> &nbsp;';
                $action .= ' <button type="button" class="btn btn-danger btn-xs" data-operation_id="'.Encryption::encodeId($api_operations->id).'" onclick="deleteOperation(this)"><b><i class="fa fa-trash"></i> Delete</b></button>';

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function storeOperationalData(Request $request){

//        if (!ACL::getAccsessRight('settings', 'A')) {
//            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
//        }

        $rules = [
            'api_id' => 'required',
            'operation_name' => 'required',
            'operation_key' => 'required',
            'operation_type' => 'required',
            'operation_exe_sql' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $api_id            = Encryption::decodeId($request->get('api_id'));
            $operation_name = $request->get('operation_name');
            $operation_key    = str_replace(' ','_',trim($request->get('operation_key')));
            $operation_type   = $request->get('operation_type');
            $operation_exe_sql   = $request->get('operation_exe_sql');
            $operation_priority   = $request->get('operation_priority');


            $parameterIsExists = DynamicApiOperationsSet::where('api_id',$api_id)
                ->where('key',$operation_key)
                ->count();

            if($parameterIsExists > 0){
                return response()->json(['responseCode' => 0, 'message' => 'Same operation name already exists']);
            }


            $ApiOperation = new DynamicApiOperationsSet();
            $ApiOperation->api_id = $api_id;
            $ApiOperation->name = $operation_name;
            $ApiOperation->key = $operation_key;
            $ApiOperation->operation_type = $operation_type;
            $ApiOperation->exe_sql = $operation_exe_sql;
            $ApiOperation->priority = $operation_priority;
            $ApiOperation->save();

            $operationCounter = DynamicApiOperationsSet::where('api_id',$api_id)->count();
            return response()->json(['responseCode' => 1,'total_operation'=> $operationCounter, 'message' => 'Successfully stored information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }



    public function operationData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $operation_id = Encryption::decodeId($request->get('operation_id'));

        $operation_data = DynamicApiOperationsSet::where('id',$operation_id)->first();

        return response()->json(['responseCode' => 1, 'data' => $operation_data, 'operation_id'=>$request->get('operation_id')]);


    }


    public function updateOperationalData(Request $request){


        $rules = [
            'operation_id' => 'required',
            'operation_name' => 'required',
            'operation_key' => 'required',
            'operation_type' => 'required',
            'operation_exe_sql' => 'required',
            'operation_priority' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $operation_id            = Encryption::decodeId($request->get('operation_id'));
            $operation_name = $request->get('operation_name');
            $operation_key    = str_replace(' ','_',trim($request->get('operation_key')));
            $operation_type   = $request->get('operation_type');
            $operation_exe_sql   = $request->get('operation_exe_sql');
            $operation_priority   = $request->get('operation_priority');


            DynamicApiOperationsSet::where('id',$operation_id)
                ->update([
                    'name'=> $operation_name,
                    'key'=> $operation_key,
                    'operation_type'=> $operation_type,
                    'exe_sql'=> $operation_exe_sql,
                    'priority'=> $operation_priority
                ]);

            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    public function deleteOperation(Request $request){

//        if (!ACL::getAccsessRight('settings', 'A')) {
//            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
//        }

        $rules = [
            'operation_id' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $operation_id      = Encryption::decodeId($request->get('operation_id'));
            $api_id      = Encryption::decodeId($request->get('api_id'));

            DynamicApiOperationsSet::where('id',$operation_id)->delete();

            $operationCounter = DynamicApiOperationsSet::where('api_id',$api_id)->count();
            return response()->json(['responseCode' => 1,'total_operation'=> $operationCounter, 'message' => 'Successfully deleted information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


}
