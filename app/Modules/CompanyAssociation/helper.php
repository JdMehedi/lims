<?php

use App\Modules\CompanyProfile\Models\CompanyType;
use App\Modules\CompanyProfile\Models\RegistrationType;
use App\Modules\CompanyAssociation\Models\CompanyAssociationMaster;
use App\Modules\Settings\Models\Area;
use Illuminate\Support\Facades\Auth;



function checkCompanyAssociation(){
    $check_association = CompanyAssociationMaster::leftJoin('users', 'users.id', '=', 'company_association_master.request_from_user_id')
        ->where('company_association_master.request_to_user_id', Auth::user()->id)->where('status', 1)->get([
            'company_association_master.*',
            'users.user_first_name',
            'users.user_email',
            'users.user_mobile',
        ]);
    return $check_association;
}

function checkCompanyAssociationForm(){
    $check_association_from = CompanyAssociationMaster::leftJoin('users', 'users.id', '=', 'company_association_master.request_to_user_id')
        ->where('company_association_master.request_from_user_id', Auth::user()->id)
        ->where('status', 1)
//        ->where('status', 25)
//        ->where('is_active', 1)
        ->where('is_archive', 0)
        ->get([
            'company_association_master.*',
            'users.user_first_name',
            'users.user_email',
            'users.user_mobile',
        ]);
    return $check_association_from;
}

function getBscicUser(){
    return collect(DB::select("select user_first_name as user_full_name, id from users where desk_id REGEXP '^([0-9]*[,]+)*2([,]+[,0-9]*)*$' and user_status = 'active' "))->pluck("user_full_name", "id")->toArray();
}

function getDivision(){
    return Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
}

function getCompanyRegistrationType(){
    return RegistrationType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
}

function getCompanyType(){
    return CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
}


