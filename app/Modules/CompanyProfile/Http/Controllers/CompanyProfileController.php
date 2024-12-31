<?php

namespace App\Modules\CompanyProfile\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyDirector;
use App\Modules\CompanyProfile\Models\CompanyType;
use App\Modules\CompanyProfile\Models\InvestorInfo;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\CompanyProfile\Models\InvestingCountry;
use App\Modules\CompanyProfile\Models\Designation;
use App\Modules\CompanyProfile\Models\IndustrialCategory;
use App\Modules\CompanyProfile\Models\IndustrialSectorSubSector;
use App\Modules\CompanyProfile\Models\InvestmentType;
use App\Modules\CompanyProfile\Models\RegistrationType;
use App\Modules\Settings\Models\IndustrialCityList;
use App\Modules\Settings\Models\Area;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\View;

class CompanyProfileController extends Controller
{

    public function index()
    {

        return view("CompanyProfile::index");
    }

    public function create($company_id = '')
    {
        if ($company_id != '') {
            $company_id = Encryption::decodeId($company_id);
        } else {
            $userType = Auth::user()->user_type;
            if (CommonFunction::checkEligibility() != 1
                and $userType == '5x505') {
                Session::flash('error', 'You are not eligible for apply ! [CAM1020]');
                return redirect('dashboard');
            }
            $company_id = Auth::user()->working_company_id;
            Session::forget('ceoInfo');
        }
        //        Session::forget('directorsInfo');

        $data['regOffice'] = IndustrialCityList::where('status', 1)->where('type', 1)->where('is_archive', 0)->orderBy('name')->pluck('name_en as name_bn', 'id')->toArray();
        $data['regType'] = RegistrationType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
        $data['companyType'] = CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
        $data['investmentType'] = InvestmentType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
        $data['industrialCategory'] = IndustrialCategory::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
        $data['industrialSector'] = IndustrialSectorSubSector::where('type', 1)->where('status', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
        $data['industrialSubSector'] = IndustrialSectorSubSector::where('type', 2)->where('status', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
        $data['divisions'] = Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['thanas'] = Area::where('area_type', 3)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['designation'] = Designation::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
        $data['companyDirector'] = CompanyDirector::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
        $data['nationality'] = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
            ->orderby('nationality', 'asc')->pluck('nationality', 'id')->toArray();

        $data["companyProfile"] = CompanyInfo::where('id', $company_id)->first();

        if ($data["companyProfile"]  == null) {
            //            if(Session::has('directorsInfo')){
            //                Session::forget('directorsInfo');
            //            }
            return view("CompanyProfile::create", $data);
        } else {

            $data["companyProfile"] = CompanyInfo::leftJoin('registration_type as reg_type', 'reg_type.id', '=', 'company_info.regist_type')
                ->leftJoin('investment_type as inv_type', 'inv_type.id', '=', 'company_info.invest_type')
                ->leftJoin('ind_sector_info as ind_sect', 'ind_sect.id', '=', 'company_info.ins_sector_id')
                ->leftJoin('ind_sector_info as ind_s_sect', 'ind_s_sect.id', '=', 'company_info.ins_sub_sector_id')
                ->leftJoin('company_type as com_type', 'com_type.id', '=', 'company_info.org_type')
                ->leftJoin('industrial_category as ind_cat', 'ind_cat.id', '=', 'company_info.ind_category_id')
                ->leftJoin('company_inv_country as inv_con', 'inv_con.company_id', '=', 'company_info.id')
                ->leftJoin('country_info as con_info', 'con_info.id', '=', 'inv_con.id')
                ->where('company_info.id', $company_id)
                ->first([
                    'company_info.*',
                    'reg_type.name_bn as reg_type_bn',
                    'inv_type.name_bn as inv_type_bn',
                    'ind_sect.name_bn as ind_sect_bn',
                    'ind_s_sect.name_bn as ind_s_sect_bn',
                    'com_type.name_bn as com_type_bn',
                    'ind_cat.name_bn as ind_cat_bn',
                    DB::raw('group_concat(con_info.name) as con_name'),
                ]);
                $data['app_count']= ProcessList::where('process_type_id',1)
                ->where('company_id',$company_id)
                ->where('status_id','!=','-1')
                ->count();
            return view("CompanyProfile::edit-view", $data);
        }
    }

    public function getCountryByInvestmentType(Request $request)
    {

        $investment_type_id = $request->get('investment_type_id');

        $countryList = Countries::where('country_status', 'yes');
        if ($investment_type_id == 2) {
            $countryList->where('id', '!=', 18);
        } elseif ($investment_type_id == 3) {
            $countryList->where('id', 18);
        }
        $countryList = $countryList->select(DB::raw("CONCAT(id,' ') AS id"), 'nicename')->orderBy('nicename')->pluck('nicename', 'id')->toArray();

        $data = ['responseCode' => 1, 'data' => $countryList];
        return response()->json($data);
    }

    public function getIndustryByInvestment(Request $request)
    {

        $total_investment = $request->get('total_investment');

        $industry_category = IndustrialCategory::where('status', 1)->get();
        $oss_fee = 0;
        $DataArray = [];
        foreach ($industry_category as $industry_category) {
            $DataArray[$industry_category->id] = [
                'industry_category_id' => $industry_category->id,
                'oss_fee' => $industry_category->oss_fee
            ];

            if (
                $total_investment >= $industry_category->inv_limit_start
                && $total_investment <= $industry_category->inv_limit_end
            ) {

                $industry_category_id = $industry_category->id;
                $oss_fee = $industry_category->oss_fee;

                break;
            }
        }

        if ($total_investment >= '500000001') {
            $industry_category_id = $DataArray[5]['industry_category_id'];
            $oss_fee = $DataArray[5]['oss_fee'];
        }

        $data = ['responseCode' => 1, 'data' => $industry_category_id ?? 0, 'oss_fee' => $oss_fee];
        return response()->json($data);
    }

    public function getSubSectorBySector(Request $request)
    {

        $industrial_sector_id = $request->get('industrial_sector_id');

        $industrialSubSector = IndustrialSectorSubSector::select(DB::raw("CONCAT(id,' ') AS id"), 'name_bn')
            ->where('type', 2)->where('pare_id', $industrial_sector_id)
            ->where('status', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();

        $data = ['responseCode' => 1, 'data' => $industrialSubSector];
        return response()->json($data);
    }



    //    company profile store
    public function storeCompany(Request $request)
    {

        //        $this->validate($request, [
        //            'company_name_english' => 'required',
        //            'company_name_bangla' => 'required',
        //            'reg_type_id' => 'required',
        //            'company_type_id' => 'required',
        //            'investment_type_id' => 'required',
        //            'investing_country_id' => 'required',
        //            'total_investment' => 'required|numeric',
        //            'industrial_category_id' => 'required',
        //            'industrial_sector_id' => 'required',
        //            'industrial_sub_sector_id' => 'required',
        //            'company_office_division_id' => 'required',
        //            'company_office_district_id' => 'required',
        //            'company_office_thana_id' => 'required',
        //            'company_office_postCode' => 'required',
        //            'company_office_address' => 'required',
        //            'company_office_email' => 'required|email',
        //            'company_office_mobile' => 'required|Max:50|regex:/[0-9+,-]$/',
        //            'company_ceo_name' => 'required',
        //            'company_ceo_fatherName' => 'required',
        //            'company_ceo_nationality' => 'required',
        //            'company_ceo_nid' => 'required',
        //            'company_ceo_dob' => 'required',
        //            'company_ceo_designation_id' => 'required',
        //            'company_ceo_division_id' => 'required',
        //            'company_ceo_district_id' => 'required',
        //            'company_ceo_thana_id' => 'required',
        //            'company_ceo_postCode' => 'required',
        //            'company_ceo_address' => 'required',
        //            'company_ceo_email' => 'required|email',
        //            'company_ceo_mobile' => 'required|Max:50|regex:/[0-9+,-]$/',
        //            'ceo_name' => 'required',
        //            'ceo_designation_id' => 'required',
        //            'signature_upload' => 'required',
        //            'pref_reg_office' => 'required',
        //            'company_main_works' => 'required',
        //            'manufacture_starting_date' => 'required',
        //            'project_deadline' => 'required',
        //        ]);
        //
        //        if ($request->same_address){
        //
        //        }else{
        //            $this->validate($request, [
        //                'company_factory_division_id' => 'required',
        //                'company_factory_district_id' => 'required',
        //                'company_factory_thana_id' => 'required',
        //                'company_factory_postCode' => 'required',
        //                'company_factory_address' => 'required',
        //                'company_factory_email' => 'required|email',
        //                'company_factory_mobile' => 'required|Max:50|regex:/[0-9+,-]$/',
        //            ]);
        //        ]);

        try {

            DB::beginTransaction();
            $organization = new CompanyInfo();
            $organization->org_nm = $request->get('company_name_english');
            $organization->org_nm_bn = $request->get('company_name_bangla');
            $organization->regist_type = $request->get('reg_type_id');
            $organization->org_type = $request->get('company_type_id');
            $organization->invest_type = $request->get('investment_type_id');
            $organization->investment_limit = $request->get('total_investment');
            $organization->ind_category_id = $request->get('industrial_category_id');
            $organization->ins_sector_id = $request->get('industrial_sector_id');
            $organization->ins_sub_sector_id = $request->get('industrial_sub_sector_id');
            $organization->office_division = $request->get('company_office_division_id');
            $organization->office_district = $request->get('company_office_district_id');
            $organization->office_thana = $request->get('company_office_thana_id');
            $organization->office_postcode = $request->get('company_office_postCode');
            $organization->office_location = $request->get('company_office_address');
            $organization->office_email = $request->get('company_office_email');
            $organization->office_mobile = $request->get('company_office_mobile');


            if ($request->same_address) {
                $organization->is_same_address = 1;
                $organization->factory_division = $request->get('company_office_division_id');
                $organization->factory_district = $request->get('company_office_district_id');
                $organization->factory_thana = $request->get('company_office_thana_id');
                $organization->factory_postcode = $request->get('company_office_postCode');
                $organization->factory_location = $request->get('company_office_address');
                $organization->factory_email = $request->get('company_office_email');
                $organization->factory_mobile = $request->get('company_office_mobile');
            } else {
                $organization->is_same_address = 0;
                $organization->factory_division = $request->get('company_factory_division_id');
                $organization->factory_district = $request->get('company_factory_district_id');
                $organization->factory_thana = $request->get('company_factory_thana_id');
                $organization->factory_postcode = $request->get('company_factory_postCode');
                $organization->factory_location = $request->get('company_factory_address');
                $organization->factory_email = $request->get('company_factory_email');
                $organization->factory_mobile = $request->get('company_factory_mobile');
            };

            $organization->director_type = $request->get('select_directors');
            $organization->ceo_name = $request->get('company_ceo_name');
            $organization->ceo_father_nm = $request->get('company_ceo_fatherName');
            $organization->nationality = $request->get('company_ceo_nationality');

            if ($request->get('company_ceo_nid')) {
                $organization->nid = $request->get('company_ceo_nid');
            }
            if ($request->get('company_ceo_passport')) {
                $organization->passport = $request->get('company_ceo_passport');
            }

            $organization->dob = date('Y-m-d', strtotime($request->get('company_ceo_dob')));
            $organization->designation = $request->get('company_ceo_designation_id');
            //            $organization->ceo_division = $request->get('company_ceo_division_id');
            //            $organization->ceo_district = $request->get('company_ceo_district_id');
            //            $organization->ceo_thana = $request->get('company_ceo_thana_id');
            //            $organization->ceo_postcode = $request->get('company_ceo_postCode');
            //            $organization->ceo_location = $request->get('company_ceo_address');
            $organization->ceo_email = $request->get('company_ceo_email');
            $organization->ceo_mobile = $request->get('company_ceo_mobile');
            //            $organization->entrepreneur_name = $request->get('ceo_name');
            //            $organization->entrepreneur_designation =$request->get('ceo_designation_id');

            if (!empty($request->correspondent_signature_base64)) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = "uploads/signature/" . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $splited = explode(',', substr($request->get('correspondent_signature_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 80));
                $base64ResizeImage = base64_decode($base64ResizeImage);
                $correspondent_signature_name = trim(uniqid('BSCIC_CP-' . 'sign' . '-', true) . '.' . 'jpeg');
                file_put_contents($path . $correspondent_signature_name, $base64ResizeImage);
                $organization->entrepreneur_signature = $path . $correspondent_signature_name;
            }

            $organization->bscic_office_id = $request->get('pref_reg_office');
            $organization->main_activities = $request->get('company_main_works');
            //            $organization->commercial_operation_dt = date('Y-m-d', strtotime($request->get('manufacture_starting_date')));
            //            $organization->project_deadline = date('Y-m-d', strtotime($request->get('project_deadline')));
            $organization->save();

            if (count($request->investing_country_id) > 0) {
                foreach ($request->investing_country_id as $item => $value) {
                    $investingCountry = new InvestingCountry();
                    $investingCountry->company_id             = $organization->id;
                    $investingCountry->country_id         = $value;
                    $investingCountry->status             = 1;
                    $investingCountry->save();
                }
            }
            $ceoInfo = Session::get('ceoInfo');
            if (empty($ceoInfo)) {
                Session::flash('error', 'Please fillup your CEO Info');
                return \redirect()->back()->withInput();
            }

            $directorInfo = Session::get('directorsInfo');
            if (empty($directorInfo)) {
                Session::flash('error', 'Please fillup your investor Info');
                return \redirect()->back()->withInput();
            }
            foreach ($directorInfo as $item => $value) {
                $investorInfo = new InvestorInfo();
                $investorInfo->company_id = $organization->id;
                $investorInfo->investor_nm = $value['l_director_name'];
                $investorInfo->designation = $value['designation'];
                $investorInfo->identity_type = $value['identity_type'];
                $investorInfo->identity_no = $value['nid_etin_passport'];
                $investorInfo->nationality = $value['l_director_nationality'];
                $investorInfo->date_of_birth = $value['date_of_birth'];
                $investorInfo->gender = $value['gender'];
                $investorInfo->status = 1;
                $investorInfo->save();
            }

            $userInfo = Users::where('id', Auth::user()->id)->first();
            $userInfo->working_company_id = $organization->id;
            $userInfo->save();

            Session::forget('directorsInfo');
            Session::forget('ceoInfo');

            DB::commit();
            Session::flash('success', 'Data entry successfully!');
            return Redirect::back();
        } catch (Exception $e) {
            return Redirect::back()->withInput();
        }
    }

    public  function getCompanyProfile(Request $request)
    {
        $company_id = Encryption::decodeId($request->get('company_id'));
        $companyProfile = CompanyInfo::leftJoin('registration_type as reg_type', 'reg_type.id', '=', 'company_info.regist_type')
            ->leftJoin('investment_type as inv_type', 'inv_type.id', '=', 'company_info.invest_type')
            ->leftJoin('ind_sector_info as ind_sect', 'ind_sect.id', '=', 'company_info.ins_sector_id')
            ->leftJoin('ind_sector_info as ind_s_sect', 'ind_s_sect.id', '=', 'company_info.ins_sub_sector_id')
            ->leftJoin('company_type as com_type', 'com_type.id', '=', 'company_info.org_type')
            ->leftJoin('industrial_category as ind_cat', 'ind_cat.id', '=', 'company_info.ind_category_id')
            ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'company_info.office_division')
            ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'company_info.office_district')
            ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'company_info.office_thana')
            ->leftJoin('area_info as factory_division', 'factory_division.area_id', '=', 'company_info.factory_division')
            ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'company_info.factory_district')
            ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'company_info.factory_thana')
            ->leftJoin('country_info as ceo_nationality', 'ceo_nationality.id', '=', 'company_info.nationality')
            ->leftJoin('designation as ceo_designation', 'ceo_designation.id', '=', 'company_info.designation')
            ->leftJoin('industrial_city_list', 'industrial_city_list.id', '=', 'company_info.bscic_office_id')
            ->leftJoin('company_director_type', 'company_director_type.id', '=', 'company_info.director_type')
            ->where('company_info.id', $company_id)
            ->first([
                'company_info.*',
                'reg_type.name_en as reg_type_bn',
                'inv_type.name_en as inv_type_bn',
                'ind_sect.name_en as ind_sect_bn',
                'ind_s_sect.name_en as ind_s_sect_bn',
                'com_type.name_en as com_type_bn',
                'ind_cat.name_en as ind_cat_bn',
                'office_division.area_nm as office_division_bn',
                'office_district.area_nm as office_district_bn',
                'office_thana.area_nm as office_thana_bn',
                'factory_division.area_nm as factory_division_bn',
                'factory_district.area_nm as factory_district_bn',
                'factory_thana.area_nm as factory_thana_bn',
                'ceo_nationality.nationality as ceo_nationality',
                'ceo_designation.name_en as ceo_designation_bn',
                'industrial_city_list.name as bscic_office_nm_bn',
                'company_director_type.name_en as director_type_name',
            ]);


//        dd($companyProfile);


        $investor_info = InvestorInfo::leftJoin('country_info', 'country_info.id', '=', 'company_investor_info.nationality')
            ->where('company_id', $company_id)
            ->get(['company_investor_info.*', 'country_info.nationality']);
        $countryInfo = InvestingCountry::leftJoin('country_info', 'company_inv_country.country_id', '=', 'country_info.id')
            ->where('company_id', $company_id)
            ->groupBy('company_id')
            ->first([
                DB::raw('group_concat(country_info.name) as con_name')
            ]);

        $regOffice = IndustrialCityList::where('status', 1)->where('type', 1)->where('is_archive', 0)->pluck('name as name_bn', 'id')->toArray();
        $companyUserType = CommonFunction::getCompanyUserType();

        return response()->json(['companyProfile' => $companyProfile, 'investorInfo' => $investor_info, 'countryInfo' => $countryInfo, 'regOffice' => $regOffice, 'companyUserType' => $companyUserType]);
    }

