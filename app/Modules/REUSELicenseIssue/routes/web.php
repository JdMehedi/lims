<?php

use App\Modules\ProcessPath\Http\Controllers\ProcessPathController;
use App\Modules\REUSELicenseIssue\Http\Controllers\ReuseController;
use Illuminate\Support\Facades\Route;


//Route::group(['module' => 'ISPLicenseIssue', 'middleware' => ['web', 'auth', 'checkAdmin','GlobalSecurity']], function () {
  Route::get('isp-license-issue/add', 'ISPLicenseIssueController@appForm');
//    Route::post('isp-license-issue/store', 'ISPLicenseIssueController@appStore');
//    Route::get('isp-license-issue/preview', "ISPLicenseIssueController@preview");
//    Route::get('isp-license-issue/guidelines', "ISPLicenseIssueController@guidelines");
//    Route::get('isp-license-issue/view/{id}/{openMode}', "ISPLicenseIssueController@appFormView");
//
//    Route::get('isp-license-issue/edit/{id}/{openMode}', "ISPLicenseIssueController@appFormEdit");
//
//    Route::post('isp-license-issue/get-payment-data-by-license-type', "ISPLicenseIssueController@getPaymentDataByLicense");
//
   Route::post('isp-license-issue/add-row', "ISPLicenseIssueController@addRow");
//});


// Process path related route
//Route::group(array('module' => 'REUSELicenseIssue','prefix' => 'client','middleware' => ['web', 'auth', 'checkAdmin', 'GlobalSecurity']), function () {
//
//    Route::get('/{asdfasdf}/list/{process_type_id}', [ProcessPathController::class, 'processListById']);
//
//});
//
//// Process path related route
//Route::group(array('module' => 'REUSELicenseIssue','middleware' => ['web', 'auth', 'checkAdmin']), function () {
//
    Route::get('/isp-license-issue/list/{process_type_id}', [ProcessPathController::class, 'processListById']);
//
//});


//Route::group(array('module' => 'REUSELicenseIssue','prefix' => 'client','middleware' => ['web', 'auth', 'checkAdmin']), function () {
//
//    Route::get('process/{process_type_form_id}/add/{process_type_id}', [ProcessPathController::class, 'processListById']);
//
//});

Route::post('/add-row', "ReuseController@addRow");
Route::post('vsat-license-issue/add-row', "ReuseController@addRowVSAT");
Route::post('/vsat-license-ammendment-add-row',"ReuseController@addRowVSATAmendment");
Route::post('/iptsp-license-ammendment/add-row', "ReuseController@addRowIPTSPAmendment");
