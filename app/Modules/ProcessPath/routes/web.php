<?php

use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\REUSELicenseIssue\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::group(array('module' => 'ProcessPath', 'middleware' => ['web', 'auth', 'XssProtection', 'GlobalSecurity']), function () {

    //    List of all process
    Route::get('process/list', "ProcessPathController@processListById");
    Route::post('process/list', "ProcessPathController@processListById");
    Route::get('process/list/{process_id}', "ProcessPathController@processListById");

    Route::get('/client/{form_url}/list/{process_type_id}', "ProcessPathController@processListById");
    Route::get('/{form_url}/list/{process_type_id}', "ProcessPathController@processListById");

    Route::post('process-path/get-desk-by-status', "ProcessPathController@getDeskByStatus");
    Route::post('process-path/batch-process-update', "ProcessPathController@updateProcess");
    Route::get('process-path/check-process-validity', "ProcessPathController@checkApplicationValidity");

    Route::get('process-path/ajax/{param}', 'ProcessPathController@ajaxRequest');

    Route::get('process/get-list/{status?}/{desk?}', [
        'as' => 'process.getList',
        'uses' => 'ProcessPathController@getList'
    ]);
    Route::get('process/set-process-type', [
        'as' => 'process.setProcessType',
        'uses' => 'ProcessPathController@setProcessType'
    ]);
    Route::get('process/search-process-type', [
        'as' => 'process.searchProcessType',
        'uses' => 'ProcessPathController@searchProcessType'
    ]);


    // New Process route
    Route::get('process/{module}/add/{process_type_id}', "ProcessPathController@applicationAdd");
//    Route::get('process/license/add/{process_type_id}', "ProcessPathController@applicationAdd");
    //TODO::Dynamic Add ajax form
    Route::get('process/license/content/{process_type_id}', "ProcessPathController@commonAddForm");
    //TODO::Dynamic Store form data
    Route::post('process/license/store/{process_type_id}', "ProcessPathController@commonStoreForm");
    //TODO::Dynamic Preview ajax form
    Route::get('process/license/preview/{process_type_id}', "ProcessPathController@commonPreview");

    //TODO::Dynamic Add row ajax form
    Route::post( '{module}/add-row', 'ProcessPathController@addRow' );

    Route::get('process/license/preview/{process_type_id}', "ProcessPathController@commonPreview");



    Route::get('process/{module}/view/{app_id}/{process_type_id}', "ProcessPathController@applicationOpen");
    //TODO::Dynamic View ajax form
    Route::get('{module}/view/{id}/{openMode}', "ProcessPathController@applicationView");

    Route::get('process/{module}/edit/{app_id}/{process_type_id}', "ProcessPathController@applicationEdit");
    //TODO::Dynamic Edit ajax form
    Route::get('{module}/edit/{id}/{openMode}', "ProcessPathController@commonFormEdit");
    //TODO::Dynamic fetch data
    Route::post( '{module}/fetchAppData', 'ProcessPathController@fetchAppData' );
    //TODO::Dynamic Payment data by license type
    Route::post( '{form_url}/get-payment-data-by-license-type', 'ProcessPathController@getPaymentDataByLicenseType' );
    Route::post( '{form_url}/check-application-limit', 'ProcessPathController@checkApplicationLimit' );

    //Route::resource('ProcessPath', 'ProcessPathController');
    Route::post('process/help-text', "ProcessPathController@getHelpText");

    Route::post('process/favorite-data-store', "ProcessPathController@favoriteDataStore");
    Route::post('process/favorite-data-remove', "ProcessPathController@favoriteDataRemove");

    Route::get('process-path/request-shadow-file', "ProcessPathController@requestShadowFile");

    // Process flow graph route
    Route::get('process/graph/{process_type_id}/{app_id}/{cat_id}', 'ProcessPathController@getProcessData');
    // get shadow file history
    Route::get('process/get-shadow-file-hist/{process_type_id}/{ref_id}', 'ProcessPathController@getShadowFileHistory');
    // get application history
    Route::get('process/get-app-hist/{process_list_id}', 'ProcessPathController@getApplicationHistory');

    //get desk by user
    Route::post('process-path/get-user-by-desk', "ProcessPathController@getUserByDesk");

    //batch process
    Route::get('process/batch-process-set', "ProcessPathController@batchProcessSet");
    Route::get('process/batch-process-skip/{id}', "ProcessPathController@skipApplication");
    Route::get('process/batch-process-previous/{id}', "ProcessPathController@previousApplication");

    Route::get('process-path/verify_history/{process_list_id}', 'ProcessPathController@verifyProcessHistory');

    // Certificate Regeneration
    Route::get('process/certificate-regeneration/{app_id}/{process_type_id}', 'ProcessPathController@certificateRegeneration');

    // Service wise application count and list
    Route::post('process/get-servicewise-count', "ProcessPathController@statusWiseApps");
    Route::get('check-occupancy-availability-of-isp', 'ProcessPathController@checkOccupancyAvailability');

    Route::get('license-json-generate/{number?}', "ProcessPathController@generateLicenseJson");
});


/** the code only for client */
Route::group(['module' => 'ProcessPath', 'prefix' => 'client', 'middleware' => ['web', 'auth', 'GlobalSecurity']], function () {

    Route::get('process/list', "ClientProcessPathController@processListById");
    Route::get('process/details/{id}', "ClientProcessPathController@processDetails");
    // New Process route
    Route::get('process/{module}/add/{process_type_id}', "ProcessPathController@applicationAdd");
    Route::get('process/{module}/view/{app_id}/{process_type_id}', "ProcessPathController@applicationOpen");
    Route::get('process/{module}/edit/{app_id}/{process_type_id}', "ProcessPathController@applicationEdit");

    Route::get('process/check-cancellation', "ClientProcessPathController@checkCancellation");
    Route::get('process/set-can-app', "ClientProcessPathController@setCanApp");
});


Route::group(['module' => 'ProcessPath', 'prefix' => 'vue', 'middleware' => ['web', 'auth', 'GlobalSecurity']], function () {

    Route::get('get-auth-data', "ProcessListController@getAuthData");

    Route::get('process', "ProcessListController@index");
    Route::get('process/get-list/{status?}/{desk?}', 'ProcessListController@getList');
    Route::get('process-type', 'ProcessListController@getProcessTypes');
    Route::get('process-type/{process_type_id}', 'ProcessListController@getProcessTypeInfo');

    Route::post('process/favorite-data-store', 'ProcessPathController@favoriteDataStore');
    Route::post('process/favorite-data-remove', 'ProcessPathController@favoriteDataRemove');


    Route::get('process-type/{process_type_id}/status', 'ProcessListController@getStatusListByProcessType');


    Route::get('process/view/{app_id}/{process_type_id}', "ProcessListController@applicationView");


    Route::post('process/update', 'ProcessListController@updateProcessVue');


    Route::get('process/shadow-file-hist/{process_type_id}/{ref_id}', 'ProcessListController@getShadowFileHistory');
    Route::get('process/history/{process_list_id}', 'ProcessListController@getApplicationHistory');

    Route::get('process/status-wise-app-count/{process_type_id}', 'ProcessListController@statusWiseAppsCount');
});
