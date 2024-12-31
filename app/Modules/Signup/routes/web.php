<?php

use Illuminate\Support\Facades\Route;


Route::group(array('module' => 'Signup', 'prefix' => 'client', 'middleware' => ['web', 'XssProtection']), function () {

    Route::get('signup/identity-verify', 'SignupController@identityVerify');

    Route::get('signup/identity-verify/nid-tin-verify', 'SignupController@nidTinVerify')->name('nidTinVerify');

    Route::get('signup/getPassportData', 'SignupController@getPassportData');
    Route::post('signup/identity-verify', 'SignupController@identityVerifyConfirm');
    Route::post('signup/identity-verify-previous/{id}', 'SignupController@identityVerifyConfirmWithPreviousData');
    Route::get('signup/registration', 'SignupController@OSSsignupForm');
    Route::post('signup/registration', 'SignupController@OSSsignupStore');
    Route::post('signup/mobile-registration', 'SignupController@OSSsignupByMobile');
    Route::post('signup/email-mobile-validation', 'SignupController@verifyEmailMobileExist');
    Route::post('signup/getPassportData', [
        'as' => 'getPassportData',
        'uses' => 'SignupController@getPassportData'
    ]);

    Route::get('signup/identity-verify-mobile', 'SignupController@identityVerifyMobile');
});
