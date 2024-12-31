<?php


namespace App\Modules\DynamicApiEngine\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\Encryption;
use App\Modules\DynamicApiEngine\accessPermissions;
use App\Modules\DynamicApiEngine\Models\DynamicApiList;
use App\Modules\DynamicApiEngine\Models\DynamicApiOperationsSet;
use App\Modules\DynamicApiEngine\Models\DynamicApiOutputs;
use App\Modules\DynamicApiEngine\Models\DynamicApiParameters;
use App\Modules\DynamicApiEngine\Models\DynamicApiParametersValidation;
use App\Modules\DynamicApiEngine\Models\DynamicApiValidationRules;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use DB;

class DynamicApiCmsController extends Controller
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
        return view('DynamicApiEngine::api_list');
    }


    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function getApiList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $api_list = DynamicApiList::get();

        return DataTables::of($api_list)
            ->editColumn('is_active', function ($api_list) {
                if ($api_list->is_active == '1') {
                    return '<span class="label label-primary"><b>Active</b></span>';
                } else {
                    return '<span class="label label-danger"><b>In-active</b></span>';
                }
            })
            ->editColumn('method', function ($api_list) {
                if ($api_list->method == 'GET') {
                    return '<span class="label label-primary"><b>GET</b></span>';
                } elseif ($api_list->method == 'POST') {
                    return '<span class="label label-info"><b>POST</b></span>';
                } else {
                    return '<span class="label label-success"><b>'.$api_list->method.'</b></span>';
                }
            })