    public function getEditInfo(Request $request)
    {
        Session::forget('directorsInfo');
        $company_id = Encryption::decodeId($request->get('company_id'));
        $companyProfile = CompanyInfo::leftJoin('company_inv_country as country', 'country.company_id', '=', 'company_info.id')
            ->where('company_info.id', $company_id)
            ->first([
                'company_info.*',
                DB::raw('group_concat(country.id) as country_id'),
            ]);
        $investor_info = InvestorInfo::leftJoin('country_info', 'country_info.id', '=', 'company_investor_info.nationality')
            ->where('company_id', $company_id)
            ->get(['company_investor_info.*', 'country_info.nationality']);

        $countryInfo = InvestingCountry::where('company_id', $company_id)->first([DB::raw('group_concat(country_id) as country_id')]);
        return response()->json(['companyProfile' => $companyProfile, 'countryInfo' => $countryInfo, 'investorInfo' => $investor_info]);
    }

    public function updateGeneralInfo(Request $request)
    {
        $this->validate($request, [
            'company_name_english' => 'required',
//            'company_name_bangla' => 'required',
//            'reg_type_id' => 'required',
            'company_type_id' => 'required',
//            'investment_type_id' => 'required',
//            'investing_country_id' => 'required',
//            'total_investment' => 'required|numeric',
//            'industrial_category_id' => 'required',
//            'industrial_sector_id' => 'required',
//            'industrial_sub_sector_id' => 'required',
        ]);
        if(in_array($request->company_type_id, [3,4])){
            $this->validate($request, [
                'incorporation_date' => 'required',
                'incorporation_num' => 'required',
            ]);
        }

        $company_id = Encryption::decodeId($request->get('company_id'));

        try {

            $app_count= ProcessList::where('company_id',$company_id)
                ->where('status_id', '!=', -1)
                ->count();
            DB::beginTransaction();
            $organization = CompanyInfo::find($company_id);
            if($app_count==0){
                $organization->org_nm = $request->get('company_name_english');
                $organization->incorporation_date = date('Y-m-d',strtotime($request->get('incorporation_date')));
                $organization->incorporation_num = $request->get('incorporation_num');
                $organization->org_type = $request->get('company_type_id');

            }elseif (in_array($organization->org_type, [3,4])){
                $organization->incorporation_date = date('Y-m-d',strtotime($request->get('incorporation_date')));
                $organization->incorporation_num = $request->get('incorporation_num');
            }elseif (in_array($request->get('company_type_id'), [3,4])){
                $organization->org_type = $request->get('company_type_id');
                $organization->incorporation_date = date('Y-m-d',strtotime($request->get('incorporation_date')));
                $organization->incorporation_num = $request->get('incorporation_num');
            }
            else{
                return response()->json(['status' => false, 'messages' => "You will not able to update your information"]);
            }

            $organization->org_nm_bn = "demo";
            $organization->regist_type = "0";;

            $organization->invest_type = "0";
            $organization->investment_limit = "0";;
            $organization->ind_category_id = "0";;
            $organization->ins_sector_id = "0";;
            $organization->ins_sub_sector_id = "0";;
            $organization->save();


            if (!empty($request->investing_country_id)) {
                if ($request->get('investment_type_id') == 1) { // 1 = যৌথ উদ্যোগ
                    $investing_country_ids = $request->investing_country_id;
                } else {
                    $investing_country_ids = explode(",", $request->investing_country_id);
                }

                InvestingCountry::where('company_id', $company_id)->delete();
                foreach ($investing_country_ids as $key => $value) {
                    $investingCountry = new InvestingCountry();
                    $investingCountry->company_id             = $company_id;
                    $investingCountry->country_id         = $value;
                    $investingCountry->status             = 1;
                    $investingCountry->save();
                }
            }

            DB::commit();

            return response()->json(['status' => true, 'messages' => "Data updated Successfully"]);
        } catch (Exception $e) {
            return Redirect::back()->withInput();
        }
    }

