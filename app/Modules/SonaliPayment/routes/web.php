<?php

use Illuminate\Support\Facades\Route;
/*
 * All routes with 'spg/' prefix
 */

Route::group(['module' => 'SonaliPayment', 'prefix' => 'spg', 'middleware' => ['web']], function () {
    //  Route::group(['prefix' => 'spg', 'module' => 'SonaliPayment', 'middleware' => ['web', 'auth', 'checkAdmin'], 'namespace' => 'App\Modules\SonaliPayment\Http\Controllers'], function () {

    // Routes for Payment Lists, open, verify by ref. no., history verify
    Route::get('initiate-multiple/{id}', 'SonaliPaymentController@initiatePaymentMultiple');
    Route::post('callbackM', 'SonaliPaymentController@callbackMultiple');
    Route::get('counter-payment-check/{id}/{status}', 'SonaliPaymentController@counterPaymentCheck');
    Route::get('offline-payment-verify', 'SonaliPaymentController@offlinePaymentVerify');
    Route::get('list', 'SonaliPaymentController@index');
    Route::post('getList', 'SonaliPaymentController@paymentList');
    Route::get('payment-history/{id}', 'SonaliPaymentController@indivPaymentHistory');
    Route::post('history-data/', 'SonaliPaymentController@indivPaymentHistoryData');
    Route::get('history-verify/{id}/{histId}', 'SonaliPaymentController@verifyTransactionHistory');
    Route::get('ref-verification/{id}', 'SonaliPaymentController@verifyTransactionByRefNo');
    Route::get('verifyAndComplete/{id}', 'SonaliPaymentController@verifyAndComplete');
    Route::get('daily-tansaction', 'SonaliPaymentController@dailyTransaction');
    Route::get('search', 'SonaliPaymentController@getPaymentList');
    // End Routes for Payment Lists, open, verify by ref. no., history verify


    // Routes for Payment configuration start
    Route::get('payment-configuration', 'PaymentConfigController@paymentConfiguration');
    Route::post('get-payment-configuration-details-data', 'PaymentConfigController@getPaymentConfiguration');
    Route::get('create-payment-configuration', 'PaymentConfigController@paymentConfigurationCreate');
    Route::post('store-payment-configuration', 'PaymentConfigController@paymentConfigurationStore');
    Route::get('edit-payment-configuration/{id}', 'PaymentConfigController@editPaymentConfiguration');
    Route::patch('update-payment-configuration/{id}', 'PaymentConfigController@updatePaymentConfiguration');
    Route::post('get-payment-distribution-data', 'PaymentConfigController@getPaymentDistributionData');
    Route::get('stakeholder-distribution/{payConfigID}', 'PaymentConfigController@stakeholderDistribution');
    Route::post('stakeholder-distribution', 'PaymentConfigController@stakeholderDistributionStore');
    Route::get('stakeholder-distribution-edit/{distributionId}', 'PaymentConfigController@editStakeholderDistribution');
    Route::post('stakeholder-distribution-update/{id}', "PaymentConfigController@stakeholderDistributionStore");
    // End Routes for Payment configuration start

    // Payment invoice/ voucher routes
    Route::get('payment-voucher/{id}', 'PaymentInvoiceController@paymentVoucher');
    Route::get('counter-payment-voucher/{id}', 'PaymentInvoiceController@counterPaymentVoucher');



    Route::post('payment-panel', 'PaymentPanelController@getPaymentPanel');

    //Duplicate for implement pay order
    Route::post('payment-panelV2', 'PaymentPanelController@getPaymentPanelV2');

    Route::post('payment/store', 'PaymentPanelController@submitPayment');

    Route::get('payment-view/{process_type_id}/{app_id}', 'PaymentPanelController@getViewPaymentPanel');
    Route::get('vue/payment-view/{process_type_id}/{app_id}', 'PaymentPanelController@getViewPaymentPanelVue');
});

Route::get('show-payment-voucher/{tracking_no}', 'PaymentInvoiceController@showVoucher');

Route::get('pay-order/add-row','SonaliPaymentController@addRow');
Route::post('api/DataUpdate','SonaliPaymentController@DataUpdateByIPN');

/*
 * All routes with 'ipn/' prefix
 */
// Route::group(['prefix' => 'ipn', 'module' => 'SonaliPayment', 'middleware' => ['web', 'auth', 'checkAdmin'], 'namespace' => 'App\Modules\SonaliPayment\Http\Controllers'], function () {


Route::group(['module' => 'SonaliPayment', 'prefix' => 'ipn', 'middleware' => ['web']], function () {  // 'auth', 'GlobalSecurity', 'checkAdmin'
    Route::get('ipn-list', "IpnController@ipnList");
    Route::post('get-list', "IpnController@getIpnList");
    Route::get('ipn-history/{id}', "IpnController@ipnHistory");
});
