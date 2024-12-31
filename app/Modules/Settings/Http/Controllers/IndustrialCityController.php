<?php


namespace App\Modules\Settings\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\IndustrialCityList;
use Illuminate\Http\Request;

class IndustrialCityController extends Controller
{
    public function industrialCityList(Request $request)
    {
        $search_input = $request->get('search');
        $limit = $request->get('limit');
        $query = IndustrialCityList::leftJoin('area_info as district', 'district.area_id', '=', 'industrial_city_list.district_en')
            ->leftJoin('area_info as upazila', 'upazila.area_id', '=', 'industrial_city_list.upazila_en')
            ->orderBy('id', 'desc')
            ->select(['industrial_city_list.*',
                'district.area_nm_ban as district_name', 'upazila.area_nm_ban as upazila_name']);
        if ($search_input) {
            $query->where(function ($query) use ($search_input) {
                $query->where('name', 'like', '%' . $search_input . '%')
                    ->orWhere('name_en', 'like', '%' . $search_input . '%')
                    ->orWhere('district', 'like', '%' . $search_input . '%')
                    ->orWhere('district_en', 'like', '%' . $search_input . '%');

            });
        }
        $data = $query->paginate($limit);
        $data->getCollection()->transform(function ($industrial, $key) {
            return ['si' => $key + 1, 'id' => Encryption::encodeId($industrial->id), 'name' => $industrial->name, 'district_name' => $industrial->district_name, 'upazila_name' => $industrial->upazila_name, 'status' => $industrial->status, 'type' => $industrial->type,
            ];
        });

        return response()->json($data);
    }

    public function getHomeoffice(){

        $homeOfficeData = IndustrialCityList::where('type', 0)->get(['id', 'name']);
        return response()->json(['homeOfficeData'=>$homeOfficeData]);
    }

