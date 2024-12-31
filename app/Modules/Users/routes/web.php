<?php

use Illuminate\Support\Facades\Route;

Route::group(array('module' => 'Users', 'middleware' => ['web', 'auth', 'GlobalSecurity', 'checkAdmin', 'XssProtection']), function () {

    /* User related */
    Route::get('/users/lists', "UsersController@lists");
    Route::get('users/view/{id}', "UsersController@view");
    Route::patch('/users/update/{id}', "UsersController@update");

    Route::get('/users/edit/{id}', "UsersController@edit");
    Route::get('/users/activate/{id}', "UsersController@activate");
    Route::get('/users/isApproved/{id}', "UsersController@isApproved");

    /* Assign parameters */
    Route::get('users/assign-parameters/{id}', "UsersController@assignParameters");
    Route::post('users/assign-parameters-save', "UsersController@assignParametersSave");

    Route::get('users/failedLogin-history/{id}', "UsersController@failedLoginHist");
    Route::post('users/get-failed-login-data', "UsersController@getRowFailedData");
    Route::post('users/failed-login-data-resolved', "UsersController@FailedDataResolved");
    /* End of User related */

    /* New User Creation by Admin */
    Route::get('/users/force-logout/{id}', 'UsersController@forceLogout');

    Route::post('users/identity-verify', "UsersController@identityVerifyConfirm");
    Route::get('users/create-new-user', "UsersController@createNewUser");
    Route::get('users/create-new-user/{skip}', "UsersController@createNewUser");
    Route::patch('/users/store-new-user', "UsersController@storeNewUser");
    /* End of New User Creation by Admin */

});

// Only Login User can do it.
Route::group(array('module' => 'Users', 'middleware' => ['web', 'auth', 'GlobalSecurity','XssProtection']), function () {

    /* User profile update */
    Route::get('users/profileinfo', "UsersController@profileInfo");
    Route::post('users/profile_updates/{id}', [
        'uses' => 'UsersController@profile_update'
    ]);
    Route::patch('users/update-password-from-profile', "UsersController@updatePassFromProfile");

    /* User related */
    Route::post('users/get-access-log-data-for-self', "UsersController@getAccessLogDataForSelf");
    Route::post('users/get-access-log-failed', "UsersController@getAccessLogFailed");
    Route::post('users/get-last-50-actions', "UsersController@getLast50Actions");
    Route::get('users/access-log/{id}', "UsersController@accessLogHist");
    Route::get('users/get-access-log-data', "UsersController@accessLogHist");

    Route::get('users/verify-nid', "UsersController@verifyNID");
    Route::get('/users/logout', "UsersController@logout");


    /* Reset Password from profile and Admin list */

    Route::get('users/reset-password/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'UsersController@resetPassword'
    ]);

    /*
     * datatable
     */
    Route::post('users/get-user-list', "UsersController@getList");
    Route::post('users/get-access-log-data/{id}', "UsersController@getAccessLogData");


    Route::post('users/email-verification', "UsersController@emailVerification");
});

Route::group(array('module' => 'Users', 'middleware' => ['web', 'auth', 'checkAdmin','XssProtection']), function () {
    Route::get('/users/delegate', "UsersController@delegate");
    Route::get('/users/remove-delegation/{id?}', "UsersController@removeDelegation");
    Route::get('/users/delegation/{id}', "UsersController@delegation");
    Route::get('/users/delegations/{id}', "UsersController@delegations");
    Route::patch('/users/process-delegation', "UsersController@processDelegation");
    Route::patch('/users/store-delegation', "UsersController@storeDelegation");

    Route::post('/users/get-delegate-userinfo', "UsersController@getDelegatedUserInfo");
    Route::post('/users/admin/get-delegate-user-list', "UsersController@getDelegateUserListForAdmin");
});

// Without Authorization (Login is not required)
Route::group(array('module' => 'Users', 'middleware' => ['web']), function () {

    Route::get('/users/create', [
        'as' => 'user_create_url',
        'uses' => 'UsersController@create'
    ]);
    Route::patch('/users/store', "UsersController@store");

    // verification
    Route::get('/users/verify-created-user/{encrypted_token}', "UsersController@verifyCreatedUser");
    Route::patch('/users/created-user-verification/{encrypted_token}', "UsersController@createdUserVerification");
    Route::get('users/verification/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'UsersController@verification'
    ]);
    Route::patch('users/verification_store/{confirmationCode}', [
        'as' => 'confirmation_path',
        'uses' => 'UsersController@verification_store'
    ]);
    Route::get('/users/get-userdesk-by-type', 'UsersController@getUserDeskByType');

    //Mail Re-sending
    Route::get('users/reSendEmail', "UsersController@reSendEmail");
    Route::patch('users/reSendEmailConfirm', "UsersController@reSendEmailConfirm");
    Route::get('/users/get-district-by-division', 'UsersController@getDistrictByDivision');
    Route::get('/users/get-thana-by-district-id', 'UsersController@getThanaByDistrictId');
    Route::post('/users/validateAutoCompleteData/{type}', 'UsersController@validateAutoCompleteData');
    Route::get('/users/resendMail', 'UsersController@resendMail');
    Route::get('users/get-user-session', 'UsersController@getUserSession');
    Route::post('users/resend-email-verification', "UsersController@resendVerification");
    Route::get('users/resend-email-verification-from-admin/{enc_user_id}', "UsersController@resendVerificationFromAdmin");
});

Route::group(array('module' => 'Users', 'middleware' => ['web', 'auth','XssProtection']), function () {
    /*   To step Verification */
    Route::get('/users/two-step', 'UsersController@twoStep');
    Route::patch('/users/check-two-step', 'UsersController@checkTwoStep');
    Route::patch('/users/verify-two-step', 'UsersController@verifyTwoStep');
});

// Route::get('manual-force-logout', 'UsersController@manualForceLogout');
