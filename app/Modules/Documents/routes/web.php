<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Documents', 'middleware' => ['web', 'auth', 'checkAdmin', 'XssProtection']], function () {

    Route::get('documents/get-app-docs', 'DocumentsController@getAppDocuments');
    Route::post('documents/upload-document', 'DocumentsController@uploadDocument');

    Route::get('documents/get-user-documents', "UserCompanyDocuments@getUserDocuments");
    Route::get('documents/upload-user-document/{doc_id}', "UserCompanyDocuments@userDocUploadModal");
    Route::post('documents/update-user-document/{doc_id}', "UserCompanyDocuments@updateUserDocument");
    Route::get('documents/lists', "UserCompanyDocuments@documentList");
    Route::get('documents/get-user-document-history/', "UserCompanyDocuments@getDocumentHistory");
});

