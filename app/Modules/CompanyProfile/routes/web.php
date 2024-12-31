<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'CompanyProfile', 'prefix' => 'client', 'middleware' => ['web','auth','GlobalSecurity']], function() {

    Route::get('company-profile/create', "CompanyProfileController@create");
    Route::get('company-profile/create/{id?}', "CompanyProfileController@create");
    Route::get('company-profile/get-country-by-investment-type', "CompanyProfileController@getCountryByInvestmentType");
    Route::get('company-profile/get-industry-type-by-investment', "CompanyProfileController@getIndustryByInvestment");
    Route::get('company-profile/get-sub-sector-by-sector', "CompanyProfileController@getSubSectorBySector");
//  store company profile
    Route::post('company-profile/store', "CompanyProfileController@storeCompany");
    Route::post('company-profile/upload-document', "CompanyProfileController@uploadDocument");
    Route::get('company-profile/get-company-profile', "CompanyProfileController@getCompanyProfile");
    Route::get('company-profile/get-edit-info', "CompanyProfileController@getEditInfo");
//    update company profile
    Route::patch('company-profile/update-general-info', "CompanyProfileController@updateGeneralInfo");
    Route::patch('company-profile/update-office-info', "CompanyProfileController@updateOfficeInfo");
    Route::patch('company-profile/update-factory-info', "CompanyProfileController@updateFactoryInfo");
    Route::patch('company-profile/update-ceo-info', "CompanyProfileController@updateCeoInfo");
    Route::post('company-profile/update-signature-info', "CompanyProfileController@updateSignatureInfo");
    Route::post('company-profile/update-signatureImage', "CompanyProfileController@updateSignatureImage");
    Route::patch('company-profile/update-activities-info', "CompanyProfileController@updateActivitiesInfo");

    //director section
    Route::get('company-profile/create-director', 'AppSubDetailsController@loadUserIdentificationModal');
    Route::get('company-profile/edit-director/{id}', 'AppSubDetailsController@editDirector');
    Route::post('company-profile/store-verify-director-session', 'AppSubDetailsController@storeVerifyDirectorSession');
    Route::post('company-profile/update-director-session', 'AppSubDetailsController@updateDirectorSession');
    Route::get('company-profile/delete-director-session/{id}', 'AppSubDetailsController@deleteDirectorSession');
    Route::post('company-profile/load-listof-directors', 'AppSubDetailsController@loadListOfDirectors');
    Route::post('company-profile/load-listof-directors-session', 'AppSubDetailsController@loadListOfDirectorsSession');
    Route::get('company-profile/set-single-director-info', 'AppSubDetailsController@setSingleDirectorInfo');

    Route::get('company-profile/create-info', 'AppSubDetailsController@createInfo');
    Route::get('company-profile/create-info/{id}', 'AppSubDetailsController@createInfo');
    Route::get('company-profile/select-director-info', 'AppSubDetailsController@selectDirectorInfo');


// Director from db
    Route::get('company-profile/edit-director-db/{id}', 'AppSubDetailsController@editDirectorDB');
    Route::get('company-profile/delete-director-db/{id}', 'AppSubDetailsController@deleteDirectorDB');
    Route::patch('company-profile/update-company-director-info', "CompanyProfileController@updateCompanyDirectorInfo");


});
Route::get('/company-info', 'CompanyProfileController@getCompanyInfo');
