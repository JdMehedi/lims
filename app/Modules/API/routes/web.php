<?php

use App\Modules\API\Http\Controllers\APIController;
use Illuminate\Support\Facades\Route;



Route::group(['module' => 'API'], function () {

    Route::get('osspid/api', 'APIController@apiRequest');
    Route::post('osspid/api', 'APIController@apiRequest');
    Route::get('web/view-mis-reports/{report_id}/{permission_id}/{unix_time}', "AppsWebController@misReportView");
    Route::get('web/search/{enc_reg_key}/{keyword}', "AppsWebController@appSearch");
    Route::get('web/view-image/{enc_user_id}', "AppsWebController@viewImage");
    Route::get('/qr-code/show', 'QRLoginController@showQrCode');
    Route::get('/qr-login-check', 'QRLoginController@qrLoginCheck');
    Route::get('/qr-log-out', 'QRLoginController@qrLogout');

    // Action info store API
    Route::post('/api/new-job', 'APIController@newJob');
    Route::post('/api/action/new-job', 'APIController@actionNewJob');
    Route::get('/server-info', 'AppsWebController@serverInfo');
    Route::post('/dnothi/notification/status', [APIController::class, 'getNotificationStatus']);
});
