<?php

Route::group(['module' => 'SonaliPayment', 'middleware' => ['api'], 'namespace' => 'App\Modules\SonaliPayment\Controllers'], function () {

    //Route::post('api/sp-ipn', 'IpnController@apiIpnRequestPOST');
    Route::post('api/v1/sp-ipn', 'IpnApiController@apiIpnRequestPOST');
});
