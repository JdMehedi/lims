<?php

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\OSSPIDLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::post('login/load-login-otp-form', [LoginController::class, 'loadLoginOtpForm']);
Route::post('login/otp-login-validation-with-token-provide', [LoginController::class, 'otpLoginEmailValidationWithTokenProvide']);
Route::post('login/otp-login-check', [LoginController::class, 'checkOtpLogin']);
Route::post('login/otp-resent', [LoginController::class, 'OtpResent']);
Route::post('login/check-sms-send-status', [LoginController::class, 'checkSMSstatus']);

Route::group(array('middleware' => ['auth']), function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});




/*
 * Google Login routes
 */
Route::get('auth/google', [GoogleLoginController::class, 'redirectToProvider']);
Route::get('auth/google/callback', [GoogleLoginController::class, 'handleProviderCallback']);
Route::get('oauth/google/callback', [GoogleLoginController::class, 'handleProviderCallback']);


//OSSPID LOGIN and signup
Route::get('osspid-callback', [OSSPIDLoginController::class, 'osspidCallback']);
Route::get('osspid_signUp', [OSSPIDLoginController::class, 'osspid_signUp']);
Route::patch('osspid/store', [OSSPIDLoginController::class, 'OsspidStore']);
Route::get('osspid/logout', [OSSPIDLoginController::class, 'osspidLogout']);

//General LOGIN and signup
Route::post('login/check', [LoginController::class, 'check']);

/*bscic attachment*/

Route::get('bscic-attachment/{fileurl}', [CommonController::class, 'getAttachment']);

// Forget password routes
Route::get('re-captcha', [LoginController::class, 'reCaptcha']);
Route::get('forget-password', [LoginController::class, 'forgetPassword']);
Route::post('reset-forgotten-password', [LoginController::class, 'resetForgottenPass']);
Route::get('verify-forgotten-pass/{token_no}', [LoginController::class, 'verifyForgottenPass']);
Route::post('store-forgotten-password', [LoginController::class, 'StoreForgottenPass']);

Route::get('articles/support', [ArticlesController::class, 'aboutQuickServicePortal']);



Route::group(array('middleware' => ['auth']), function () {
    Route::get('common/activities/activities-summary', [CommonController::class, 'activitiesSummary']);
});

//Route::get('/show-information-from-rjsc', function (Request $request) {
//    sleep(120);
//    return response()->json([
//        'message' => '
//           <div style="font-family: Arial, sans-serif; line-height: 1.4; margin: -22px 0 20px 58px; max-width: 800px; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
//            <!-- Logo Section -->
//    <div style="text-align: center; margin-bottom: 20px;">
//        <img src="/assets/rjsc/rjsc.png" alt="RJSC Logo" style="max-width: 150px; height: auto;"/>
//    </div>
//    <h2 style="text-align: center; color: #2c3e50; font-weight: bold; margin-bottom: 10px;">
//        Office of the Registrar of Joint Stock Companies and Firms
//    </h2>
//    <br>
//    <br>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Company Name: SHIBLI SHAMSI LIMITED
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Incorporation No.: C-155907/2019
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Date: 10/10/2019
//    </p>
//
//    <h3 style="margin-top: 20px; font-weight: bold; color: #34495e; padding-top: 10px; text-align: center;">
//        List of Shareholder/Director
//    </h3>
//    <br>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Company Name: Faiza Gulraiz
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Designation: Chairman
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        NID/Passport No.: AB4727102
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        No. of Shares Taken: 1000
//    </p>
//   <br>
//
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Company Name: Gulrez Shoaib
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Designation: Managing Director
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        NID/Passport No.: CZ5191803
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        No. of Shares Taken: 1250
//    </p>
//    <br>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Company Name: Muhammad Salman
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Designation: Director
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        NID/Passport No.: PK5758782
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        No. of Shares Taken: 700
//    </p>
//    <br>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Company Name: Muhammad
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Designation: Director
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        NID/Passport No.: CX8913782
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        No. of Shares Taken: 700
//    </p>
//    <br>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Company Name: Fatima Gulrez
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        Designation: Director
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        NID/Passport No.: AA4727322
//    </p>
//    <p style="text-align: center; color: #34495e; margin: 5px 0;">
//        No. of Shares Taken: 600
//    </p>
//    <!-- Close button -->
//    <span id="verify" class="btn btn-primary close" onclick="closeModal()">Close</span>
//</div>
//
//
//        '
//    ]);
//});



Route::get('/show-information-from-rjsc', [CommonController::class, 'getRJSCInformation']);


