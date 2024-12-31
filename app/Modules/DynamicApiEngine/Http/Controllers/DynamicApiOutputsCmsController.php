<?php


namespace App\Modules\DynamicApiEngine\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\Encryption;
use App\Modules\DynamicApiEngine\accessPermissions;
use App\Modules\DynamicApiEngine\Models\DynamicApiOutputs;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use DB;

class DynamicApiOutputsCmsController extends Controller
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

    public function getOutputList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $apiID = Encryption::decodeId($request->get('api_id'));

        $api_outputs = DynamicApiOutputs::where('api_id',$apiID)
            ->get();

        return DataTables::of($api_outputs)
            ->editColumn('query_type', function ($api_outputs) {
                if ($api_outputs->query_type == '1') {
                    return '<span class="label label-primary"><b>Base Query</b></span>';
                } else {
                    return '<span class="label label-info"><b>Child Query</b></span>';
                }
            })
            ->addColumn('action', function ($api_outputs) {
                $action = '<button type="button" class="btn btn-info btn-xs" data-output_id="'.Encryption::encodeId($api_outputs->id).'" onclick="openOutputModal(this)"><b><i class="fa fa-edit"></i> Edit</b></button> &nbsp;';
                $action .= ' <button type="button" class="btn btn-danger btn-xs" data-output_id="'.Encryption::encodeId($api_outputs->id).'" onclick="deleteOutput(this)"><b><i class="fa fa-trash"></i> Delete</b></button>';

                return $action;
            })
            ->rawColumns(['query_type','action'])
            ->make(true);
    }


    public function storeOutputData(Request $request){

//        if (!ACL::getAccsessRight('settings', 'A')) {
//            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
//        }

        $rules = [
            'api_id' => 'required',
            'output_type' => 'required',
            'output_exe_sql' => 'required',
            'output_query_type' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $api_id            = Encryption::decodeId($request->get('api_id'));
            $output_type = $request->get('output_type');
            $output_exe_sql   = $request->get('output_exe_sql');
            $output_query_type   = $request->get('output_query_type');

            $hasMultipleBaseQuery = DynamicApiOutputs::where('api_id',$api_id)->where('query_type',1)->count();
            if($hasMultipleBaseQuery > 0 && $output_query_type == 1){
                return response()->json(['responseCode' => 0, 'message' => 'Base query already exists. Please select it as child query']);
            }

            $ApiOutputs = new DynamicApiOutputs();
            $ApiOutputs->api_id = $api_id;
            $ApiOutputs->output_type = $output_type;
            $ApiOutputs->exe_sql = $output_exe_sql;
            $ApiOutputs->query_type = $output_query_type;
            $ApiOutputs->save();

            $outputCounter = DynamicApiOutputs::where('api_id',$api_id)->count();
            return response()->json(['responseCode' => 1,'total_output'=> $outputCounter, 'message' => 'Successfully stored information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }



    public function outputData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $output_id = Encryption::decodeId($request->get('output_id'));

        $output_data = DynamicApiOutputs::where('id',$output_id)->first();

        return response()->json(['responseCode' => 1, 'data' => $output_data, 'output_id'=>$request->get('output_id')]);


    }



    public function updateOutputData(Request $request){
        $rules = [
            'api_id' => 'required',
            'output_id' => 'required',
            'output_type' => 'required',
            'output_exe_sql' => 'required',
            'output_query_type' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $output_id            = Encryption::decodeId($request->get('output_id'));
            $api_id               = Encryption::decodeId($request->get('api_id'));
            $output_type          = $request->get('output_type');
            $output_exe_sql       = $request->get('output_exe_sql');
            $output_query_type    = $request->get('output_query_type');

            $hasMultipleBaseQuery = DynamicApiOutputs::where('api_id',$api_id)->where('query_type',1)->where('id','!=',$output_id)->count();
            if($hasMultipleBaseQuery > 0 && $output_query_type == 1){
                return response()->json(['responseCode' => 0, 'message' => 'Base query already exists. Please select it as child query']);
            }

            DynamicApiOutputs::where('id',$output_id)
                ->update([
                    'output_type'=> $output_type,
                    'exe_sql'=> $output_exe_sql,
                    'query_type'=> $output_query_type
                ]);

            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    public function deleteOutput(Request $request){

//        if (!ACL::getAccsessRight('settings', 'A')) {
//            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
//        }

        $rules = [
            'output_id' => 'required',
            'api_id' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $output_id      = Encryption::decodeId($request->get('output_id'));
            $api_id      = Encryption::decodeId($request->get('api_id'));

            DynamicApiOutputs::where('id',$output_id)->delete();

            $outputCounter = DynamicApiOutputs::where('api_id',$api_id)->count();
            return response()->json(['responseCode' => 1,'total_output'=> $outputCounter, 'message' => 'Successfully deleted information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }




}