//            ->editColumn('base_url', function ($api_list) {
//                return '<span class="api_base_url_span">'.$api_list->base_url.'</span> <i class="fa fa-copy" title="Copy to Clipboard" data-toggle="tooltip" style="cursor: pointer;" onclick="copyToClipboard(this,'.$api_list->base_url.')"></i>';
//            })
            ->editColumn('base_url', function ($api_list) {
                return '<span>'.$api_list->base_url.'</span> <i class="fa fa-copy " title="Copy to Clipboard" data-toggle="tooltip"></i>';
            })

            ->addColumn('action', function ($api_list) {
                $action = "<a href='/dynamic-api-engine/open-api/".Encryption::encodeId($api_list->id)."' class='btn btn-xs btn-info'><i class='fa fa-folder-open'></i> Open </a> &nbsp;";
                $action .= ' <button type="button" class="btn btn-danger btn-xs deleteAPI" data-api_id="'.Encryption::encodeId($api_list->id).'"><b class="spinner-icon-delete"></b><b><i class="fa fa-trash"></i> Delete</b></button>';
                return $action;
            })

            ->rawColumns(['is_active','method','base_url', 'action'])
            ->make(true);
    }


    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function getParameterList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $apiID = Encryption::decodeId($request->get('api_id'));

        $api_parameters = DynamicApiParameters::leftjoin('d_api_list','d_api_list.id','=','d_api_request_parameter.api_id')
                    ->leftjoin('d_api_request_parameter_validation','d_api_request_parameter.id','=','d_api_request_parameter_validation.request_parameter_id')
                    ->where('d_api_request_parameter.api_id',$apiID)
                    ->groupBy('d_api_request_parameter.id')
                    ->get([
                        'd_api_request_parameter.id as id',
                        'd_api_list.name as api_name',
                        'd_api_request_parameter.request_parameter_name as parameter_name',
                        DB::raw('group_concat(distinct validation_method , " ") as validation_method')
                    ]);

        return DataTables::of($api_parameters)
            ->editColumn('validation_method', function ($api_parameters) {
                if (isset($api_parameters->validation_method)) {
                    $validationMethodArray = explode(",",$api_parameters->validation_method);
                    $finalString = '';
                    foreach ($validationMethodArray as $method){
                        $finalString .= '<span class="label label-success" style="font-weight: bold !important;font-size:12px;">'.$method.'</span> &nbsp;';
                    }
                    return $finalString;

                }  else {
                    return '';
                }

            })
            ->addColumn('action', function ($api_parameters) {
                $action = '<button type="button" class="btn btn-primary btn-xs" data-param_id="'.Encryption::encodeId($api_parameters->id).'" onclick="editParameterModal(this)"><b class="spinner-section"></b><b><i class="fa fa-edit"></i> Edit Parameter</b></button> &nbsp;';
                $action .= '<button type="button" class="btn btn-info btn-xs" data-param_id="'.Encryption::encodeId($api_parameters->id).'" onclick="parameterValidationModal(this)"><b class="spinner-section"></b><b> Add / Edit Validation</b></button> &nbsp;';
                $action .= ' <button type="button" class="btn btn-danger btn-xs" data-param_id="'.Encryption::encodeId($api_parameters->id).'" onclick="deleteParameter(this)"><b><i class="fa fa-trash"></i> Delete</b></button>';

                return $action;
            })
            ->rawColumns(['validation_method','action'])
            ->make(true);
    }



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('DynamicApiEngine::create_new_api');
    }



    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function openApi($id)
    {
        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');

        $api_id = Encryption::decodeId($id);
        $apiListData = DynamicApiList::where('id', $api_id)->first();
      //  $validationRules = DynamicApiValidationRules::where('status', 1)->pluck('rule_name', 'rule_name');

        $validationRulesData = DynamicApiValidationRules::where('status', 1)->get([
            DB::raw('SUBSTRING_INDEX(rule_name, ":",1) as rule_name_val'),
            'rule_name'
        ]);

        $validationRules = [];

        foreach ($validationRulesData as $rule){
            $validationRules[$rule->rule_name_val] = $rule->rule_name;
        }
        $parameterCounter = DynamicApiParameters::where('api_id',$api_id)->count();
        $operationCounter = DynamicApiOperationsSet::where('api_id',$api_id)->count();
        $outputCounter = DynamicApiOutputs::where('api_id',$api_id)->count();

        return view('DynamicApiEngine::open_api',compact('apiListData','validationRules','parameterCounter','operationCounter','outputCounter'));

    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeApiBasicInfo(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'name' => 'required',
            'key' => 'required',
            'method' => 'required',
            'request_content_type' => 'required',
            'base_url' => 'required',
           // 'allowed_ips' => 'required',
            'allowed_sql_keys' => 'required',
            'description' => 'required'
        ]);


        try {

            $apiKeyCounter = DynamicApiList::where('key',$request->get('key'))->count();
            if($apiKeyCounter > 0){
                Session::flash('error', 'Api already exists');
                return Redirect::back()->withInput();
            }

            $ApiList = new DynamicApiList();
            $ApiList->name = $request->get('name');
            $ApiList->key = $request->get('key');
            $ApiList->method = $request->get('method');
            $ApiList->request_content_type = $request->get('request_content_type');
            $ApiList->base_url = $request->get('base_url');
          //  $ApiList->allowed_ips = $request->get('allowed_ips');
            $ApiList->allowed_sql_keys = json_encode($request->get('allowed_sql_keys'));
            $ApiList->description = $request->get('description');
            $ApiList->is_active = 1;
            $ApiList->save();

            $ApiListId = $ApiList->id;

          //  Session::flash('success', 'Data is stored successfully!');
            return redirect('/dynamic-api-engine/open-api/'. Encryption::encodeId($ApiListId));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }

    }



    public function updateApiBasicInfo(Request $request){

//        if (!ACL::getAccsessRight('settings', 'A')) {
//            die('You have no access right! Please contact system administration for more information.');
//        }
//        $this->validate($request, [
//            'name' => 'required',
//            'key' => 'required',
//            'method' => 'required',
//            'request_content_type' => 'required',
//            'base_url' => 'required',
//            'allowed_ips' => 'required',
//            'allowed_sql_keys' => 'required',
//            'Description' => 'required'
//        ]);


        try {

            $api_id =  Encryption::decodeId($request->get('api_id'));

            DynamicApiList::where('id',$api_id)->update([
                'name' => $request->get('api_name'),
                'key' => $request->get('api_key'),
                'method' => $request->get('api_method'),
                'request_content_type' => $request->get('api_request_content_type'),
                'base_url' => $request->get('api_base_url'),
             //   'allowed_ips' => $request->get('api_allowed_ips'),
                'allowed_sql_keys' => json_encode($request->get('api_allowed_sql_keys')),
                'description' => $request->get('api_description'),
                'is_active' => $request->get('api_is_active')
            ]);


            return response()->json(['responseCode' => 1, 'message' => 'Successfully stored information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function storeParameterData(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
        }

        $rules = [
            'parameter_name' => 'required',
            'api_id' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $api_id            = Encryption::decodeId($request->get('api_id'));
            $parameter_name    = $request->get('parameter_name');


            foreach ($parameter_name as $param){
                $parameterIsExists = DynamicApiParameters::where('api_id',$api_id)
                    ->where('request_parameter_name',$param)
                    ->count();

                if($parameterIsExists > 0){
                    return response()->json(['responseCode' => 0, 'message' => 'Parameter already exists']);
                }

                $ApiParameter = new DynamicApiParameters();
                $ApiParameter->api_id = $api_id;
                $ApiParameter->request_parameter_name = str_replace(' ','_',trim($param));
                $ApiParameter->save();
            }

            $parameterCounter = DynamicApiParameters::where('api_id',$api_id)->count();

            return response()->json(['responseCode' => 1, 'total_param' => $parameterCounter,'message' => 'Successfully stored information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */

    public function parameterValidationContent(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $parameterId = Encryption::decodeId($request->get('param_id'));

        $api_parameter_data = DynamicApiParameters::leftjoin('d_api_request_parameter_validation','d_api_request_parameter.id','=','d_api_request_parameter_validation.request_parameter_id')
            ->where('d_api_request_parameter.id',$parameterId)
            ->get([
                'd_api_request_parameter.id as id',
                'd_api_request_parameter.api_id as api_id',
                'd_api_request_parameter.request_parameter_name as parameter_name',
                'd_api_request_parameter_validation.validation_method as validation_method_full',
                'd_api_request_parameter_validation.exe_sql as exe_sql',
                'd_api_request_parameter_validation.message as message',
                DB::raw('SUBSTRING_INDEX(d_api_request_parameter_validation.validation_method, ":",1) as validation_method')
            ]);

        $validationRulesData = DynamicApiValidationRules::where('status', 1)->get([
            DB::raw('SUBSTRING_INDEX(rule_name, ":",1) as rule_name_val'),
            'rule_name'
        ]);

        $validationRules = [];

        foreach ($validationRulesData as $rule){
            $validationRules[$rule->rule_name_val] = $rule->rule_name;
        }

        $public_html = strval(view("DynamicApiEngine::Modal.parameter_validation_content", compact('api_parameter_data','validationRules')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);


    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */

    public function parameterEditContent(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $parameterId = Encryption::decodeId($request->get('param_id'));

        $api_parameter_data = DynamicApiParameters::where('d_api_request_parameter.id',$parameterId)
            ->first([
                'd_api_request_parameter.id as id',
                'd_api_request_parameter.request_parameter_name as parameter_name'
            ]);

        $public_html = strval(view("DynamicApiEngine::Modal.parameter_edit_content", compact('api_parameter_data')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateParameterValidationData(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
        }

        $rules = [
            'parameter_name' => 'required',
            'parameter_id' => 'required',
            'api_id' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $validationMethods = $request->get('validation_methods');
            $validation_text   = $request->get('validation_text');
            $validation_exe_sql   = $request->get('validation_exe_sql');
          //  $sql_validation_message   = $request->get('sql_validation_message');
            $edited_validation_method_val   = $request->get('edited_validation_method_val');
            $parameter_id      = Encryption::decodeId($request->get('parameter_id'));
            $api_id            = Encryption::decodeId($request->get('api_id'));
            $parameter_name    = str_replace(' ','_',trim($request->get('parameter_name')));


            if(isset($validationMethods)) {
                if (array_diff_assoc($validationMethods, array_unique($validationMethods)) != []) {
                    return response()->json(['responseCode' => 0, 'message' => 'Duplicate validation not acceptable']);
                }
            }

            $existingParam = DynamicApiParameters::where('id',$parameter_id)->first(['request_parameter_name']);
            if($existingParam->request_parameter_name != $parameter_name){
                $parameterIsExists = DynamicApiParameters::where('api_id',$api_id)
                    ->where('request_parameter_name',$parameter_name)
                    ->count();

                if($parameterIsExists > 0){
                    return response()->json(['responseCode' => 0, 'message' => 'Parameter already exists']);
                }
            }


            DynamicApiParameters::where('id',$parameter_id)
                                  ->update([
                                      'request_parameter_name'=>$parameter_name
                                  ]);

            DynamicApiParametersValidation::where('request_parameter_id',$parameter_id)->delete();

            $validationSqlIndexCounter = 0 ;
            for ($i=0 ; $i< count($validationMethods) ; $i++){
                $ApiParamValidation = new DynamicApiParametersValidation();
                $ApiParamValidation->request_parameter_id = $parameter_id;

                if($validationMethods[$i] != "SQL"){
                    if(in_array($validationMethods[$i],['LENGTH','MIN','MAX','LENGTH_BETWEEN','DATE'])){
                        $validation_key = isset(explode(":",$validationMethods[$i])[0]) ? explode(':',$validationMethods[$i])[0] : "";
                        $validation_val = isset($edited_validation_method_val[$i]) ? $edited_validation_method_val[$i] : "";
                        $ApiParamValidation->validation_method = $validation_key.":".$validation_val;
                    }else {
                        $ApiParamValidation->validation_method = $validationMethods[$i];
                    }
                }else{
                    $ApiParamValidation->validation_method = 'SQL';
                    $ApiParamValidation->exe_sql = $validation_exe_sql[$validationSqlIndexCounter];
                    $validationSqlIndexCounter ++;
                }

                $ApiParamValidation->message = $validation_text[$i];
                $ApiParamValidation->save();
            }


//            if(isset($validation_exe_sql) && $validation_exe_sql != ''){
//                $ApiParamValidation = new DynamicApiParametersValidation();
//                $ApiParamValidation->request_parameter_id = $parameter_id;
//                $ApiParamValidation->validation_method = 'SQL';
//                $ApiParamValidation->exe_sql = $validation_exe_sql;
//                $ApiParamValidation->message = $sql_validation_message;
//                $ApiParamValidation->save();
//            }

            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateParameterName(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
        }

        $rules = [
            'parameter_name' => 'required',
            'parameter_id' => 'required',
            'api_id' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $parameter_id      = Encryption::decodeId($request->get('parameter_id'));
            $api_id            = Encryption::decodeId($request->get('api_id'));
            $parameter_name    = str_replace(' ','_',trim($request->get('parameter_name')));


            $existingParam = DynamicApiParameters::where('id',$parameter_id)->first(['request_parameter_name']);
            if($existingParam->request_parameter_name != $parameter_name){
                $parameterIsExists = DynamicApiParameters::where('api_id',$api_id)
                    ->where('request_parameter_name',$parameter_name)
                    ->count();

                if($parameterIsExists > 0){
                    return response()->json(['responseCode' => 0, 'message' => 'Parameter already exists']);
                }
            }


            DynamicApiParameters::where('id',$parameter_id)
                ->update([
                    'request_parameter_name'=>$parameter_name
                ]);

            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    public function deleteParameter(Request $request){

//        if (!ACL::getAccsessRight('settings', 'A')) {
//            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
//        }

        $rules = [
            'parameter_id' => 'required',
            'api_id' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $parameter_id      = Encryption::decodeId($request->get('parameter_id'));
            $api_id      = Encryption::decodeId($request->get('api_id'));

            DynamicApiParameters::where('id',$parameter_id)->delete();
            DynamicApiParametersValidation::where('request_parameter_id',$parameter_id)->where('validation_method','!=','SQL')->delete();

            $parameterCounter = DynamicApiParameters::where('api_id',$api_id)->count();

            return response()->json(['responseCode' => 1,'total_param' => $parameterCounter, 'message' => 'Successfully deleted information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    public function deleteApi(Request $request){

//        if (!ACL::getAccsessRight('settings', 'A')) {
//            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
//        }

        $rules = [
            'api_id' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $api_id      = Encryption::decodeId($request->get('api_id'));

            DB::beginTransaction();

            DynamicApiList::where('id',$api_id)->delete();
            $parameter_ids = DynamicApiParameters::where('api_id',$api_id)->get(['id']);

            foreach ($parameter_ids as $parameter){
                DynamicApiParametersValidation::where('request_parameter_id',$parameter->id)->where('validation_method','!=','SQL')->delete();
            }

            DynamicApiParameters::where('api_id',$api_id)->delete();
            DynamicApiOperationsSet::where('api_id',$api_id)->delete();
            DynamicApiOutputs::where('api_id',$api_id)->delete();

            DB::commit();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully deleted api']);

        } catch (\Exception $e) {
            DB::rollback(); //DB rollback
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


}
