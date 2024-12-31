<?php
use Illuminate\Support\Facades\Route;

Route::group(array('Module'=>'Dashboard', 'middleware' => ['web','auth','GlobalSecurity']), function () {

//    Route::get('dashboard', 'DashboardController@dashboard');
    Route::get('/notifications/count', "DashboardController@notificationCount");
    Route::get('/notifications/show', "DashboardController@notifications");
    Route::get('/single-notification/{id}', "DashboardController@notificationSingle");
    Route::get('/notification-all', "DashboardController@notificationAll");

    Route::get('/special_service/list', "DashboardController@specialServices");
    Route::get('/special_service/service-list/{id}', "DashboardController@specialServicesList");
    Route::get('/special_service/add/{id}', "DashboardController@specialServicesCreate");
    Route::post('/special_service/store', "DashboardController@specialServicesDataStore");
    Route::post('/special_payment/store', "DashboardController@specialServicesPaymentStore");
    Route::post( 'special_service/fetchAppData', 'DashboardController@fetchSpecialData' );
    Route::resource('dashboard', 'DashboardController');
    Route::get('server-info', 'DashboardController@serverInfo');
    
});
if(env('IS_MOBILE')) {
    Route::get('dashboard', 'DashboardController@index');
}
