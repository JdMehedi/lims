<?php

namespace App\Modules\ProcessPath\Services;

use App\Models\IndRRCommonPool;
use App\Modules\IndustryReRegistration\Models\IndustryReRegistration;
use App\Modules\IndustryNew\Models\IndustryNew;
use App\Modules\IndustryCancellation\Models\IndustryCancellation;
use Illuminate\Support\Facades\DB;

class IndustryCommonPoolManager
{
    public static function indrrDataStore($tracking_no, $ref_id,$applicant_id)
    {

        try {

            $indrrData = IndustryReRegistration::where('id', $ref_id)->first();

            $appData =  IndRRCommonPool::firstOrNew(['tracking_no'=>$tracking_no]);

            $appData->tracking_no = $tracking_no;

            $appData->company_id = $indrrData->company_id;
            $appData->regist_no = $indrrData->regist_no;
            $appData->org_nm = $indrrData->org_nm;
            $appData->org_nm_bn = $indrrData->org_nm_bn;
            $appData->project_nm = $indrrData->project_nm;
            $appData->regist_type = $indrrData->regist_type;
            $appData->org_type = $indrrData->org_type;
            $appData->invest_type = $indrrData->invest_type;
            $appData->investment_limit = $indrrData->investment_limit;
            $appData->ind_category_id = $indrrData->ind_category_id;
            $appData->ins_sector_id = $indrrData->ins_sector_id;
            $appData->ins_sub_sector_id = $indrrData->ins_sub_sector_id;
            $appData->office_division = $indrrData->office_division;
            $appData->office_district = $indrrData->office_district;
            $appData->office_thana = $indrrData->office_thana;
            $appData->office_postcode = $indrrData->office_postcode;
            $appData->office_location = $indrrData->office_location;
            $appData->office_email = $indrrData->office_email;
            $appData->office_mobile = $indrrData->office_mobile;
            $appData->is_same_address = $indrrData->is_same_address;
            $appData->factory_division = $indrrData->factory_division;
            $appData->factory_district = $indrrData->factory_district;
            $appData->factory_thana = $indrrData->factory_thana;
            $appData->factory_postcode = $indrrData->factory_postcode;
            $appData->factory_location = $indrrData->factory_location;
            $appData->factory_email = $indrrData->factory_email;
            $appData->factory_mobile = $indrrData->factory_mobile;

            $appData->director_type = $indrrData->director_type;
            $appData->ceo_name = $indrrData->ceo_name;
            $appData->ceo_father_nm = $indrrData->ceo_father_nm;
            $appData->nationality = $indrrData->nationality;
            $appData->nid = $indrrData->nid;
            $appData->passport = $indrrData->passport;



            $appData->dob =  $indrrData->dob;
            $appData->designation = $indrrData->designation;
            $appData->ceo_email = $indrrData->ceo_email;
            $appData->ceo_mobile = $indrrData->ceo_mobile;
            $appData->entrepreneur_signature = $indrrData->entrepreneur_signature;
            $appData->bscic_office_id = $indrrData->bscic_office_id;
            $appData->main_activities = $indrrData->main_activities;
            $appData->commercial_operation_dt = $indrrData->commercial_operation_dt;
            $appData->project_deadline = $indrrData->project_deadline;

            $appData->sales_local = $indrrData->sales_local;
            $appData->sales_foreign = $indrrData->sales_foreign;
            $appData->apc_price_total = $indrrData->apc_price_total;

            $appData->local_male = $indrrData->local_male;
            $appData->local_female = $indrrData->local_female;
            $appData->local_total = $indrrData->local_total;
            $appData->foreign_male = $indrrData->foreign_male;
            $appData->foreign_female = $indrrData->foreign_female;
            $appData->foreign_total = $indrrData->foreign_total;
            $appData->manpower_total = $indrrData->manpower_total;
            $appData->manpower_local_ratio = $indrrData->manpower_local_ratio;
            $appData->manpower_foreign_ratio = $indrrData->manpower_foreign_ratio;

            $appData->local_land_ivst = $indrrData->local_land_ivst;
            $appData->local_land_ivst_ccy = $indrrData->local_land_ivst_ccy;
            $appData->local_machinery_ivst = $indrrData->local_machinery_ivst;
            $appData->local_machinery_ivst_ccy = $indrrData->local_machinery_ivst_ccy;
            $appData->local_building_ivst = $indrrData->local_building_ivst;
            $appData->local_building_ivst_ccy = $indrrData->local_building_ivst_ccy;
            $appData->local_others_ivst = $indrrData->local_others_ivst;
            $appData->local_others_ivst_ccy = $indrrData->local_others_ivst_ccy;
            $appData->local_wc_ivst = $indrrData->local_wc_ivst;
            $appData->local_wc_ivst_ccy = $indrrData->local_wc_ivst_ccy;
            $appData->total_fixed_ivst = $indrrData->total_fixed_ivst;
            $appData->total_fixed_ivst_million = $indrrData->total_fixed_ivst_million;
            $appData->usd_exchange_rate = $indrrData->usd_exchange_rate;
            $appData->total_invt_dollar = $indrrData->total_invt_dollar;
            $appData->total_fee = $indrrData->total_fee;

            $appData->ceo_taka_invest = $indrrData->ceo_taka_invest;
            $appData->ceo_dollar_invest = $indrrData->ceo_dollar_invest;
            $appData->ceo_loan_org_country = $indrrData->ceo_loan_org_country;
            $appData->local_loan_taka = $indrrData->local_loan_taka;
            $appData->local_loan_dollar = $indrrData->local_loan_dollar;
            $appData->local_loan_org_country = $indrrData->local_loan_org_country;
            $appData->foreign_loan_taka = $indrrData->foreign_loan_taka;
            $appData->foreign_loan_dollar = $indrrData->foreign_loan_dollar;
            $appData->foreign_loan_org_country = $indrrData->foreign_loan_org_country;
            $appData->total_inv_taka = $indrrData->total_inv_taka;
            $appData->total_inv_dollar = $indrrData->total_inv_dollar;

            $appData->local_machinery_total = $indrrData->local_machinery_total;
            $appData->imported_machinery_total = $indrrData->imported_machinery_total;

            $appData->raw_local_number = $indrrData->raw_local_number;
            $appData->raw_local_price = $indrrData->raw_local_price;
            $appData->raw_imported_number = $indrrData->raw_imported_number;
            $appData->raw_imported_price = $indrrData->raw_imported_price;
            $appData->raw_total_number = $indrrData->raw_total_number;
            $appData->raw_total_price = $indrrData->raw_total_price;

            $appData->auth_person_nm = $indrrData->auth_person_nm;
            $appData->auth_person_desig = $indrrData->auth_person_desig;
            $appData->auth_person_address = $indrrData->auth_person_address;
            $appData->auth_person_mobile = $indrrData->auth_person_mobile;
            $appData->auth_person_email = $indrrData->auth_person_email;

            $appData->authorization_letter =$indrrData->authorization_letter;
            $appData->auth_person_pic = $indrrData->auth_person_pic;

            $appData->accept_terms =  $indrrData->accept_terms;
            $appData->applicant_id =  $applicant_id;

            $appData->save();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }


public static function indnewDataStore($tracking_no, $ref_id,$applicant_id)
    {

        try {

            $indrrData = IndustryNew::where('id', $ref_id)->first();

            $appData =  IndRRCommonPool::firstOrNew(['tracking_no'=>$tracking_no]);

            $appData->tracking_no = $tracking_no;

            $appData->company_id = $indrrData->company_id;

            $appData->org_nm = $indrrData->org_nm;
            $appData->org_nm_bn = $indrrData->org_nm_bn;
            $appData->regist_no = $indrrData->regist_no;
            $appData->project_nm = $indrrData->project_nm;
            $appData->regist_type = $indrrData->regist_type;
            $appData->org_type = $indrrData->org_type;
            $appData->invest_type = $indrrData->invest_type;
            $appData->investment_limit = $indrrData->investment_limit;
            $appData->ind_category_id = $indrrData->ind_category_id;
            $appData->ins_sector_id = $indrrData->ins_sector_id;
            $appData->ins_sub_sector_id = $indrrData->ins_sub_sector_id;
            $appData->office_division = $indrrData->office_division;
            $appData->office_district = $indrrData->office_district;
            $appData->office_thana = $indrrData->office_thana;
            $appData->office_postcode = $indrrData->office_postcode;
            $appData->office_location = $indrrData->office_location;
            $appData->office_email = $indrrData->office_email;
            $appData->office_mobile = $indrrData->office_mobile;
            $appData->is_same_address = $indrrData->is_same_address;
            $appData->factory_division = $indrrData->factory_division;
            $appData->factory_district = $indrrData->factory_district;
            $appData->factory_thana = $indrrData->factory_thana;
            $appData->factory_postcode = $indrrData->factory_postcode;
            $appData->factory_location = $indrrData->factory_location;
            $appData->factory_email = $indrrData->factory_email;
            $appData->factory_mobile = $indrrData->factory_mobile;

            $appData->director_type = $indrrData->director_type;
            $appData->ceo_name = $indrrData->ceo_name;
            $appData->ceo_father_nm = $indrrData->ceo_father_nm;
            $appData->nationality = $indrrData->nationality;
            $appData->nid = $indrrData->nid;
            $appData->passport = $indrrData->passport;



            $appData->dob =  $indrrData->dob;
            $appData->designation = $indrrData->designation;
            $appData->ceo_email = $indrrData->ceo_email;
            $appData->ceo_mobile = $indrrData->ceo_mobile;
            $appData->entrepreneur_signature = $indrrData->entrepreneur_signature;
            $appData->bscic_office_id = $indrrData->bscic_office_id;
            $appData->main_activities = $indrrData->main_activities;
            $appData->commercial_operation_dt = $indrrData->commercial_operation_dt;
            $appData->project_deadline = $indrrData->project_deadline;

            $appData->sales_local = $indrrData->sales_local;
            $appData->sales_foreign = $indrrData->sales_foreign;
            $appData->apc_price_total = $indrrData->apc_price_total;

            $appData->local_male = $indrrData->local_male;
            $appData->local_female = $indrrData->local_female;
            $appData->local_total = $indrrData->local_total;
            $appData->foreign_male = $indrrData->foreign_male;
            $appData->foreign_female = $indrrData->foreign_female;
            $appData->foreign_total = $indrrData->foreign_total;
            $appData->manpower_total = $indrrData->manpower_total;
            $appData->manpower_local_ratio = $indrrData->manpower_local_ratio;
            $appData->manpower_foreign_ratio = $indrrData->manpower_foreign_ratio;

            $appData->local_land_ivst = $indrrData->local_land_ivst;
            $appData->local_land_ivst_ccy = $indrrData->local_land_ivst_ccy;
            $appData->local_machinery_ivst = $indrrData->local_machinery_ivst;
            $appData->local_machinery_ivst_ccy = $indrrData->local_machinery_ivst_ccy;
            $appData->local_building_ivst = $indrrData->local_building_ivst;
            $appData->local_building_ivst_ccy = $indrrData->local_building_ivst_ccy;
            $appData->local_others_ivst = $indrrData->local_others_ivst;
            $appData->local_others_ivst_ccy = $indrrData->local_others_ivst_ccy;
            $appData->local_wc_ivst = $indrrData->local_wc_ivst;
            $appData->local_wc_ivst_ccy = $indrrData->local_wc_ivst_ccy;
            $appData->total_fixed_ivst = $indrrData->total_fixed_ivst;
            $appData->total_fixed_ivst_million = $indrrData->total_fixed_ivst_million;
            $appData->usd_exchange_rate = $indrrData->usd_exchange_rate;
            $appData->total_invt_dollar = $indrrData->total_invt_dollar;
            $appData->total_fee = $indrrData->total_fee;

            $appData->ceo_taka_invest = $indrrData->ceo_taka_invest;
            $appData->ceo_dollar_invest = $indrrData->ceo_dollar_invest;
            $appData->ceo_loan_org_country = $indrrData->ceo_loan_org_country;
            $appData->local_loan_taka = $indrrData->local_loan_taka;
            $appData->local_loan_dollar = $indrrData->local_loan_dollar;
            $appData->local_loan_org_country = $indrrData->local_loan_org_country;
            $appData->foreign_loan_taka = $indrrData->foreign_loan_taka;
            $appData->foreign_loan_dollar = $indrrData->foreign_loan_dollar;
            $appData->foreign_loan_org_country = $indrrData->foreign_loan_org_country;
            $appData->total_inv_taka = $indrrData->total_inv_taka;
            $appData->total_inv_dollar = $indrrData->total_inv_dollar;

            $appData->local_machinery_total = $indrrData->local_machinery_total;
            $appData->imported_machinery_total = $indrrData->imported_machinery_total;

            $appData->raw_local_number = $indrrData->raw_local_number;
            $appData->raw_local_price = $indrrData->raw_local_price;
            $appData->raw_imported_number = $indrrData->raw_imported_number;
            $appData->raw_imported_price = $indrrData->raw_imported_price;
            $appData->raw_total_number = $indrrData->raw_total_number;
            $appData->raw_total_price = $indrrData->raw_total_price;

            $appData->auth_person_nm = $indrrData->auth_person_nm;
            $appData->auth_person_desig = $indrrData->auth_person_desig;
            $appData->auth_person_address = $indrrData->auth_person_address;
            $appData->auth_person_mobile = $indrrData->auth_person_mobile;
            $appData->auth_person_email = $indrrData->auth_person_email;

            $appData->authorization_letter =$indrrData->authorization_letter;
            $appData->auth_person_pic = $indrrData->auth_person_pic;

            $appData->accept_terms =  $indrrData->accept_terms;
            $appData->applicant_id =  $indrrData->updated_by;
             $appData->applicant_id =  $applicant_id;

            $appData->save();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }


    public static function indcanDataStore($tracking_no, $ref_id,$applicant_id){
        try{
             DB::beginTransaction();
            $indCanData = IndustryCancellation::where('id', $ref_id)->first();
         $appData =  IndRRCommonPool::where('tracking_no',$indCanData->ref_tracking_no)->first();

            $appData->ind_can_tracking_no = $tracking_no;
             $appData->save();

             $indCanData->common_pool_id = $appData->id;
             $indCanData->save();

            DB::commit();
            return true;
        }catch (\Exception $e) {
            DB::rollback();
            return false;
        }

    }


}
