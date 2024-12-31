<?php

Route::group(['module' => 'ReportsV2', 'middleware' => ['web','auth']], function() {

    Route::get('/reportv2', "ReportsControllerV2@index");

    Route::get('/reportv2/create', "ReportsControllerV2@create");
    Route::get('/reportv2/edit/{id}', "ReportsControllerV2@edit");

    Route::get('/reportv2/show/{id}', "@show");
    Route::get('/reportv2/view/{id}', "ReportsControllerV2@view");
    Route::get('/reportv2/getuserbytype', "ReportsControllerV2@getusers");

    Route::get('/reportv2/add-to-favourite/{id}', "ReportsControllerV2@addToFavourite");
    Route::get('/reportv2/remove-from-favourite/{id}', "ReportsControllerV2@removeFavourite");

    Route::get('/reportv2/add-remove-favourite-ajax', "ReportsControllerV2@addRemoveFavouriteAjax");

    Route::post('/reportv2/verify', "ReportsControllerV2@reportsVerify");
    Route::get('/reportv2/tables', "ReportsControllerV2@showTables");
    Route::get('/reportv2/get-report-category', "ReportsControllerV2@getReportCategory");

    Route::post('/reportv2/show-report/{report_id}', "ReportsControllerV2@showReport");
    Route::get('/reportv2/show-report/{report_id}', "ReportsControllerV2@showReport");

    foreach (glob(__DIR__ . '/routes/*.php') as $route_file) {
        require $route_file;
    }
    Route::post('/reportv2/show-crystal-report-data', "PdfReportControllerV2@showCrystalReportData");
//    Route::post('/reportv2/show-crystal-report', "PdfReportControllerV2@showCrystalReportModal");
    Route::post('/reportv2/ajax-crystal-report-feedback', "PdfReportControllerV2@ajaxApiFeedback");
    Route::post('/reportv2/update-download-panel', "PdfReportControllerV2@updateDownloadPanel");
    Route::post('/reportv2/generate-crystal-report', "PdfReportControllerV2@generateCrystalReport");
    Route::patch('/reportv2/store', "ReportsControllerV2@store");
    Route::patch('/reportv2/update/{id}', "ReportsControllerV2@update");

});
