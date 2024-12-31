<?php


namespace App\Modules\DynamicApiEngine\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\Encryption;
use App\Modules\DynamicApiEngine\accessPermissions;
use App\Modules\DynamicApiEngine\Models\DynamicApiClientMapping;
use App\Modules\DynamicApiEngine\Models\DynamicApiClients;
use App\Modules\DynamicApiEngine\Models\DynamicApiList;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use DB;

class DynamicApiAutheticationCmsController extends Controller
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
        $api_list = DynamicApiList::select('id', 'name')->where('is_active', '1')->pluck('name', 'id')->toArray();
        return view('DynamicApiEngine::api_authentications',compact('api_list'));
    }


    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function getClientList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $client_list = DynamicApiClients::get();

        return DataTables::of($client_list)
            ->editColumn('is_active', function ($client_list) {
                if ($client_list->status == '1') {
                    return '<span class="label label-primary"><b>Active</b></span>';
                } else {
                    return '<span class="label label-danger"><b>In-active</b></span>';
                }
            })
            ->addColumn('action', function ($client_list) {
                $action = '<button type="button" class="btn btn-info btn-sm clientEditModal" data-client_id="'.Encryption::encodeId($client_list->id).'" ><b class="spinner-icon-edit"></b><b> Edit</b></button> &nbsp;';
                $action .= ' <button type="button" class="btn btn-success btn-sm assignApi" data-client_id="'.Encryption::encodeId($client_list->id).'"> <b class="spinner-icon"></b><b> Assign API </b></button> &nbsp;';
                $action .= ' <button type="button" class="btn btn-danger btn-sm deleteClient" data-client_id="'.Encryption::encodeId($client_list->id).'"><b class="spinner-icon-delete"></b><b> Delete</b></button>';
                return $action;
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }



    public function apiMapInfo(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $encodedClientId = $request->get('client_id');
        $client_id = Encryption::decodeId($encodedClientId);

        $assigned_list = DynamicApiClientMapping::select('api_id')->where('client_id',$client_id)->pluck('api_id')->toArray();
        $api_list = DynamicApiList::select('id', 'name')->where('is_active', '1')->pluck('name', 'id')->toArray();
        $public_html = strval(view("DynamicApiEngine::Modal.assign_api", compact('api_list','assigned_list','encodedClientId')));

        return response()->json(['responseCode' => 1, 'html' => $public_html, 'client_id'=>$request->get('client_id')]);


    }


    public function storeApiMapInfo(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
        }

        $rules = [
            'client_id' => 'required',
            'assign_api' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $assign_api_array  = $request->get('assign_api');
            $client_id   = Encryption::decodeId($request->get('client_id'));

            DynamicApiClientMapping::where('client_id',$client_id)->delete();

            for ($i=0 ; $i< count($assign_api_array) ; $i++){
                $ApiClientMapping = new DynamicApiClientMapping();
                $ApiClientMapping->client_id = $client_id;
                $ApiClientMapping->api_id = $assign_api_array[$i];
                $ApiClientMapping->status = 1;
                $ApiClientMapping->save();
            }

            return response()->json(['responseCode' => 1, 'message' => 'Successfully stored information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    public function storeApiClientInfo(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
        }

        $rules = [
            'client_name' => 'required',
            'token_expire_time' => 'required',
            'grant_type' => 'required',
            'client_id' => 'required',
            'api_allowed_ips' => 'required',
            'client_secret' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $client_name  = strtoupper($request->get('client_name'));
            $token_expire_time  = $request->get('token_expire_time');
            $grant_type  = $request->get('grant_type');
            $client_id  = $request->get('client_id');
            $client_secret  = $request->get('client_secret');
            $api_allowed_ips  = $request->get('api_allowed_ips');

            $isExistsClient = DynamicApiClients::where('client_name',$client_name)->count()>0;
            if($isExistsClient == true){return response()->json(['responseCode' => 0, 'message' => 'Client already exists']);};

            $ApiClient = new DynamicApiClients();
            $ApiClient->client_name = $client_name;
            $ApiClient->token_expire_time = $token_expire_time;
            $ApiClient->grant_type = $grant_type;
            $ApiClient->client_id = $client_id;
            $ApiClient->client_secret = $client_secret;
            $ApiClient->allowed_ips = $api_allowed_ips;
            $ApiClient->save();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully stored information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    public function getApiClientInfo(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->aclName, 'V'))
            abort('401', 'You have no access right! This incidence will be reported. Contact with system admin for more information.');


        $encodedClientId = $request->get('client_id');
        $client_id = Encryption::decodeId($encodedClientId);

        $client_list = DynamicApiClients::where('id', $client_id)->first();

        return response()->json(['responseCode' => 1, 'data' => $client_list, 'client_id'=>$encodedClientId]);


    }


    public function updateApiClientInfo(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
        }

        $rules = [
            'client_tbl_id' => 'required',
            'client_name' => 'required',
            'token_expire_time' => 'required',
            'grant_type' => 'required',
            'client_id' => 'required',
            'allowed_ips' => 'required',
            'client_secret' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $client_tbl_id = Encryption::decodeId($request->get('client_tbl_id'));
            $client_name  = strtoupper($request->get('client_name'));
            $token_expire_time  = $request->get('token_expire_time');
            $grant_type  = $request->get('grant_type');
            $client_id  = $request->get('client_id');
            $client_secret  = $request->get('client_secret');
            $allowed_ips  = $request->get('allowed_ips');

            $isExistsClient = DynamicApiClients::where('client_name',$client_name)->where('id','!=',$client_tbl_id)->count()>0;
            if($isExistsClient == true){return response()->json(['responseCode' => 0, 'message' => 'Client already exists']);};

            DynamicApiClients::where('id',$client_tbl_id)->update([
                'client_name'=>$client_name,
                'token_expire_time'=>$token_expire_time,
                'grant_type'=>$grant_type,
                'client_id'=>$client_id,
                'client_secret'=>$client_secret,
                'allowed_ips'=>$allowed_ips
            ]);

            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


    public function deleteApiClientInfo(Request $request){

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json(['responseCode' => 0, 'message' => 'You have no access right! Please contact system administration for more information.']);
        }

        $rules = [
            'client_id' => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Missing required value']);
        }


        try {

            $client_tbl_id = Encryption::decodeId($request->get('client_id'));

            DynamicApiClients::where('id',$client_tbl_id)->delete();
            DynamicApiClientMapping::where('client_id',$client_tbl_id)->delete();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully deleted information']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Something wrong']);
        }

    }


}
