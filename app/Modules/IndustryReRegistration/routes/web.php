<?php

use App\Modules\ProcessPath\Http\Controllers\ProcessPathController;
use Illuminate\Support\Facades\Route;

Route::get('industry-re-registration', 'IndustryReRegistrationController@welcome');



Route::group(['module' => 'IndustryReRegistration', 'middleware' => ['web', 'auth', 'checkAdmin']], function() {

    Route::get('industry-re-registration/add', 'IndustryReRegistrationController@appForm');
    Route::get('industry-re-registration/edit/{id}/{openMode}', 'IndustryReRegistrationController@appFormEdit');
    Route::post('industry-re-registration/store', 'IndustryReRegistrationController@appStore');
    Route::post('industry-re-registration/ajax-store', 'IndustryReRegistrationController@ajaxAppStore');
    Route::get('industry-re-registration/view/{id}/{openMode}', 'IndustryReRegistrationController@appFormView');

    Route::post('industry-re-registration/get-local-machinery', 'IndustryReRegistrationController@getLocalMachinery');
    Route::get('industry-re-registration/local-machinery/add/{app_id}', 'IndustryReRegistrationController@localMachineryAdd');
    Route::post('industry-re-registration/local-machinery/store', 'IndustryReRegistrationController@localMachineryStore');
    Route::get('industry-re-registration/edit-local-machinery/{machine_id}', 'IndustryReRegistrationController@localMachineryEdit');
    Route::get('industry-re-registration/delete-local-machinery/{machine_id}/{app_id}', 'IndustryReRegistrationController@localMachineryDelete');

    Route::post('industry-re-registration/get-imported-machinery', 'IndustryReRegistrationController@getImportedMachinery');
    Route::get('industry-re-registration/imported-machinery/add/{app_id}', 'IndustryReRegistrationController@importedMachineryAdd');
    Route::post('industry-re-registration/imported-machinery/store', 'IndustryReRegistrationController@importedMachineryStore');
    Route::get('industry-re-registration/edit-imported-machinery/{machine_id}', 'IndustryReRegistrationController@importedMachineryEdit');
    Route::get('industry-re-registration/delete-imported-machinery/{machine_id}/{app_id}', 'IndustryReRegistrationController@importedMachineryDelete');

    Route::get('industry-re-registration/afterPayment/{payment_id}', 'IndustryReRegistrationController@afterPayment');
    Route::get('industry-re-registration/afterCounterPayment/{payment_id}', 'IndustryReRegistrationController@afterCounterPayment');

    //gov payment
    Route::post('industry-re-registration/payment', "IndustryReRegistrationController@Payment");

    Route::get('industry-re-registration/get-industry-type-by-investment', "IndustryReRegistrationController@getIndustryByInvestment");

//    pdf design test
//    Route::get('industry-new/pdf', 'IndustryNewController@pdf');


});

// Process path related route
// Process path related route
Route::group(array('module' => 'IndustryReRegistration','prefix' => 'client', 'middleware' => ['web', 'auth', 'checkAdmin','GlobalSecurity']), function () {

    Route::get('industry-re-registration/list/{process_type_id}', [ProcessPathController::class, 'processListById']);

});

// Process path related route
Route::group(array('module' => 'IndustryReRegistration', 'middleware' => ['web', 'auth', 'checkAdmin']), function () {

    Route::get('industry-re-registration/list/{process_type_id}', [ProcessPathController::class, 'processListById']);

});