    public function updateOfficeInfo(Request $request)
    {
                $this->validate($request, [
                    'company_office_division_id' => 'required',
                    'company_office_district_id' => 'required',
                    'company_office_thana_id' => 'required',
                    'company_office_postCode' => 'required',
                    'company_office_address' => 'required',
                    'company_office_email' => 'required',
                    'company_office_mobile' => 'required',
                ]);

        $company_id = Encryption::decodeId($request->get('company_id'));

        try {

            DB::beginTransaction();
            $organization = CompanyInfo::find($company_id);
            $organization->office_division = $request->get('company_office_division_id');
            $organization->office_district = $request->get('company_office_district_id');
            $organization->office_thana = $request->get('company_office_thana_id');
            $organization->office_postcode = $request->get('company_office_postCode');
            $organization->office_location = $request->get('company_office_address');
            $organization->office_email =  json_encode($request->get('company_office_email'));
            $organization->office_mobile = json_encode( $request->get('company_office_mobile'));
            $organization->save();
            DB::commit();

            return response()->json(['status' => true, 'messages' => "Data updated Successfully"]);
        } catch (Exception $e) {
            return Redirect::back()->withInput();
        }
    }

    public function updateFactoryInfo(Request $request)
    {
        //        $this->validate($request, [
        //            'company_factory_division_id' => 'required',
        //            'company_factory_district_id' => 'required',
        //            'company_factory_thana_id' => 'required',
        //            'company_factory_postCode' => 'required',
        //            'company_factory_address' => 'required',
        //            'company_factory_email' => 'required|email',
        //            'company_factory_mobile' => 'required|Max:50|regex:/[0-9+,-]$/',
        //        ]);

        $company_id = Encryption::decodeId($request->get('company_id'));

        try {

            DB::beginTransaction();
            $organization = CompanyInfo::find($company_id);
            $organization->factory_division = $request->get('company_factory_division_id');
            $organization->factory_district = $request->get('company_factory_district_id');
            $organization->factory_thana = $request->get('company_factory_thana_id');
            $organization->factory_postcode = $request->get('company_factory_postCode');
            $organization->factory_location = $request->get('company_factory_address');
            $organization->factory_email = $request->get('company_factory_email');
            $organization->factory_mobile = $request->get('company_factory_mobile');
            $organization->save();

            DB::commit();

            return response()->json(['status' => true, 'messages' => "Data updated Successfully"]);
        } catch (Exception $e) {
            return Redirect::back()->withInput();
        }
    }

