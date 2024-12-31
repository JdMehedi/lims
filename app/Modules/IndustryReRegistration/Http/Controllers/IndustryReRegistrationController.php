<?php

namespace App\Modules\IndustryReRegistration\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyDirector;
use App\Modules\CompanyProfile\Models\CompanyType;
use App\Modules\CompanyProfile\Models\Designation;
use App\Modules\CompanyProfile\Models\IndustrialCategory;
use App\Modules\CompanyProfile\Models\IndustrialSectorSubSector;
use App\Modules\CompanyProfile\Models\InvestingCountry;
use App\Modules\CompanyProfile\Models\InvestmentType;
use App\Modules\CompanyProfile\Models\InvestorInfo;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\CompanyProfile\Models\RegistrationType;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\DocumentTypes;
use App\Modules\IndustryReRegistration\Models\AnnualProductionCapacity;
use App\Modules\IndustryReRegistration\Models\BusinessCategory;
use App\Modules\IndustryReRegistration\Models\RrIndImportedRawMaterial;
use App\Modules\IndustryReRegistration\Models\RrIndLocalRawMaterial;
use App\Modules\IndustryReRegistration\Models\SponsorsDirectors;
use App\Modules\IndustryReRegistration\Models\CsvUploadLog;
use App\Modules\IndustryReRegistration\Models\IndRegInvCountry;
use App\Modules\IndustryReRegistration\Models\IndustryReRegistration;
use App\Modules\IndustryReRegistration\Models\InvestorList;
use App\Modules\IndustryReRegistration\Models\LoanSourceCountry;
use App\Modules\IndustryReRegistration\Models\MachineryImported;
use App\Modules\IndustryReRegistration\Models\MachineryLocal;
use App\Modules\IndustryReRegistration\Models\PublicUtilityResource;
use App\Modules\IndustryReRegistration\Models\UtilityService;
use App\Modules\ProcessPath\Models\PayOrderAmountSetup;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\IndustrialCityList;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Settings\Models\Area;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class IndustryReRegistrationController extends Controller
{
    use SPPaymentManager, SPAfterPaymentManager;

    protected $process_type_id;
    protected $acl_name;

    public function __construct()
    {
        $this->process_type_id = 2; // 2 is for IndustryReRegistration
        $this->acl_name = 'IndustryReRegistration';
    }

    public function appForm(Request $request)
    {

        Session::forget('ceoInfo');
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->acl_name, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => 'You have no access right! Contact with system admin for more information']);
        }

        try {
            $data['vat_percentage']  = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');


//            $data['payment_config'] = PaymentConfiguration::where([
//                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
//                    'sp_payment_configuration.status' => 1,
//                    'sp_payment_configuration.is_archive' => 0
//                ])->first(['sp_payment_configuration.*']);
//
//            if (empty($data['payment_config'])) {
//                return response()->json([
//                    'responseCode' => 1,
//                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![IRN-10100]</h4>"
//                ]);
//            }


//            $data['vat_percentage']  = Configuration::where('caption','GOVT_VENDOR_VAT_FEE')->value('value');
            $companyId = CommonFunction::getUserCompanyWithZero();

            $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

//            if(!$data['companyInfo']){
//                return response()->json([
//                    'responseCode' => 1,
//                    'html' => 'Working Company Not Found!']);
//            }

//            $mode = '-A-';

            $data['regOffice'] = IndustrialCityList::where('status', 1)->where('type', 1)->where('is_archive', 0)->orderBy('name')->pluck('name as name_bn', 'id')->toArray();
            $data['regType'] = RegistrationType::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['companyType'] = CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['businessCategory'] = BusinessCategory::where('status', 1)->where('is_archive', 0)->where('category_type', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['investmentType'] = InvestmentType::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['industrialCategory'] = IndustrialCategory::where('status', 1)->where('is_archive', 0)->orderBy('id')->pluck('name_bn', 'id')->toArray();
            $data['industrialSector'] = IndustrialSectorSubSector::where('type', 1)->where('status', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['industrialSubSector'] = IndustrialSectorSubSector::where('type', 2)->where('status', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['divisions'] = Area::where('area_type', 1)->orderBy('area_nm_ban')->pluck('area_nm_ban', 'area_id')->toArray();
//            $data['districts'] = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm_ban', 'area_id')->toArray();
//            $data['thanas'] = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'ASC')->pluck('area_nm_ban', 'area_id')->toArray();
            $data['designation'] = Designation::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['nationality'] = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

            $data['public_utility'] = PublicUtilityResource::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->get(['id', 'name_en', 'name_bn']);
            $data['currencyBDT'] = Currencies::orderBy('code')->whereIn('code', ['BDT'])->where('is_archive', 0)->where('is_active', 1)->orderBy('code')->pluck('code', 'id')->toArray();
            $data['currencyBDT']['114'] = "টাকা";
            $data['totalFee'] = DB::table('industrial_category')->where('status', 1)->orderBy('id')->get([
                'inv_limit_start', 'inv_limit_end', 'reg_fee', 'name_bn', 'oss_fee_re_registration as oss_fee'
            ]);
            $data['country'] = Countries::where('country_status', 'yes')->orderBy('nicename')->pluck('nicename', 'id')->toArray();
            $data['companyDirector'] = CompanyDirector::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['process_type_id'] = $this->process_type_id;

//            $data['investing_country'] = InvestingCountry::where('company_id', $data['companyInfo']->id)->get(['country_id'])->toArray();

            $data['investing_country'] = "";
            $investors = [];
            if (!empty($data['companyInfo'])){
                $data['investing_country'] = InvestingCountry::where('company_id', $data['companyInfo']->id)->first([DB::raw('group_concat(country_id) as country_id')]);
                $investors = InvestorInfo::where('company_id', $data['companyInfo']->id)->get();

                if(!empty($investors)){
                    $this->setInvestorSession($investors);
                }
            }
            $data['companyUserType'] = CommonFunction::getCompanyUserType();

            $public_html = strval(view("IndustryReRegistration::application-form", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [VA-1010]']);
        }
    }

    public function appStore(Request $request)
    {

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->acl_name, $mode, $app_id))
            abort('400', "You have no access right! Please contact with system admin if you have any query.");

        // check Approved IN application, if have go back
        $company_id = CommonFunction::getUserCompanyWithZero();

        try {
            DB::beginTransaction();

            if ($request->get('app_id')) {
                $appData = IndustryReRegistration::find($app_id);
            } else {
                $appData = new IndustryReRegistration();
            }

            $appData->company_id = $company_id;
            $appData->service_name = $request->get('service_name_manual');
            $appData->manual_reg_number = $request->get('manual_reg_number');
            $appData->manual_reg_date =!empty($request->get('manual_reg_date'))? date('Y-m-d', strtotime($request->get('manual_reg_date'))):'';

            $appData->org_nm = $request->get('company_name_english');
            $appData->org_nm_bn = $request->get('company_name_bangla');
            $appData->project_nm = $request->get('project_name');
            $appData->regist_type = $request->get('reg_type_id');
            $appData->org_type = $request->get('company_type_id');
            $appData->business_category_id = $request->get('business_category_id');
            $appData->invest_type = $request->get('investment_type_id');
            $appData->investment_limit = $request->get('total_investment');
            $appData->ind_category_id = $request->get('industrial_category_id');
            $appData->ins_sector_id = $request->get('industrial_sector_id');
            $appData->ins_sub_sector_id = $request->get('industrial_sub_sector_id');
            $appData->office_division = $request->get('company_office_division_id');
            $appData->office_district = $request->get('company_office_district_id');
            $appData->office_thana = $request->get('company_office_thana_id');
            $appData->office_postcode = $request->get('company_office_postCode');
            $appData->office_location = $request->get('company_office_address');
            $appData->office_email = $request->get('company_office_email');
            $appData->office_mobile = $request->get('company_office_mobile');

            if ($request->same_address){
                $appData->is_same_address = 1;
                $appData->factory_division = $request->get('company_office_division_id');
                $appData->factory_district = $request->get('company_office_district_id');
                $appData->factory_thana = $request->get('company_office_thana_id');
                $appData->factory_postcode = $request->get('company_office_postCode');
                $appData->factory_location = $request->get('company_office_address');
                $appData->factory_email = $request->get('company_office_email');
                $appData->factory_mobile = $request->get('company_office_mobile');
            }else{
                $appData->is_same_address = 0;
                $appData->factory_division = $request->get('company_factory_division_id');
                $appData->factory_district = $request->get('company_factory_district_id');
                $appData->factory_thana = $request->get('company_factory_thana_id');
                $appData->factory_postcode = $request->get('company_factory_postCode');
                $appData->factory_location = $request->get('company_factory_address');
                $appData->factory_email = $request->get('company_factory_email');
                $appData->factory_mobile = $request->get('company_factory_mobile');
            };

            $appData->director_type = $request->get('select_directors');
            $appData->ceo_name = $request->get('company_ceo_name');
            $appData->ceo_father_nm = $request->get('company_ceo_fatherName');
            $appData->nationality = $request->get('company_ceo_nationality');
            $appData->nid = $request->get('company_ceo_nid');
            $appData->passport = $request->get('company_ceo_passport');

            if($request->company_ceo_passport !=''){
                $appData->passport = $request->get('company_ceo_passport');
                $appData->nid = null;
//                dd(13);
            }
            if($request->company_ceo_nid !=''){
                $appData->nid = $request->get('company_ceo_nid');
                $appData->passport = null;
//                dd(14);
            }
//            if($request->get('company_ceo_nid')){
//                $appData->nid = $request->get('company_ceo_nid');
//            }
//            if($request->get('company_ceo_passport')){
//                $appData->passport = $request->get('company_ceo_passport');
//            }

            $appData->dob =  date('Y-m-d', strtotime($request->get('company_ceo_dob')));
            $appData->designation = $request->get('company_ceo_designation_id');
//            $appData->ceo_division = $request->get('company_ceo_division_id');
//            $appData->ceo_district = $request->get('company_ceo_district_id');
//            $appData->ceo_thana = $request->get('company_ceo_thana_id');
//            $appData->ceo_postcode = $request->get('company_ceo_postCode');
//            $appData->ceo_location = $request->get('company_ceo_address');
            $appData->ceo_email = $request->get('company_ceo_email');
            $appData->ceo_mobile = $request->get('company_ceo_mobile');
//            $appData->entrepreneur_name = $request->get('ceo_name');
//            $appData->entrepreneur_designation =$request->get('ceo_designation_id');

            if (!empty($request->correspondent_signature_base64)) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = "uploads/client/signature/" . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $splited = explode(',', substr($request->get('correspondent_signature_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 80));
                $base64ResizeImage = base64_decode($base64ResizeImage);
                $correspondent_signature_name = trim(uniqid('BSCIC_CP-' . '-', true) . '.' . 'jpeg');
                file_put_contents($path . $correspondent_signature_name, $base64ResizeImage);
                $appData->entrepreneur_signature = $path . $correspondent_signature_name;
            }else{
                $signature_url = CompanyInfo::where('id', Auth::user()->working_company_id)->value('entrepreneur_signature');
                if(!empty($signature_url) && $signature_url!=""){
                    $appData->entrepreneur_signature =$signature_url;
                }
            }

            $appData->bscic_office_id = $request->get('pref_reg_office');
            $appData->main_activities = $request->get('company_main_works');
//            $appData->commercial_operation_dt =!empty($request->get('manufacture_starting_date'))? date('Y-m-d', strtotime($request->get('manufacture_starting_date'))):'';
//            $appData->project_deadline =!empty($request->get('project_deadline'))? date('Y-m-d', strtotime($request->get('project_deadline'))):'';

            $appData->sales_local = $request->get('local_sales');
            $appData->sales_foreign = $request->get('foreign_sales');
            $appData->apc_price_total = $request->get('apc_price_total');

            $appData->local_male = $request->get('local_male');
            $appData->local_female = $request->get('local_female');
            $appData->local_total = $request->get('local_total');
            $appData->foreign_male = $request->get('foreign_male');
            $appData->foreign_female = $request->get('foreign_female');
            $appData->foreign_total = $request->get('foreign_total');
            $appData->manpower_total = $request->get('manpower_total');
            $appData->manpower_local_ratio = $request->get('manpower_local_ratio');
            $appData->manpower_foreign_ratio = $request->get('manpower_foreign_ratio');

            $appData->local_land_ivst = (float)$request->get('local_land_ivst');
            $appData->local_land_ivst_ccy = $request->get('local_land_ivst_ccy');
            $appData->local_machinery_ivst = (float)$request->get('local_machinery_ivst');
            $appData->local_machinery_ivst_ccy = $request->get('local_machinery_ivst_ccy');
            $appData->local_building_ivst = (float)$request->get('local_building_ivst');
            $appData->local_building_ivst_ccy = $request->get('local_building_ivst_ccy');
            $appData->local_others_ivst = (float)$request->get('local_others_ivst');
            $appData->local_others_ivst_ccy = $request->get('local_others_ivst_ccy');
            $appData->local_wc_ivst = (float)$request->get('local_wc_ivst');
            $appData->local_wc_ivst_ccy = $request->get('local_wc_ivst_ccy');
            $appData->total_fixed_ivst = $request->get('total_fixed_ivst');
            $appData->total_fixed_ivst_million = $request->get('total_fixed_ivst_million');
            $appData->usd_exchange_rate = $request->get('usd_exchange_rate');
            $appData->total_invt_dollar = $request->get('total_invt_dollar');
            $appData->total_fee = $request->get('total_fee');

            $appData->ceo_taka_invest = $request->get('ceo_taka_invest');
            $appData->ceo_dollar_invest = $request->get('ceo_dollar_invest');
            $appData->ceo_loan_org_country = $request->get('ceo_loan_org_country');
            $appData->local_loan_taka = $request->get('local_loan_taka');
            $appData->local_loan_dollar = $request->get('local_loan_dollar');
            $appData->local_loan_org_country = $request->get('local_loan_org_country');
            $appData->foreign_loan_taka = $request->get('foreign_loan_taka');
            $appData->foreign_loan_dollar = $request->get('foreign_loan_dollar');
            $appData->foreign_loan_org_country = $request->get('foreign_loan_org_country');
            $appData->total_inv_taka = $request->get('total_inv_taka');
            $appData->total_inv_dollar = $request->get('total_inv_dollar');

            $appData->local_machinery_total = $request->get('local_machinery_total');
            $appData->imported_machinery_total = $request->get('imported_machinery_total');

//            $appData->raw_local_number = $request->get('local_raw_material_number');
//            $appData->raw_local_price = $request->get('local_raw_material_price');
//            $appData->raw_imported_number = $request->get('import_raw_material_number');
//            $appData->raw_imported_price = $request->get('import_raw_material_price');
            $appData->local_raw_price_total = $request->get('local_raw_price_total');
            $appData->imported_raw_price_total = $request->get('imported_raw_price_total');

            $appData->auth_person_nm = $request->get('auth_person_nm');
            $appData->auth_person_desig = $request->get('auth_person_desig');
            $appData->auth_person_address = $request->get('auth_person_address');
            $appData->auth_person_mobile = $request->get('auth_person_mobile');
            $appData->auth_person_email = $request->get('auth_person_email');

            if ($request->hasFile('authorization_letter')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/client/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('authorization_letter');
                $file_path = trim(uniqid('BSCIC_IR-'. '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $appData->authorization_letter = $yearMonth . $file_path;
            }

            if (!empty($request->correspondent_photo_base64)) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = "uploads/client/image/" . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $splited = explode(',', substr($request->get('correspondent_photo_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                $base64ResizeImage = base64_decode($base64ResizeImage);
                $correspondent_photo_name = trim(uniqid('BSCIC_IR-' . '-', true) . '.' . 'jpeg');
                file_put_contents($path . $correspondent_photo_name, $base64ResizeImage);
                $appData->auth_person_pic = $path . $correspondent_photo_name;
            }else{
                if(empty($appData->auth_person_pic) ){
                    $appData->auth_person_pic =Auth::user()->user_pic;
                }
            }

            $appData->accept_terms = (!empty($request->get('accept_terms')) ? 1 : 0);

            $appData->save();



            if (!empty($appData->id) && !empty($request->utility_id[0])) {

                foreach ($request->utility_id as $item => $value) {
                    $indRegUtilityId = $request->get('ind_reg_utility_id')[$item];
                    $utility = UtilityService::findOrNew($indRegUtilityId);
                    $utility->app_id = $appData->id;
                    $utility->utility_id = $request->get('utility_id')[$item];
                    if(isset($request->get('services_availability')[$item])){
                        $utility->services_availability = $request->get('services_availability')[$item];
                    }else{
                        $utility->services_availability = 0;
                    }
                    $utility->utility_distance = $request->get('utility_distance')[$item];
                    $utility->distance_unit = $request->get('distance_unit')[$item];

                    $utility->save();
                }
            }


            if (!empty($appData->id) && !empty($request->investing_country_id[0])) {
                $indRegCountryIds = [];
                foreach ($request->investing_country_id as $item => $value) {
                    $indRegCountryId = $request->get('ind_reg_inv_country_id')[$item];
                    $investingCountry = IndRegInvCountry::findOrNew($indRegCountryId);
                    $investingCountry->app_id = $appData->id;
                    $investingCountry->country_id = $value;
                    $investingCountry->save();

                    $indRegCountryIds[] = $investingCountry->id;
                }

                if (count($indRegCountryIds) > 0) {
                    IndRegInvCountry::where('app_id', $appData->id)->whereNotIn('id', $indRegCountryIds)->delete();
                }
            }

            $annualCapacityFields = ['service_name', 'quantity', 'unit', 'amount_bdt'];
            CommonComponent()->storeChildRow($request->all(), $annualCapacityFields, $appData->id, 'app_id', 'rr_ind_annual_prod_capacity');



            $loadSourceCountry = ['loan_country_id','loan_org_nm', 'loan_amount','loan_receive_date'];
            CommonComponent()->storeChildRow($request->all(), $loadSourceCountry, $appData->id, 'app_id', 'rr_ind_loan_source_country');

            if(empty($request->get('app_id'))){
                $localMachineFields = ['machinery_nm','machinery_qty', 'machinery_price'];
                CommonComponent()->storeChildRow($request->all(), $localMachineFields, $appData->id, 'app_id', 'rr_ind_machinery_local');

                $importedMachineFields = ['import_machinery_nm'=>'machinery_nm','import_machinery_qty'=>'machinery_qty','import_machinery_price'=>'machinery_price'];
                CommonComponent()->storeChildRow($request->all(), $importedMachineFields, $appData->id, 'app_id', 'rr_ind_machinery_imported');
            }

            $localRawMaterialFields = ['local_raw_material_name', 'local_raw_material_quantity', 'local_raw_material_unit', 'local_raw_material_amount_bdt'];
            CommonComponent()->storeChildRow($request->all(), $localRawMaterialFields, $appData->id, 'app_id', 'rr_ind_local_raw_material');

            $importedRawMaterialFields = ['imported_raw_material_name', 'imported_raw_material_quantity', 'imported_raw_material_unit', 'imported_raw_material_amount_bdt'];
            CommonComponent()->storeChildRow($request->all(), $importedRawMaterialFields, $appData->id, 'app_id', 'rr_ind_imported_raw_material');

            $directorInfo = Session::get('directorsInfo');
            if (!empty($appData->id) && count($directorInfo) > 0) {
                $indRegInvestorIds = [];
                foreach ($directorInfo as $item => $value) {

                    $investorId = $request->get('ind_reg_investor_id')[$item];
                    $investorInfo = InvestorList::findOrNew($investorId);
                    $investorInfo->process_type_id = $this->process_type_id;
                    $investorInfo->app_id = $appData->id;
                    $investorInfo->investor_nm = $value['l_director_name'];
                    $investorInfo->designation = $value['designation'];
                    $investorInfo->identity_type = $value['identity_type'];
                    $investorInfo->identity_no = $value['nid_etin_passport'];
                    $investorInfo->nationality = $value['l_director_nationality'];
                    $investorInfo->date_of_birth = date('Y-m-d', strtotime($value['date_of_birth']));
                    $investorInfo->gender = $value['gender'];
                    $investorInfo->save();

                    $indRegInvestorIds[] = $investorInfo->id;
                }

                if (count($indRegInvestorIds) > 0) {
                    InvestorList::where('app_id', $appData->id)->whereNotIn('id', $indRegInvestorIds)->delete();
                }

            }


            //  $processData->company_id = $company_id;

//Set category id for process differentiation


            $doc_type_id = $request->doc_type_key;
//            $doc_type_id = 0;
//            if ($doc_type_key) {
//                $doc_type_id = DocumentTypes::where('key', $doc_type_key)->value('id');
//            }

            //  Required Documents for attachment
            $process_type_id = $this->process_type_id;
            DocumentsController::storeAppDocuments($process_type_id, $doc_type_id, $appData->id, $request);

            $cat_id = 1;
            $officeid = $request->get('pref_reg_office');
            $processData = CommonComponent()->processDataInsertAndUpdate($this->process_type_id,$request,$appData->id,$company_id,$cat_id,$officeid);

            // Payment info will not be updated for resubmit
            if ($processData->status_id != 2 && !empty($appData->ind_category_id)) {
                $contactInfo = ['contact_no'=>!empty($request->get('sfp_contact_phone'))?$request->sfp_contact_phone:'','address'=>!empty($request->get('sfp_contact_address'))?$request->sfp_contact_address:''];

                $calculatedAmountArray = $this->unfixedAmountsForVendorServiceFee(1, 1);
                $payment_id = $this->storeSubmissionFeeData($appData->id, 1, $contactInfo, $calculatedAmountArray);
            }


//            $this->updateCompanyProfile($request, $appData, $processData);

            /*
             * if application submitted and status is equal to draft then
             * generate tracking number and payment initiate
             */



            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1) {
                if (empty($processData->tracking_no)) {
                    $dist_name = CommonFunction::getDistrictFirstTwoChar($appData->factory_district);
                    $officeShortCode = CommonFunction::getOfficeShortCode($appData->bscic_office_id);
                    $trackingPrefix = $dist_name . '-RR-' . date("Ymd") . '-'.$officeShortCode.'-';

                    CommonComponent()->trackingNoWithShortCode($this->process_type_id,$trackingPrefix,$processData->id);
                }
                DB::commit();

                return redirect('spg/initiate-multiple/' . Encryption::encodeId($payment_id));
            }

            // Send Email notification to user on application re-submit

            if ($processData->status_id == 2) {
                $appInfo = [
                    'app_id' => $processData->ref_id,
                    'status_id' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'tracking_no' => $processData->tracking_no,
                    'process_type_name' => 'Industry New',
                    'remarks' => '',
                ];

                $receiverInfo = Users::where('id', Auth::user()->id)->get(['user_email', 'user_phone']);
                //send email for application re-submission...
                CommonFunction::sendEmailSMS('APP_RESUBMIT', $appInfo, $receiverInfo);
            }

            DB::commit();

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]');
            }
            return redirect('client/industry-re-registration/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[IN-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function updateCompanyProfile($request, $appData, $processData){
        $company_id = CompanyInfo::where('id', Auth::user()->working_company_id)->value('id');

        try{
            if (!empty($company_id)){
                $companyInfo = CompanyInfo::find($company_id);
            }else{
                $companyInfo = new CompanyInfo();
            }

            $companyInfo->org_nm = $appData->org_nm;
            $companyInfo->org_nm_bn = $appData->org_nm_bn;
            $companyInfo->regist_type = $appData->regist_type;
            $companyInfo->org_type = $appData->org_type;
//            $companyInfo->business_category_id = $appData->business_category_id;
            $companyInfo->invest_type = $appData->invest_type;
            $companyInfo->investment_limit = $appData->investment_limit;
            $companyInfo->ind_category_id = $appData->ind_category_id;
            $companyInfo->ins_sector_id = $appData->ins_sector_id;
            $companyInfo->ins_sub_sector_id = $appData->ins_sub_sector_id;
            $companyInfo->office_division = $appData->office_division;
            $companyInfo->office_district = $appData->office_district;
            $companyInfo->office_thana = $appData->office_thana;
            $companyInfo->office_postcode = $appData->office_postcode;
            $companyInfo->office_location = $appData->office_location;
            $companyInfo->office_email = $appData->office_email;
            $companyInfo->office_mobile = $appData->office_mobile;

            $companyInfo->is_same_address = $appData->is_same_address;

            $companyInfo->factory_division = $appData->factory_division;
            $companyInfo->factory_district = $appData->factory_district;
            $companyInfo->factory_thana = $appData->factory_thana;
            $companyInfo->factory_postcode = $appData->factory_postcode;
            $companyInfo->factory_location = $appData->factory_location;
            $companyInfo->factory_email = $appData->factory_email;
            $companyInfo->factory_mobile = $appData->factory_mobile;


            $companyInfo->director_type = $appData->director_type;
            $companyInfo->ceo_name = $appData->ceo_name;
            $companyInfo->ceo_father_nm = $appData->ceo_father_nm;

            if($appData->passport !=''){
                $companyInfo->passport = $appData->passport;
                $companyInfo->nid = null;
            }
            if($appData->nid !=''){
                $companyInfo->nid = $appData->nid;
                $companyInfo->passport = null;
            }

//            $companyInfo->nid = $appData->nid;
//            $companyInfo->passport = $appData->passport;
            $companyInfo->dob = $appData->dob;
            $companyInfo->nationality = $appData->nationality;
            $companyInfo->designation = $appData->designation;
//            $companyInfo->ceo_division = $appData->ceo_division;
//            $companyInfo->ceo_district = $appData->ceo_district;
//            $companyInfo->ceo_thana = $appData->ceo_thana;
//            $companyInfo->ceo_postcode = $appData->ceo_postcode;
//            $companyInfo->ceo_location = $appData->ceo_location;
            $companyInfo->ceo_email = $appData->ceo_email;
            $companyInfo->ceo_mobile = $appData->ceo_mobile;
//            $companyInfo->entrepreneur_name = $appData->entrepreneur_name;
//            $companyInfo->entrepreneur_designation = $appData->entrepreneur_designation;
            $companyInfo->entrepreneur_signature = $appData->entrepreneur_signature;
            $companyInfo->bscic_office_id = $appData->bscic_office_id;
            $companyInfo->main_activities = $appData->main_activities;
//            $companyInfo->commercial_operation_dt = $appData->commercial_operation_dt;
//            $companyInfo->project_deadline = $appData->project_deadline;
            $companyInfo->save();

            $users = Users::where('id', Auth::user()->id)->first();
            $users->working_company_id = $companyInfo->id;
            $users->save();
            $processData->company_id =  $companyInfo->id;
            $processData->save();


            if (!empty($companyInfo->id) && !empty($request->investing_country_id[0])) {
                $indRegCountryIds = [];
                foreach ($request->investing_country_id as $item => $value) {
                    $indRegCountryId = $request->get('ind_reg_inv_country_id')[$item];
                    $investingCountry = InvestingCountry::findOrNew($indRegCountryId);
                    $investingCountry->company_id = $companyInfo->id;
                    $investingCountry->country_id = $value;
                    $investingCountry->save();

                    $indRegCountryIds[] = $investingCountry->id;
                }

                if (count($indRegCountryIds) > 0) {
                    InvestingCountry::where('company_id', $companyInfo->id)->whereNotIn('id', $indRegCountryIds)->delete();
                }
            }

            $directorInfo = Session::get('directorsInfo');
            if (!empty($companyInfo->id) && count($directorInfo) > 0) {
                $indRegInvestorIds = [];
                foreach ($directorInfo as $item => $value) {

                    $investorId = $request->get('ind_reg_investor_id')[$item];
                    $investorInfo = InvestorInfo::findOrNew($investorId);
                    $investorInfo->company_id = $companyInfo->id;
                    $investorInfo->investor_nm = $value['l_director_name'];
                    $investorInfo->designation = $value['designation'];
                    $investorInfo->identity_type = $value['identity_type'];
                    $investorInfo->identity_no = $value['nid_etin_passport'];
                    $investorInfo->nationality = $value['l_director_nationality'];
                    $investorInfo->date_of_birth = date('Y-m-d', strtotime($value['date_of_birth']));
                    $investorInfo->gender = $value['gender'];
                    $investorInfo->save();

                    $indRegInvestorIds[] = $investorInfo->id;
                }

                if (count($indRegInvestorIds) > 0) {
                    InvestorInfo::where('company_id', $companyInfo->id)->whereNotIn('id', $indRegInvestorIds)->delete();
                }

            }

        }catch (\Exception $e){
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
        }


    }

    public function appFormEdit($applicationId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        if (!ACL::getAccsessRight($this->acl_name, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => 'You have no access right! Contact with system admin for more information']);
        }

        try {
                $data['payment_config'] = PaymentConfiguration::where([
                        'sp_payment_configuration.process_type_id' => $this->process_type_id,
                        'sp_payment_configuration.status' => 1,
                        'sp_payment_configuration.is_archive' => 0
                    ])->first(['sp_payment_configuration.*']);

                $data['vat_percentage']  = Configuration::where('caption','GOVT_VENDOR_VAT_FEE')->value('value');

                if (empty($data['payment_config'])) {
                    return response()->json([
                        'responseCode' => 1,
                        'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![IRN-10100]</h4>"
                    ]);
                }

            $applicationId = Encryption::decodeId($applicationId);
            $process_type_id = $this->process_type_id;
            $data['vat_percentage']  = Configuration::where('caption','GOVT_VENDOR_VAT_FEE')->value('value');

            $data['regOffice'] = IndustrialCityList::where('status', 1)->where('type', 1)->where('is_archive', 0)->orderBy('name')->pluck('name as name_bn', 'id')->toArray();
            $data['regType'] = RegistrationType::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['companyType'] = CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['businessCategory'] = BusinessCategory::where('status', 1)->where('is_archive', 0)->where('category_type', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['investmentType'] = InvestmentType::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['industrialCategory'] = IndustrialCategory::where('status', 1)->where('is_archive', 0)->orderBy('id')->pluck('name_bn', 'id')->toArray();
            $data['industrialSector'] = IndustrialSectorSubSector::where('type', 1)->where('status', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['industrialSubSector'] = IndustrialSectorSubSector::where('type', 2)->where('status', 1)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['divisions'] = Area::where('area_type', 1)->orderBy('area_nm_ban')->pluck('area_nm_ban', 'area_id')->toArray();
//            $data['districts'] = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm_ban', 'area_id')->toArray();
//            $data['thanas'] = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'ASC')->pluck('area_nm_ban', 'area_id')->toArray();
            $data['designation'] = Designation::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['nationality'] = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality', 'asc')->pluck('nationality', 'id')->toArray();

            $data['currencyBDT'] = Currencies::orderBy('code')->whereIn('code', ['BDT'])->where('is_archive', 0)->where('is_active', 1)->orderBy('code')->pluck('code', 'id')->toArray();
            $data['currencyBDT']['114'] = "টাকা";
            $data['totalFee'] = DB::table('industrial_category')->orderBy('id')->where('status', 1)->get([
                'inv_limit_start', 'inv_limit_end', 'reg_fee', 'name_bn', 'oss_fee_re_registration as oss_fee'
            ]);
            $data['country'] = Countries::where('country_status', 'yes')->orderBy('nicename')->pluck('nicename', 'id')->toArray();
            $data['companyDirector'] = CompanyDirector::where('status', 1)->where('is_archive', 0)->orderBy('name_bn')->pluck('name_bn', 'id')->toArray();
            $data['process_type_id'] = $this->process_type_id;

            $data['appInfo'] = ProcessList::leftJoin('rr_ind_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })

                ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                    $join->on('sfp.app_id', '=', 'apps.id');
                    $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
                })
//                ->leftJoin('sp_payment as sfp', 'apps.id', '=', 'sp_payment.app_id')


                ->where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
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
                    'ps.status_name',
                    'ps.color',
                    'apps.*',

                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_tax',
                    'sfp.transaction_charge_amount as sfp_bank_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',
                ]);
            $data['loanSources'] = LoanSourceCountry::where('app_id', $data['appInfo']->id)->get();
            $data['investing_country'] = IndRegInvCountry::where('app_id', $data['appInfo']->id)
                ->first([DB::raw('group_concat(country_id) as country_id')]);
            $data['public_utility'] = UtilityService::leftJoin('rr_public_utility_resource', 'rr_public_utility_resource.id', '=', 'rr_ind_utility_services.utility_id')
                ->where('rr_ind_utility_services.app_id', $data['appInfo']->id)
                ->get(['rr_ind_utility_services.*', 'rr_public_utility_resource.name_en', 'rr_public_utility_resource.name_bn']);
            $data['loanSource'] = LoanSourceCountry::where('app_id', $data['appInfo']->id)->get(['loan_country_id','loan_org_nm', 'loan_amount','loan_receive_date'])->toArray();
            $data['annualProduction'] = AnnualProductionCapacity::where('app_id', $data['appInfo']->id)->get(['service_name', 'quantity', 'unit', 'amount_bdt'])->toArray();
            $data['localMachinery'] = MachineryLocal::where('app_id', $data['appInfo']->id)->get();
            $data['importedMachinery'] = MachineryImported::where('app_id', $data['appInfo']->id)->get();

            $data['localMaterial'] = RrIndLocalRawMaterial::where('app_id', $data['appInfo']->id)->get(['local_raw_material_name', 'local_raw_material_quantity', 'local_raw_material_unit', 'local_raw_material_amount_bdt'])->toArray();
            $data['importedMaterial'] = RrIndImportedRawMaterial::where('app_id', $data['appInfo']->id)->get(['imported_raw_material_name', 'imported_raw_material_quantity', 'imported_raw_material_unit', 'imported_raw_material_amount_bdt'])->toArray();

            $investors = InvestorList::where('process_type_id', $process_type_id)->where('app_id', $data['appInfo']->id)->get();

            //Set Session
            if($investors->isNotEmpty()){
                $this->setInvestorSession($investors);
            }
            $data['companyUserType'] = CommonFunction::getCompanyUserType();
            $public_html = strval(view("IndustryReRegistration::application-form-edit", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [VA-1011]']);
        }
    }

    public function appFormView($appId, Request $request){

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BRC-1003]';
        }

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->acl_name, '-V-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [BRC-974]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            $data['appInfo'] = ProcessList::leftJoin('rr_ind_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('registration_type', 'registration_type.id', '=', 'apps.regist_type')
                ->leftJoin('company_type', 'company_type.id', '=', 'apps.org_type')
                ->leftJoin('investment_type', 'investment_type.id', '=', 'apps.invest_type')
                ->leftJoin('industrial_category', 'industrial_category.id', '=', 'apps.ind_category_id')
                ->leftJoin('ind_sector_info', 'ind_sector_info.id', '=', 'apps.ins_sector_id')
                ->leftJoin('ind_sector_info as ind_sub_sector', 'ind_sub_sector.id', '=', 'apps.ins_sub_sector_id')
                ->leftJoin('area_info as area_info_division', 'area_info_division.area_id', '=', 'apps.office_division')
                ->leftJoin('area_info as area_info_district', 'area_info_district.area_id', '=', 'apps.office_district')
                ->leftJoin('area_info as area_info_thana', 'area_info_thana.area_id', '=', 'apps.office_thana')
                ->leftJoin('area_info as f_area_info_division', 'f_area_info_division.area_id', '=', 'apps.factory_division')
                ->leftJoin('area_info as f_area_info_district', 'f_area_info_district.area_id', '=', 'apps.factory_district')
                ->leftJoin('area_info as f_area_info_thana', 'f_area_info_thana.area_id', '=', 'apps.factory_thana')
                ->leftJoin('country_info as country_info_nationality', 'country_info_nationality.id', '=', 'apps.nationality')
                ->leftJoin('designation', 'designation.id', '=', 'apps.designation')
                ->leftJoin('industrial_city_list', 'industrial_city_list.id', '=', 'apps.bscic_office_id')
                ->leftJoin('currencies as currencies_land', 'currencies_land.id', '=', 'apps.local_land_ivst_ccy')
                ->leftJoin('currencies as currencies_building', 'currencies_building.id', '=', 'apps.local_building_ivst_ccy')
                ->leftJoin('currencies as currencies_machinery', 'currencies_machinery.id', '=', 'apps.local_machinery_ivst_ccy')
                ->leftJoin('currencies as currencies_others', 'currencies_others.id', '=', 'apps.local_others_ivst_ccy')
                ->leftJoin('currencies as currencies_wc', 'currencies_wc.id', '=', 'apps.local_wc_ivst_ccy')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('sp_payment as gfp', 'gfp.id', '=', 'apps.gf_payment_id')
                ->leftJoin('industrial_city_list as reg_office', 'reg_office.id', '=', 'apps.bscic_office_id')
                ->leftJoin('company_director_type as director_type', 'director_type.id', '=', 'apps.director_type')
                ->leftJoin('ind_business_category as business_cat', 'business_cat.id', '=', 'apps.business_category_id')
                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'ps.status_name',

                    'process_type.form_url',

                    'apps.*',

                    'registration_type.name_bn as regist_name_bn',
                    'company_type.name_bn as company_type_bn',
                    'investment_type.name_bn as investment_type_bn',
                    'industrial_category.name_bn as ind_category_bn',
                    'ind_sector_info.name_bn as ind_sector_bn',
                    'ind_sub_sector.name_bn as ind_sub_sector_bn',
                    'area_info_division.area_nm_ban as div_nm_ban',
                    'area_info_district.area_nm_ban as dis_nm_ban',
                    'area_info_thana.area_nm_ban as thana_nm_ban',
                    'f_area_info_division.area_nm_ban as f_div_nm_ban',
                    'f_area_info_district.area_nm_ban as f_dis_nm_ban',
                    'f_area_info_thana.area_nm_ban as f_thana_nm_ban',
                    'country_info_nationality.nationality as ceo_nationality',
//                    'ceo_area_div.area_nm_ban as ceo_area_div',
//                    'ceo_area_dis.area_nm_ban as ceo_area_dis',
//                    'ceo_area_thana.area_nm_ban as ceo_area_thana',
//                    'ent_designation.name_bn as ent_desg_bn',
                    'industrial_city_list.name as registration_office',
                    'currencies_land.code as currency_code_land',
                    'currencies_building.code as currency_code_building',
                    'currencies_machinery.code as currency_code_machinery',
                    'currencies_others.code as currency_others',
                    'currencies_wc.code as currencies_wc',
                    'reg_office.name as reg_office_name_bn',

                    'sfp.id as sfp_id',
                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_tax',
                    'sfp.transaction_charge_amount as sfp_bank_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',

                    'gfp.id as gfp_id',
                    'gfp.contact_name as gfp_contact_name',
                    'gfp.contact_email as gfp_contact_email',
                    'gfp.contact_no as gfp_contact_phone',
                    'gfp.address as gfp_contact_address',
                    'gfp.pay_amount as gfp_pay_amount',
                    'gfp.vat_on_pay_amount as gfp_vat_tax',
                    'gfp.transaction_charge_amount as gfp_bank_charge',
                    'gfp.payment_status as gfp_payment_status',
                    'gfp.pay_mode as gfp_pay_mode',
                    'gfp.pay_mode_code as gf_pay_mode_code',
                    'gfp.total_amount as gfp_total_amount',
                    'gfp.transaction_charge_amount as gfp_transaction_charge_amount',
                    'gfp.vat_on_transaction_charge as gfp_vat_on_transaction_charge',
                    'director_type.name_bn as director_type',
                    'business_cat.name_bn as business_category',
                ]);

            $data['investing_country'] = IndRegInvCountry::leftJoin('country_info', 'country_info.id', '=', 'rr_ind_inv_country.country_id')
                ->where('app_id', $decodedAppId)->get([
                    'rr_ind_inv_country.*',
                    'country_info.name as country_name'
                ]);


            $data['annualProductionCapacity'] = AnnualProductionCapacity::leftJoin('apc_units', 'apc_units.id', '=', 'rr_ind_annual_prod_capacity.unit')
                ->where('app_id', $decodedAppId)->get();
            $data['utilityService'] = UtilityService::leftJoin('public_utility_resource', 'public_utility_resource.id', '=', 'rr_ind_utility_services.utility_id')
                ->where('app_id', $decodedAppId)
                ->get([
                    'rr_ind_utility_services.*',
                    'public_utility_resource.name_bn'
                ]);
            $data['loanSrcCountry'] = LoanSourceCountry::leftJoin('country_info', 'country_info.id', '=', 'rr_ind_loan_source_country.loan_country_id')
                ->where('app_id', $decodedAppId)->get([
                    'rr_ind_loan_source_country.*',
                    'country_info.name as country_name'
                ]);

            $data['localMachinery'] = MachineryLocal::where('app_id', $decodedAppId)->get();
            $data['importedMachinery'] = MachineryImported::where('app_id', $decodedAppId)->get();

            $data['localRawMaterial'] = RrIndLocalRawMaterial::leftJoin('apc_units', 'apc_units.id', '=', 'rr_ind_local_raw_material.local_raw_material_unit')
                ->where('app_id', $decodedAppId)->get();
            $data['importedRawMaterial'] = RrIndImportedRawMaterial::leftJoin('apc_units', 'apc_units.id', '=', 'rr_ind_imported_raw_material.imported_raw_material_unit')
                ->where('app_id', $decodedAppId)->get();

            $data['investorInfo'] = InvestorList::leftJoin('country_info', 'country_info.id', '=', 'investor_list.nationality')
//                ->leftJoin('designation', 'designation.id', '=', 'investor_list.designation')
                ->where('app_id', $decodedAppId)
                ->where('process_type_id', $process_type_id)->get();

            // Checking the Government Fee Payment(GFP) configuration for this service

            if (!empty($data['appInfo']->sfp_id)) {
                $data['service_fee_distributions'] = PaymentDetails::leftJoin('sp_payment_distribution', 'sp_payment_distribution.id', '=', 'sp_payment_details.payment_distribution_id')
                    ->leftJoin('sp_payment_distribution_type', 'sp_payment_distribution_type.id', '=', 'sp_payment_distribution.distribution_type')
                    ->where('sp_payment_details.sp_payment_id', $data['appInfo']->sfp_id)
                    ->get([
                        'sp_payment_distribution.stakeholder_ac_name',
                        'sp_payment_details.receiver_ac_no as stakeholder_ac_no',
                        'sp_payment_details.pay_amount',
                        'sp_payment_distribution_type.name',
                    ]);
            }

            if (!empty($data['appInfo']->gfp_id)) {
                $data['government_fee_distributions'] = PaymentDetails::leftJoin('sp_payment_distribution', 'sp_payment_distribution.id', '=', 'sp_payment_details.payment_distribution_id')
                    ->leftJoin('sp_payment_distribution_type', 'sp_payment_distribution_type.id', '=', 'sp_payment_distribution.distribution_type')
                    ->where('sp_payment_details.sp_payment_id', $data['appInfo']->gfp_id)
                    ->get([
                        'sp_payment_distribution.stakeholder_ac_name',
                        'sp_payment_details.receiver_ac_no as stakeholder_ac_no',
                        'sp_payment_details.pay_amount',
                        'sp_payment_distribution_type.name',
                    ]);
            }

            if (in_array($data['appInfo']->status_id, [15])) {
                $data['payment_config'] = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                    'sp_payment_configuration.payment_category_id')
                    ->where([
                        'sp_payment_configuration.process_type_id' => $this->process_type_id,
                        'sp_payment_configuration.payment_category_id' => 2, //Government fee payment
                        'sp_payment_configuration.status' => 1,
                        'sp_payment_configuration.is_archive' => 0
                    ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
                $vat_percentage  = Configuration::where('caption','GOVT_VENDOR_VAT_FEE')->value('value');
                $industry_category = IndustrialCategory::where('status', 1)->where('id',$data['appInfo']->ind_category_id)->first();
                $data['payment_config']->amount = $industry_category->reg_fee;
                $data['payment_config']->vat_on_pay_amount = ($data['payment_config']->amount/100)* $vat_percentage;

                if (empty($data['payment_config'])) {
                    return response()->json([
                        'responseCode' => 1,
                        'html' => "<h4 class='custom-err-msg'>Payment Configuration not found ![BRC-10103]</h4>"
                    ]);
                }
            }

            $public_html = strval(view("IndustryReRegistration::application-view", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [VA-1012]']);
        }

    }

    public function getLocalMachinery(Request $request)
    {
        $app_id = Encryption::decodeId($request->app_id);

//        DB::statement(DB::raw('set @rownum=0'));
        $getData = MachineryLocal::where('app_id', $app_id)
            ->orderBy('id', 'DESC')
            ->get([
//                DB::raw('@rownum := @rownum+1 AS sl'),
                'id',
                'app_id',
                'machinery_nm',
                'machinery_qty',
                'machinery_price'
            ]);

        $localTotal = IndustryReRegistration::where('id', $app_id)->value('local_machinery_total');

        $data = ['responseCode' => 1, 'data' => $getData, 'localTotal' => $localTotal];
        return response()->json($data);
    }

    public function localMachineryAdd($appId){
        $data['app_id'] = $appId;

        return view('IndustryReRegistration::machinery.local-machinery-add-modal', $data);
    }

    public function localMachineryStoreFromAttachment(Request $request){
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [IN-10210]';
        }
//        dd($request->all());

        if($request->hasFile('file_upload')){
            return $this->uploadCsvFile($request);
        }else{
            return response()->json([
                'error' => true,
                'status' => "Please provide attachment file",
            ]);
        }


    }
    public function importedMachineryStoreFromAttachment(Request $request){
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [IN-10210]';
        }
//        dd($request->all());

        if($request->hasFile('file_upload')){
            return $this->uploadCsvFile($request);
        }else{
            return response()->json([
                'error' => true,
                'status' => "Please provide attachment file",
            ]);
        }


    }

    public function localMachineryStore(Request $request){
//        dd($request->all());
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [IN-10210]';
        }

//        if($request->hasFile('file_upload')){
//            $this->uploadCsvFile();
//        }

        $rules = [
            'local_machinery_nm' => 'required',
            'local_machinery_qty' => 'required',
            'local_machinery_price' => 'required'
        ];
        $messages = [
            'local_machinery_nm.required' => 'Machinery Name is Required.',
            'local_machinery_qty.required' => 'Machinery Quantity is Required.',
            'local_machinery_price.required' => 'Machinery Price is Required.'
        ];

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        try {

            $app_id = $request->app_id ? Encryption::decodeId($request->app_id) : '';
            $machine_id = $request->machine_id ? Encryption::decodeId($request->machine_id) : '';

            $localMachine = MachineryLocal::findOrNew($machine_id);
            $localMachine->app_id = $app_id;
            $localMachine->machinery_nm = $request->local_machinery_nm;
            $localMachine->machinery_qty = $request->local_machinery_qty;
            $localMachine->machinery_price = $request->local_machinery_price;

            $localMachine->save();

            $total_local_machine_price = MachineryLocal::where('app_id', $app_id)->sum('machinery_price');

            IndustryReRegistration::where('id', $app_id)->update(['local_machinery_total' => $total_local_machine_price]);

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully',
                'link' => ''
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()).' [ASDC-10021]'
            ]);
        }

    }

    public function localMachineryEdit($machine_id){

        $data['localMachine'] = MachineryLocal::where('id', CommonFunction::vulnerabilityCheck($machine_id))->first();

        return view('IndustryReRegistration::machinery.local-machinery-edit-modal', $data);
    }

    public function localMachineryDelete($id, $app_id)
    {
        MachineryLocal::where('id', CommonFunction::vulnerabilityCheck($id))->delete();

        $total_local_machine_price = MachineryLocal::where('app_id', CommonFunction::vulnerabilityCheck($app_id))->sum('machinery_price');

        IndustryReRegistration::where('id', CommonFunction::vulnerabilityCheck($app_id))->update(['local_machinery_total' => $total_local_machine_price]);

        return response()->json([
            'responseCode' => 1,
            'msg' => 'Machinery Removed!'
        ]);
    }

    public function getImportedMachinery(Request $request)
    {
        $app_id = Encryption::decodeId($request->app_id);

//        DB::statement(DB::raw('set @rownum=0'));
        $getData = MachineryImported::where('app_id', $app_id)
            ->orderBy('id', 'DESC')
            ->get([
//                DB::raw('@rownum := @rownum+1 AS sl'),
                'id',
                'app_id',
                'machinery_nm',
                'machinery_qty',
                'machinery_price'
            ]);

        $importedTotal = IndustryReRegistration::where('id', $app_id)->value('imported_machinery_total');

        $data = ['responseCode' => 1, 'data' => $getData, 'importedTotal' => $importedTotal];
        return response()->json($data);
    }

    public function importedMachineryAdd($appId){
        $data['app_id'] = $appId;

        return view('IndustryReRegistration::machinery.imported-machinery-add-modal', $data);
    }

    public function importedMachineryStore(Request $request){
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [IN-10210]';
        }

