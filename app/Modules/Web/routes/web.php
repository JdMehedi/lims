<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;


Route::group(array('module' => 'Web', 'middleware' => ['web', 'XssProtection']), function () {

    Route::get('/login/{lang}', 'WebController@index');


    Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'WebController@switchLang']);

//==============================*********************=====================================
    // route with new view
    Route::get('/', 'WebController@index');
    Route::get('/login', 'Auth\LoginController@loginView')->name('login');
    Route::get('/login-with-mobile', 'Auth\LoginController@loginWithMobileView');
    Route::get('/otp-phone', 'Auth\LoginController@loginWithOTPView');
    Route::get('/signup', 'Auth\SignupController@signupView');
    Route::get('/signup-email', 'Auth\SignupController@signupEmailView');
    Route::post('/new-signup-by-email', 'Auth\SignupController@signupEmailAction');
    #Route::get('/signup-mobile', 'Auth\SignupController@signupMobileView');
    Route::get('/user-signup', 'Auth\SignupController@signupMobileView');
    Route::get('/user-manual', 'WebController@userMamual');
    Route::get('/guidelines', 'WebController@guidelinesData');
    Route::get('/notices', 'WebController@noticeData');
    Route::get('/licenses', 'WebController@licenseData');
    // route with new view

    // route with new API
    Route::post('/send-otp', 'Auth\LoginController@sendOtp');
    // route with new API
//==============================*********************====================================


//    Route::get('/login', 'WebController@index');
//    Route::post('login', ['as' => 'login', 'uses' => 'WebController@index']);


    Route::get('/login/{lang}', 'WebController@index');

    Route::get('web/notice', 'WebController@notice');

    Route::get('web/service-list', 'WebController@serviceList');
    Route::post('web/get-service-list', 'WebController@getServiceList');

    Route::get('web/application-chart', 'WebController@applicationChart');
    Route::post('web/get-service-list', 'WebController@getServiceList');

    Route::get('/viewNotice/{id}/{slug}', 'WebController@viewNotice');
    Route::get('/industrial-city-details/{id}', 'WebController@industrialCityDetails');
    Route::get('/need-help', "WebController@support");
    Route::get('/show-voucher/{id}', "WebController@qrVoucherPdf");

    Route::get('/log', '\Srmilon\LogViewer\LogViewerController@index');

    Route::get('/docs/{pdftype}/{id}', 'VerifyDocController@verifyDoc');
    Route::get('/docs/{id}', 'VerifyDocController@verifyDoc');

    Route::get('available-services', 'FrontPagesController@availableServices');
    Route::get('bscic-industrial-city-list', 'FrontPagesController@industrialCityMap')->name('industrialCity.cityList');
    Route::get('bscic-industrial-city-map-data', 'FrontPagesController@industrialCityMapData');
    Route::get('bscic-industrial-city/{city_id?}', 'FrontPagesController@industrialCity')->name('industrialCity.details');
    Route::get('document-and-downloads', 'FrontPagesController@documentAndDownloads');
    Route::get('new-business', 'FrontPagesController@newBusiness');
    Route::get('location-map', 'FrontPagesController@locationMap');



    Route::get('article/{page_name}', 'FrontPagesController@articlePage');
    Route::get('web/load-more-notice', 'WebController@loadMoreNotice');
    Route::get('web/load-city-office', 'WebController@loadCityOffice');


    Route::get('guidelines/{group_nm}/{process_type}','WebController@guidelines');
});

Route::get('/image/paid', function () {
    $path = app_path('Modules/SonaliPayment/resources/images/paid.png');
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('paid.image');

Route::get('/image/btrc', function () {
    $path = app_path('Modules/SonaliPayment/resources/images/btrc.png');
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('btrc.image');

Route::get('/notify-license-expirity', function(){
    Artisan::call('notify-license-expirity:start');
});
Route::get('/notify-bg-expirity', function(){
    Artisan::call('notify-bg-expirity:start');
});

Route::get('/send-notification', function () {
    Artisan::call('send-notification 10');
});

Route::get('/send-license-to-nothi', function () {
    Artisan::call('send-license-to-nothi 10');
});