    public function industrialCityStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
//        $this->validate($request, [
//            'name_en' => 'required',
//            'name' => 'required',
//            'district_en' => 'required',
//            'district' => 'required',
//            'upazila_en' => 'required',
//            'upazila' => 'required',
//            'status' => 'required',
//            'type' => 'required',
//        ]);
        if ( $request->get('type') == '0'){
            $this->validate($request, [
                'name_en' => 'required',
                'name' => 'required',
                'district_en' => 'required',
                'district' => 'required',
                'upazila_en' => 'required',
                'upazila' => 'required',
                'status' => 'required',
                'type' => 'required',
                'details_en' => 'required',
                'details' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
        }

        if ( $request->get('type') == '1'){
            $this->validate($request, [
                'office_short_code' => 'required',
                'h_office_id' => 'required',
                'name_en' => 'required',
                'name' => 'required',
                'district_en' => 'required',
                'district' => 'required',
                'upazila_en' => 'required',
                'upazila' => 'required',
                'status' => 'required',
                'type' => 'required',
            ]);
        }

        try {
            $image = $request->file('image');
            $path = "uploads/industrial_city";

            if ($request->hasFile('image')) {
                $img_file = 'industrial_city_' . md5(uniqid()) . '.' . $image->getClientOriginalExtension();
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $image->move($path, $img_file);
                $filepath = $path . '/' . $img_file;
            }
            if($request->get('type') == '0'){
                IndustrialCityList::create(
                    array(
                        'name' => $request->get('name'),
                        'name_en' => $request->get('name_en'),
                        'district_en' => $request->get('district_en'),
                        'district' => $request->get('district'),
                        'upazila_en' => $request->get('upazila_en'),
                        'upazila' => $request->get('upazila'),
                        'details_en' => $request->get('details_en'),
                        'details' => $request->get('details'),
                        'latitude' => $request->get('latitude'),
                        'longitude' => $request->get('longitude'),
                        'establish_year' => $request->get('establish_year'),
                        'land_amount' => $request->get('land_amount'),
                        'per_acre_price' => $request->get('per_acre_price'),
                        'used_plot' => $request->get('used_plot'),
                        'ind_plot_no' => $request->get('ind_plot_no'),
                        'total_plot_allocated' => $request->get('total_plot_allocated'),
                        'ind_unit_total' => $request->get('ind_unit_total'),
                        'ind_unit_under_prod' => $request->get('ind_unit_under_prod'),
                        'ind_unit_under_cons' => $request->get('ind_unit_under_cons'),
                        'ind_unit_off' => $request->get('ind_unit_off'),
                        'ind_unit_allocate_wait' => $request->get('ind_unit_allocate_wait'),
                        'order' => $request->get('order'),
                        'status' => $request->get('status'),
                        'type' => $request->get('type'),
                        'image' => $filepath,
                        'created_by' => CommonFunction::getUserId()
                    ));
            }else{
                IndustrialCityList::create(
                    array(
                        'office_short_code' => $request->get('office_short_code'),
                        'h_office_id' => $request->get('h_office_id'),
                        'name' => $request->get('name'),
                        'name_en' => $request->get('name_en'),
                        'district_en' => $request->get('district_en'),
                        'district' => $request->get('district'),
                        'upazila_en' => $request->get('upazila_en'),
                        'upazila' => $request->get('upazila'),
                        'order' => $request->get('order'),
                        'status' => $request->get('status'),
                        'type' => $request->get('type'),
                        'created_by' => CommonFunction::getUserId()
                    ));
            }

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
    }

    public function editIndustrialCity($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $industryData = IndustrialCityList::where('id', $id)->first();
        $homeOfficeData = IndustrialCityList::where('type', 0)->get(['id', 'name']);

        return response()->json(['industryData'=> $industryData, 'homeOfficeData'=>$homeOfficeData]);
    }

    public function updateIndustrialCity(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($request->id);

        $this->validate($request, [
            'name_en' => 'required',
            'name' => 'required',
            'district_en' => 'required',
            'district' => 'required',
            'upazila_en' => 'required',
            'upazila' => 'required',
            'status' => 'required',
            'type' => 'required',
        ]);
        if ( $request->get('type') == '0'){
            $this->validate($request, [
                'name_en' => 'required',
                'name' => 'required',
                'district_en' => 'required',
                'district' => 'required',
                'upazila_en' => 'required',
                'upazila' => 'required',
                'status' => 'required',
                'type' => 'required',
                'details_en' => 'required',
                'details' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
            if ($request->hasFile('image')) {
                $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:1024';
                $this->validate($request, $rules);
            }

        }

        if ( $request->get('type') == '1'){
            $this->validate($request, [
                'office_short_code' => 'required',
                'name_en' => 'required',
                'name' => 'required',
                'district_en' => 'required',
                'district' => 'required',
                'upazila_en' => 'required',
                'upazila' => 'required',
                'status' => 'required',
                'type' => 'required',
                'h_office_id' => 'required',
            ]);
        }

        try {

            $industrialCity = IndustrialCityList::Where('id', $id)->first();

            if ( $request->get('type') == '0'){
                $industrialCity->name = $request->get('name');
                $industrialCity->name_en = $request->get('name_en');
                $industrialCity->district_en = $request->get('district_en');
                $industrialCity->district = $request->get('district');
                $industrialCity->upazila_en = $request->get('upazila_en');
                $industrialCity->upazila = $request->get('upazila');
                $industrialCity->details_en = $request->get('details_en');
                $industrialCity->details = $request->get('details');
                $industrialCity->latitude = $request->get('latitude');
                $industrialCity->longitude = $request->get('longitude');

                $image = $request->file('image');
                $path = "uploads/industrial_city";
                if ($request->hasFile('image') && !empty($request->image)) {
                    $img_file = 'industrial_city_' . md5(uniqid()) . '.' . $image->getClientOriginalExtension();
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $image->move($path, $img_file);
                    $filepath = $path . '/' . $img_file;
                    $industrialCity->image = $filepath;
                }

                $industrialCity->order = $request->get('order');
                $industrialCity->status = $request->get('status');
                $industrialCity->type = $request->get('type');
                $industrialCity->establish_year = $request->get('establish_year');
                $industrialCity->land_amount = $request->get('land_amount');
                $industrialCity->per_acre_price = $request->get('per_acre_price');
                $industrialCity->used_plot = $request->get('used_plot');
                $industrialCity->ind_plot_no = $request->get('ind_plot_no');
                $industrialCity->total_plot_allocated = $request->get('total_plot_allocated');
                $industrialCity->ind_unit_total = $request->get('ind_unit_total');
                $industrialCity->ind_unit_under_prod = $request->get('ind_unit_under_prod');
                $industrialCity->ind_unit_under_cons = $request->get('ind_unit_under_cons');
                $industrialCity->ind_unit_off = $request->get('ind_unit_off');
                $industrialCity->ind_unit_allocate_wait = $request->get('ind_unit_allocate_wait');
                $industrialCity->created_by = CommonFunction::getUserId();
            }else{
                $industrialCity->office_short_code = $request->get('office_short_code');
                $industrialCity->h_office_id = $request->get('h_office_id');
                $industrialCity->name = $request->get('name');
                $industrialCity->name_en = $request->get('name_en');
                $industrialCity->district_en = $request->get('district_en');
                $industrialCity->district = $request->get('district');
                $industrialCity->upazila_en = $request->get('upazila_en');
                $industrialCity->upazila = $request->get('upazila');

                $industrialCity->details_en = '';
                $industrialCity->details = '';
                $industrialCity->latitude = '';
                $industrialCity->longitude = '';
                $industrialCity->image = '';

                $industrialCity->order = $request->get('order');
                $industrialCity->status = $request->get('status');
                $industrialCity->type = $request->get('type');
                $industrialCity->created_by = CommonFunction::getUserId();
            }

            $industrialCity->save();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
    }

    public function upazilaName(Request $request)
    {
        $districts = Area::orderBy('area_nm')->where('area_type', 3)->get();

        return response()->json($districts);
    }

    public function getUpazila(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $districtId = $request->get('districtId');

        $districts = Area::where('pare_id', $districtId)->orderBy('area_nm', 'asc')->select('area_nm', 'area_id', 'area_nm_ban')->get();
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }
}