//        if($request->hasFile('file_upload')){
//            $this->uploadCsvFile();
//        }

        $rules = [
            'imported_machinery_nm' => 'required',
            'imported_machinery_qty' => 'required',
            'imported_machinery_price' => 'required'
        ];
        $messages = [
            'imported_machinery_nm.required' => 'Machinery Name is Required.',
            'imported_machinery_qty.required' => 'Machinery Quantity is Required.',
            'imported_machinery_price.required' => 'Machinery Price is Required.'
        ];

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        try {

            $app_id = $request->app_id ? Encryption::decodeId($request->app_id) : '';
            $machine_id = $request->machine_id ? Encryption::decodeId($request->machine_id) : '';

            $importedMachine = MachineryImported::findOrNew($machine_id);
            $importedMachine->app_id = $app_id;
            $importedMachine->machinery_nm = $request->imported_machinery_nm;
            $importedMachine->machinery_qty = $request->imported_machinery_qty;
            $importedMachine->machinery_price = $request->imported_machinery_price;

            $importedMachine->save();

            $total_imported_machine_price = MachineryImported::where('app_id', $app_id)->sum('machinery_price');

            IndustryReRegistration::where('id', $app_id)->update(['imported_machinery_total' => $total_imported_machine_price]);

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully',
                'link' => ''
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()).' [ASDC-10021]'
            ]);
        }

    }

    public function importedMachineryEdit($machine_id){

        $data['importedMachine'] = MachineryImported::where('id', CommonFunction::vulnerabilityCheck($machine_id))->first();

        return view('IndustryReRegistration::machinery.imported-machinery-edit-modal', $data);
    }

    public function importedMachineryDelete($id, $app_id)
    {
        MachineryImported::where('id', CommonFunction::vulnerabilityCheck($id))->delete();

        $total_imported_machine_price = MachineryImported::where('app_id', CommonFunction::vulnerabilityCheck($app_id))->sum('machinery_price');

        IndustryReRegistration::where('id', CommonFunction::vulnerabilityCheck($app_id))->update(['imported_machinery_total' => $total_imported_machine_price]);

        return response()->json([
            'responseCode' => 1,
            'msg' => 'Machinery Removed!'
        ]);
    }

    public function uploadCsvFile($request)
    {

        try {
            $data = $request->all();
            $app_id = Encryption::decodeId($data['app_id']);
            $file = $data['file_upload'];
            $type = $data['type'];
            $file_mime = $file->getMimeType();
            $mimes = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.oasis.opendocument.spreadsheet',
                'application/vnd.ms-excel',
                'text/plain',
                'text/csv',
                'text/tsv'
            );
            if (in_array($file_mime, $mimes)) {

                $rand = rand(111, 999);
                $onlyFileName = 'IN_Raw_' . date("Ymd_") . $rand . time();
                $savedPath = 'uploads/client/csv-upload/'; // upload path
                $extension = $file->getClientOriginalExtension(); // getting extension
                $fileName = $onlyFileName . '.' . $extension; // renaming
                $path = public_path($savedPath);
                $file->move($path, $fileName);
                $uploadingLog = new CsvUploadLog();
                $uploadingLog->file_name = $onlyFileName;
                $uploadingLog->file_path = $savedPath . $fileName;
                $uploadingLog->save();

                $excelData = Excel::load($savedPath . $fileName)->get();

                if (empty($excelData)) {
//                    Session::flash('error', 'Your file is empty, please upload a valid file');
                    return response()->json([
                        'error' => true,
                        'status' => 'Your file is empty, please upload a valid file'
                    ]);
                }

                $firstrow = ($excelData->first() != null) ? $excelData->first()->toArray() : $excelData->first();
                if (count($firstrow) == 0) { // Condition for blank data sheet checking
                    return response()->json([
                        'error' => true,
                        'status' => 'This is not a valid data sheet at least the first row of sheet will not be empty.'
                    ]);
                }



                $tableFields = [
                    0 => 'name',
                    1 => 'quantity',
                    2 => 'price'
                ];
                $existFields = [];
                foreach ($firstrow as $csvColumnName => $csvColumnValue) {
                    $existFields[] = $csvColumnName;
                }

                if (array_diff($existFields, $tableFields)) {
                    return response()->json([
                        'error' => true,
                        'status' => 'Column mismatched. Please follow the given sample.'
                    ]);
                }

                $excelData = $excelData->toArray();
                if($type == 'local'){
                    foreach ($excelData as $item){
                        $Machinery = new MachineryLocal();
                        $Machinery->app_id = $app_id;
                        $Machinery->machinery_nm = $item['name'];
                        $Machinery->machinery_qty = $item['quantity'];
                        $Machinery->machinery_price = $item['price'];
                        $Machinery->save();

                    }
                    $total_imported_machine_price = MachineryLocal::where('app_id', $app_id)->sum('machinery_price');
                    IndustryReRegistration::where('id', $app_id)->update(['local_machinery_total' => $total_imported_machine_price]);
                }else{
                    foreach ($excelData as $item){
                        $Machinery = new MachineryImported();
                        $Machinery->app_id = $app_id;
                        $Machinery->machinery_nm = $item['name'];
                        $Machinery->machinery_qty = $item['quantity'];
                        $Machinery->machinery_price = $item['price'];
                        $Machinery->save();
                    }
                    $total_imported_machine_price = MachineryImported::where('app_id', $app_id)->sum('machinery_price');
                    IndustryReRegistration::where('id', $app_id)->update(['imported_machinery_total' => $total_imported_machine_price]);
                }

//                return true;
                return response()->json([
                    'success' => true,
                    'status' => ' Your data saved successfully'
                ]);

            } else {
                return response()->json([
                    'error' => true,
                    'status' => 'csv or xls or xlsx file supported only!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()).' [IRCNCSV-100]'
            ]);
        }
    }

    private function setInvestorSession($investors){

        if(Session::has('directorsInfo')){
            Session::forget('directorsInfo');
        }

        $directorInfo = [];

        foreach ($investors as $investor){

            $directorInfo['nationality_type'] = $investor->identity_type;
            $directorInfo['identity_type'] = $investor->identity_type;
            $directorInfo['l_director_name'] = $investor->investor_nm;
            $directorInfo['date_of_birth'] = date('Y-m-d', strtotime($investor->date_of_birth));
            $directorInfo['nid_etin_passport'] = $investor->identity_no;
            $directorInfo['l_director_designation'] = $investor->designation;
            $directorInfo['designation'] = $investor->designation;
//            $directorInfo['designation'] = Designation::where('id', $investor->designation)->value('name_bn');
            $directorInfo['l_director_nationality'] = $investor->nationality;
            $directorInfo['nationality'] = Countries::where('id', $investor->nationality)->value('nationality');
            $directorInfo['gender'] = $investor->gender;

            Session::push('directorsInfo', $directorInfo);
        }

    }

    public function unfixedAmountsForVendorServiceFee($ind_category_id, $payment_step_id)
    {
        $vat_percentage  = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');
        if (empty($vat_percentage)) {
            DB::rollback();
            Session::flash('error', 'Please, configure the value for VAT.[INR-1026]');
            return redirect()->back()->withInput();
        }

        $govt_service_fees = $this->calculateVendorServiceFee($ind_category_id);

        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => $govt_service_fees, // Govt-Service-Fee
            3 => 0, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => ($govt_service_fees / 100) * $vat_percentage, // Govt-Vat-Fee
            6 => 0 //govt-vendor-vat-fee
        ];

        return $unfixed_amount_array;
    }


    public function calculateVendorServiceFee($id)
    {
        $data = IndustrialCategory::where('id', $id)->first();


        return $data->oss_fee_re_registration;
    }

    public function calculateGovtApplicationFee($id)
    {
        $data = IndustrialCategory::where('id', $id)->first();


        return $data->reg_fee;
    }

    public function Payment(Request $request)
    {
        try {
            DB::beginTransaction();
            $appId = Encryption::decodeId($request->get('app_id'));

            $appData = IndustryReRegistration::find($appId);
            $contactInfo = ['contact_no'=>!empty($request->get('gfp_contact_phone'))?$request->gfp_contact_phone:'','address'=>!empty($request->get('gfp_contact_address'))?$request->gfp_contact_address:''];
            $calculatedAmountArray = $this->unfixedAmountsForGovtApplicationFee($appData->ind_category_id, 2);

            $payment_id = $this->storeSubmissionFeeData($appData, 2,$contactInfo, $calculatedAmountArray['unfixed_amount_array'], $calculatedAmountArray);

            DB::commit();
            if ($request->get('actionBtn') == 'Submit' && $payment_id) {
                return redirect('spg/initiate-multiple/' . Encryption::encodeId($payment_id));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[IRN-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function unfixedAmountsForGovtApplicationFee($ind_category_id, $payment_step_id)
    {
        $govt_application_fees = $this->calculateGovtApplicationFee($ind_category_id);
        $vat_percentage  = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');

        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => 0, // Govt-Service-Fee
            3 => $govt_application_fees, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => ($govt_application_fees / 100) * $vat_percentage, // Govt-Vat-Fee
            6 => 0
        ];

        return $unfixed_amount_array;
    }

    public function afterPayment($payment_id)
    {
        $this->paymentCallBackDataStore($payment_id);
    }

    public function afterCounterPayment($payment_id)
    {
        $this->counterPaymentCallBackDataStore($payment_id);
    }


    public function getIndustryByInvestment(Request $request){

        $total_investment = $request->get('total_investment');

        $industry_category = IndustrialCategory::where('status', 1)->get();
        $oss_fee = 0;
        $DataArray=[];
        foreach($industry_category as $industry_category){
            $DataArray[$industry_category->id]=[
                'industry_category_id'=>$industry_category->id,
                'oss_fee_re_registration'=>$industry_category->oss_fee_re_registration
            ];
            if($total_investment >= $industry_category->inv_limit_start
                && $total_investment <= $industry_category->inv_limit_end){

                $industry_category_id = $industry_category->id;
                $oss_fee = $industry_category->oss_fee_re_registration;

                break;
            }
        }

        if ($total_investment >= '500000001') {
            $industry_category_id =$DataArray[5]['industry_category_id'];
            $oss_fee = $DataArray[5]['oss_fee_re_registration'];
        }
        $data = ['responseCode' => 1, 'data' => $industry_category_id, 'oss_fee' => $oss_fee];
        return response()->json($data);
    }

//    public function pdf(){
//        return view('IndustryReRegistration::pdf-design');
//    }

    public function ajaxAppStore(Request $request){

//        dd($request->all());
        try{
            $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
            $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
//            dd($request->get('app_id'));
            if (!ACL::getAccsessRight($this->acl_name, $mode, $app_id))
                abort('400', "You have no access right! Please contact with system admin if you have any query.");

            // check Approved IN application, if have go back
            $company_id = CommonFunction::getUserCompanyWithZero();

            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = IndustryReRegistration::find($app_id);
            } else {
                $appData = new IndustryReRegistration();
            }

            //            $appData->company_id = $company_id;
            if($request->steps == 'step_one'){
                $this->stepOne($appData, $request);
            }

            if($request->steps == 'step_two'){
                $this->stepTwo($appData, $request);
            }

            if($request->steps == 'step_three'){
                $this->stepThree($appData, $request);
            }

            //            if($request->steps == 'step_four'){
            //
            //            }




            $cat_id = 1;
            $officeid = $request->get('pref_reg_office');
//            dd($request->get('pref_reg_office'));
            $processData = CommonComponent()->processDataInsertAndUpdate($this->process_type_id,$request,$appData->id,$company_id,$cat_id,$officeid);


            // Payment info will not be updated for resubmit
            if ($processData->status_id != 2) {
                $contactInfo = [
                    'contact_name' => CommonFunction::getUserFullName(),
                    'contact_email' => Auth::user()->user_email,
                    'contact_no' =>  Auth::user()->user_mobile,
                    'contact_address' => Auth::user()->contact_address,
                ];

                $unfixed_amount_array = $this->unfixedAmountsForVendorServiceFee(1, 1);
                $payment_id = $this->storeSubmissionFeeData($appData->id, 1, $contactInfo, $unfixed_amount_array);

            }

//            $this->updateCompanyProfile($request, $appData, $processData);

            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1) {
                if (empty($processData->tracking_no)) {
                    $dist_name = CommonFunction::getDistrictFirstTwoChar($appData->factory_district);
                    $officeShortCode = CommonFunction::getOfficeShortCode($appData->bscic_office_id);
                    $trackingPrefix = 'RR-' . date("Ymd") . '-'.$officeShortCode.'-';
                    CommonComponent()->trackingNoWithShortCode($this->process_type_id,$trackingPrefix,$processData->id);
                }
                DB::commit();

                $redirectUrl = '/spg/initiate-multiple/' . Encryption::encodeId($payment_id);
                return response()->json(['responseCode' => 1, 'status' => true,  'redirectUrl' => $redirectUrl]);
                //                return redirect('spg/initiate-multiple/' . Encryption::encodeId($payment_id));
            }

            DB::commit();
            return response()->json(['responseCode' => 1, 'status' => true, 'encrypted_ids'=>Encryption::encodeId($appData->id).'/'.Encryption::encodeId($this->process_type_id),
                'app_id' => Encryption::encodeId($appData->id)]);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'status' => false,  'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [VA-1013]']);
        }


    }


    public function stepOne($appData, $request){
        $appData->org_nm = "ok";
        $appData->org_nm_bn = $request->get('company_name_bangla');
        $appData->business_category_id = $request->get('business_category_id');


        $appData->service_name = $request->get('service_name_manual');
        $appData->manual_reg_number = $request->get('manual_reg_number');
        $appData->manual_reg_date =!empty($request->get('manual_reg_date'))? date('Y-m-d', strtotime($request->get('manual_reg_date'))):'';
        $appData->total_investment = $request->get('total_investment');


        $appData->director_type = $request->get('select_directors');
        $appData->ceo_name = $request->get('company_ceo_name');
        $appData->ceo_father_nm = $request->get('company_ceo_fatherName');
        $appData->nationality = $request->get('company_ceo_nationality');
        $appData->nid = $request->get('company_ceo_nid');
        $appData->passport = $request->get('company_ceo_passport');

        if($request->company_ceo_passport !=''){
            $appData->passport = $request->get('company_ceo_passport');
            $appData->nid = null;
        }
        if($request->company_ceo_nid !=''){
            $appData->nid = $request->get('company_ceo_nid');
            $appData->passport = null;
        }

        $appData->dob =  date('Y-m-d', strtotime($request->get('company_ceo_dob')));
        $appData->designation = $request->get('company_ceo_designation_id');
        $appData->ceo_email = $request->get('company_ceo_email');
        $appData->ceo_mobile = $request->get('company_ceo_mobile');


        if (!empty($request->correspondent_signature_base64)) {
            $yearMonth = date("Y") . "/" . date("m") . "/";
            $path = "uploads/client/signature/" . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $splited = explode(',', substr($request->get('correspondent_signature_base64'), 5), 2);
            $imageData = $splited[1];
            $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 80));
            $base64ResizeImage = base64_decode($base64ResizeImage);
            $correspondent_signature_name = trim(uniqid('BSCIC_CP-' . '-', true) . '.' . 'jpeg');
            file_put_contents($path . $correspondent_signature_name, $base64ResizeImage);
            $appData->entrepreneur_signature = $path . $correspondent_signature_name;
        }
        else{
            $signature_url = CompanyInfo::where('id', Auth::user()->working_company_id)->value('entrepreneur_signature');
            if(!empty($signature_url) && $signature_url!=""){
                $appData->entrepreneur_signature =$signature_url;
            }
        }


        $appData->bscic_office_id = $request->get('pref_reg_office');
        $appData->save();

        if (!empty($appData->id) && !empty($request->loan_country_id[0])) {
            $loan_source_ids = [];
            foreach ($request->loan_country_id as $item => $value) {
                $loan_source_id = $request->get('loan_source_id') != null ? $request->get('loan_source_id')[$item] : '';
                $loanSource = LoanSourceCountry::findOrNew($loan_source_id);
                $loanSource->app_id = $appData->id;
                $loanSource->loan_country_id = $request->loan_country_id[$item];
                $loanSource->loan_org_nm = $request->loan_org_name[$item];
                $loanSource->loan_amount = $request->loan_amount[$item];
                $loanSource->loan_receive_date = !empty($request->loan_receive_date[$item]) ? date('Y-m-d', strtotime($request->loan_receive_date[$item])) : '';
                $loanSource->save();

                $loan_source_ids[] = $loanSource->id;
            }

            if (count($loan_source_ids) > 0) {
                LoanSourceCountry::where('app_id', $appData->id)->whereNotIn('id', $loan_source_ids)->delete();
            }
        }