    public function updateCeoInfo(Request $request)
    {
        //        $this->validate($request, [
        //            'company_ceo_name' => 'required',
        //            'company_ceo_fatherName' => 'required',
        //            'company_ceo_nationality' => 'required',
        //            'company_ceo_nid' => 'required',
        //            'company_ceo_dob' => 'required',
        //            'company_ceo_designation_id' => 'required',
        //            'company_ceo_division_id' => 'required',
        //            'company_ceo_district_id' => 'required',
        //            'company_ceo_thana_id' => 'required',
        //            'company_ceo_postCode' => 'required',
        //            'company_ceo_address' => 'required',
        //            'company_ceo_email' => 'required|email',
        //            'company_ceo_mobile' => 'required|Max:50|regex:/[0-9+,-]$/',
        //        ]);

        $company_id = Encryption::decodeId($request->get('company_id'));

        try {

            DB::beginTransaction();
            $organization = CompanyInfo::find($company_id);
            $organization->ceo_name = $request->get('company_ceo_name');
            $organization->director_type = $request->get('select_directors');
            $organization->ceo_father_nm = $request->get('company_ceo_fatherName');
            $organization->nationality = $request->get('company_ceo_nationality');
            $organization->address = $request->get('company_ceo_address');
//            dd($request->get('company_ceo_nid'),$request->get('company_ceo_passport'));
            if ($request->company_ceo_passport != null) {
                $organization->passport = $request->get('company_ceo_passport');
                $organization->nid = null;
            }
            if ($request->company_ceo_nid != null) {
                $organization->nid = $request->get('company_ceo_nid');
                $organization->passport = null;
            }
            $organization->dob =  date('Y-m-d', strtotime($request->get('company_ceo_dob')));
            $organization->designation = $request->get('company_ceo_designation_id');
            //            $organization->ceo_division = $request->get('company_ceo_division_id');
            //            $organization->ceo_district = $request->get('company_ceo_district_id');
            //            $organization->ceo_thana = $request->get('company_ceo_thana_id');
            //            $organization->ceo_postcode = $request->get('company_ceo_postCode');
            //            $organization->ceo_location = $request->get('company_ceo_address');
            $organization->ceo_email = $request->get('company_ceo_email');
            $organization->ceo_mobile = $request->get('company_ceo_mobile');

            $stringSign = $request->get('correspondent_signature_base64');
            if (strpos($stringSign, '/', 0) == 0) {
            } else {
                if (!empty($request->get('correspondent_signature_base64'))) {
                    //                $yearMonth = date("Y") . "-" . date("m") . "-";
                    $path = "uploads/client/";
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $splited = explode(',', substr($request->get('correspondent_signature_base64'), 5), 2);
                    $imageData = $splited[1];
                    $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 80));
                    $base64ResizeImage = base64_decode($base64ResizeImage);
                    $correspondent_signature_name = trim(uniqid('BSCIC_CP-' . 'sign' . '-', true) . '.' . 'jpeg');
                    file_put_contents($path . $correspondent_signature_name, $base64ResizeImage);
                    $organization->entrepreneur_signature = $path . $correspondent_signature_name;
                } else {
                    return response()->json(['status' => false, 'messages' => "Image must be png or jpg or jpeg format"]);
                }
            }

