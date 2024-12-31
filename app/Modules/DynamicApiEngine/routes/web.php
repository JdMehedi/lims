<?php

use Illuminate\Support\Facades\Route;
Route::group(['module' => 'DynamicApiEngine', 'middleware' => []], function () {

    Route::any('d-api/{API_TYPE}/{OTHERS_PARAMETERS}', 'DynamicApiEngineController@mainEngine');


    Route::post('d-api/get-token', 'DynamicApiAuthenticationController@handleTokenGenerationService');
});


Route::group([
    'module' => 'DynamicApiEngine', 'middleware' => [
        'web',
        'auth', 'XssProtection'
    ]

], function () {

    Route::get('dynamic-api-engine/list', 'DynamicApiCmsController@index');
    // Route::get('settings/dynamic-api-engine/create', 'DynamicApiCmsController@create');
    Route::post('dynamic-api-engine/get-list', 'DynamicApiCmsController@getApiList');
    Route::post('dynamic-api-engine/delete-api', 'DynamicApiCmsController@deleteApi');
    Route::post('dynamic-api-engine/store-basic-info', 'DynamicApiCmsController@storeApiBasicInfo');
    Route::post('dynamic-api-engine/update-api-basic-info', 'DynamicApiCmsController@updateApiBasicInfo');
    Route::get('dynamic-api-engine/open-api/{id}', 'DynamicApiCmsController@openApi');
    Route::post('dynamic-api-engine/get-parameter-list', 'DynamicApiCmsController@getParameterList');
    Route::post('dynamic-api-engine/store-parameter-data', 'DynamicApiCmsController@storeParameterData');
    Route::post('dynamic-api-engine/get-parameter-content-for-validation', 'DynamicApiCmsController@parameterValidationContent');
    Route::post('dynamic-api-engine/get-parameter-content-for-edit', 'DynamicApiCmsController@parameterEditContent');
    Route::post('dynamic-api-engine/update-parameter-validation-data', 'DynamicApiCmsController@updateParameterValidationData');
    Route::post('dynamic-api-engine/update-parameter-name', 'DynamicApiCmsController@updateParameterName');
    Route::post('dynamic-api-engine/delete-api-parameter', 'DynamicApiCmsController@deleteParameter');

    Route::post('dynamic-api-engine/get-operation-list', 'DynamicApiOperationsCmsController@getOperationList');
    Route::post('dynamic-api-engine/store-operational-data', 'DynamicApiOperationsCmsController@storeOperationalData');
    Route::post('dynamic-api-engine/get-operation-data-for-edit', 'DynamicApiOperationsCmsController@operationData');
    Route::post('dynamic-api-engine/update-operational-data', 'DynamicApiOperationsCmsController@updateOperationalData');
    Route::post('dynamic-api-engine/delete-api-operation', 'DynamicApiOperationsCmsController@deleteOperation');

    Route::post('dynamic-api-engine/get-output-list', 'DynamicApiOutputsCmsController@getOutputList');
    Route::post('dynamic-api-engine/store-output-data', 'DynamicApiOutputsCmsController@storeOutputData');
    Route::post('dynamic-api-engine/get-output-data-for-edit', 'DynamicApiOutputsCmsController@outputData');
    Route::post('dynamic-api-engine/update-output-data', 'DynamicApiOutputsCmsController@updateOutputData');
    Route::post('dynamic-api-engine/delete-api-output', 'DynamicApiOutputsCmsController@deleteOutput');

    Route::get('dynamic-api-engine/authentications/list', 'DynamicApiAutheticationCmsController@index');
    Route::post('dynamic-api-engine/authentications/get-client-list', 'DynamicApiAutheticationCmsController@getClientList');
    Route::post('dynamic-api-engine/authentications/get-api-map-info', 'DynamicApiAutheticationCmsController@apiMapInfo');
    Route::post('dynamic-api-engine/authentications/store-api-map-info', 'DynamicApiAutheticationCmsController@storeApiMapInfo');
    Route::post('dynamic-api-engine/authentications/store-api-client-info', 'DynamicApiAutheticationCmsController@storeApiClientInfo');
    Route::post('dynamic-api-engine/authentications/get-api-client-info', 'DynamicApiAutheticationCmsController@getApiClientInfo');
    Route::post('dynamic-api-engine/authentications/update-api-client-info', 'DynamicApiAutheticationCmsController@updateApiClientInfo');
    Route::post('dynamic-api-engine/authentications/delete-api-client-info', 'DynamicApiAutheticationCmsController@deleteApiClientInfo');
});