//        $loadSourceCountry = ['loan_country_id','loan_org_nm', 'loan_amount','loan_receive_date'];
//
//        CommonComponent()->storeChildRow($request->all(), $loadSourceCountry, $appData->id, 'app_id', 'rr_ind_loan_source_country');
    }

    public function stepTwo($appData, $request){

        $localRawMaterialFields = ['local_raw_material_name', 'local_raw_material_quantity', 'local_raw_material_unit', 'local_raw_material_amount_bdt'];
        CommonComponent()->storeChildRow($request->all(), $localRawMaterialFields, $appData->id, 'app_id', 'rr_ind_local_raw_material');

        $importedRawMaterialFields = ['imported_raw_material_name', 'imported_raw_material_quantity', 'imported_raw_material_unit', 'imported_raw_material_amount_bdt'];
        CommonComponent()->storeChildRow($request->all(), $importedRawMaterialFields, $appData->id, 'app_id', 'rr_ind_imported_raw_material');
        $appData->local_raw_price_total = $request->get('local_raw_price_total');
        $appData->imported_raw_price_total = $request->get('imported_raw_price_total');
        $appData->save();

    }

    public function stepThree($appData, $request){

        $appData->auth_person_nm = $request->get('auth_person_nm');
        $appData->auth_person_desig = $request->get('auth_person_desig');
        $appData->auth_person_address = $request->get('auth_person_address');
        $appData->auth_person_mobile = $request->get('mobile_no');
        $appData->auth_person_email = $request->get('auth_person_email');
        $appData->authorization_letter = $request->validate_field_trade_license;

        if (!empty($request->correspondent_photo_base64)) {
            $yearMonth = date("Y") . "/" . date("m") . "/";
            $path = "uploads/client/image/" . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $splited = explode(',', substr($request->get('correspondent_photo_base64'), 5), 2);
            $imageData = $splited[1];
            $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
            $base64ResizeImage = base64_decode($base64ResizeImage);
            $correspondent_photo_name = trim(uniqid('BSCIC_IR-' . '-', true) . '.' . 'jpeg');
            file_put_contents($path . $correspondent_photo_name, $base64ResizeImage);
            $appData->auth_person_pic = $path . $correspondent_photo_name;
        }else{
            if(empty($appData->auth_person_pic) ){
                $appData->auth_person_pic = Auth::user()->user_pic;
            }
        }

        $appData->accept_terms = (!empty($request->get('accept_terms')) ? 1 : 0);
        $appData->save();

        $process_type_id = $this->process_type_id;
        $doc_type_id = $request->doc_type_key ?? "";
        DocumentsController::storeAppDocuments($process_type_id, $doc_type_id, $appData->id, $request);
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("IndustryReRegistration::welcome");
    }
}
