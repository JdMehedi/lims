<?php

use Illuminate\Support\Facades\Route;


Route::group(array(
    'module' => 'CompanyAssociation', 'prefix' => 'client',  'middleware' => ['web', 'auth',  'XssProtection']), function () {

    Route::get('company-association/create', 'CompanyAssociationController@appForm');
    Route::post('company-association/store', 'CompanyAssociationController@storeCompanyAssociationV2');
    Route::get('company-association/open/{id}', 'CompanyAssociationController@appOpen');
    Route::post('company-association/update', 'CompanyAssociationController@appUpdate');
    Route::get('company-association/status/{request_id}/{status_id}', 'CompanyAssociationController@status_update');
    Route::post('company-association/approve-reject', 'CompanyAssociationController@companyActivatesAction');
    Route::post('company-association/cancel-association', 'CompanyAssociationController@cancelAssociation');
});


/*
 * These url need to call without checkAdmin middleware.
 * because, company-association/select-company will redirect from checkAdmin if user does not select working company.
 * redirect url in checkAdmin middleware will generate error.
 */
Route::group(array('module' => 'CompanyAssociation', 'middleware' => ['web', 'auth', 'XssProtection']), function () {

    Route::get('company-association/select-company', 'CompanyAssociationController@selectCompany');
    Route::get('company-association/skip', 'CompanyAssociationController@skipCompanyAssociation');
    Route::post('company-association/update-working-company', 'CompanyAssociationController@updateWorkingCompany');
    Route::get('company-association/view/{id}/{openMode}', 'CompanyAssociationController@appFormView');
    Route::get('company-association/list', 'CompanyAssociationController@getList');
    Route::post('company-association/get-list', 'CompanyAssociationController@getCompanyAssociationList');
});

Route::group(array(
    'module' => 'CompanyAssociation', 'prefix' => 'client', 'middleware' => ['web', 'auth', 'XssProtection']), function () {
    Route::get('company-association/get-company-list', 'CompanyAssociationController@getAssociationCompanyList');
    Route::get('company-association/get-user-list', 'CompanyAssociationController@getAssociationUserList');
    Route::post('company-association/company-info', 'CompanyAssociationController@companyInformation');
});