            $organization->save();

            DB::commit();

            return response()->json(['status' => true, 'messages' => "Data updated Successfully"]);
        } catch (Exception $e) {
            return Redirect::back()->withInput();
        }
    }


    public function updateActivitiesInfo(Request $request)
    {
        //        $this->validate($request, [
        //            'company_main_works' => 'required',
        //            'manufacture_starting_date' => 'required',
        //            'project_deadline' => 'required',
        //        ]);

        $company_id = Encryption::decodeId($request->get('company_id'));

        try {

            DB::beginTransaction();
            $organization = CompanyInfo::find($company_id);
            $organization->main_activities = $request->get('company_main_works');
            $organization->bscic_office_id = $request->get('pref_reg_office');
            //            $organization->commercial_operation_dt = date('Y-m-d', strtotime($request->get('manufacture_starting_date')));
            //            $organization->project_deadline = date('Y-m-d', strtotime($request->get('project_deadline')));
            $organization->save();

            DB::commit();

            return response()->json(['status' => true, 'messages' => "Data updated Successfully"]);
        } catch (Exception $e) {
            return Redirect::back()->withInput();
        }
    }

    public function updateCompanyDirectorInfo(Request $request)
    {
        $company_id = Encryption::decodeId($request->get('company_id'));

        try {

            $inverstorInfo = Session::get("directorsInfoAll");
            //            dd($inverstorInfo);
            DB::beginTransaction();
            foreach ($inverstorInfo as $row) {
                $organization = InvestorInfo::Where('company_id', $company_id)->where('identity_no', $row['nid_etin_passport'])->first();
                if (empty($organization)) {
                    $organization = new InvestorInfo();
                }

                if ($row['nationality_type'] == "bangladeshi") {
                    $nationality = 'bangladeshi';
                } else {
                    $nationality = 'foreign';
                }
                $organization->company_id = $company_id;
                $organization->nationality_type = $row['nationality_type'];
                $organization->identity_type = $row['identity_type'];
                $organization->investor_nm = $row['l_director_name'];
                $organization->identity_no = $row['nid_etin_passport'];
                $organization->date_of_birth = $row['date_of_birth'];
                $organization->designation = $row['designation'];
                $organization->nationality = $row['l_director_nationality'];
                $organization->nationality_type = $nationality;
                $organization->gender = $row['gender'];
                $organization->save();
            }
            DB::commit();

            Session::forget("directorsInfoAll");
            Session::forget("directorsInfo");

            return response()->json(['status' => true, 'messages' => "Data updated Successfully"]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'messages' => "wrong"]);
        }
    }

    public function uploadDocument()
    {
        return View::make('CompanyProfile::ajaxUploadFile');
    }

    public function getCompanyInfo() {
        $company_infos = CompanyInfo::where('company_status', 1)->get();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $company_infos
        ]);
    }
}
