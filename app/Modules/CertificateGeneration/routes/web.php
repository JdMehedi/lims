<?php

use Illuminate\Support\Facades\Route;


Route::group(['module' => 'CertificateGeneration', 'middleware' => ['web']], function() {

    Route::get('certificate-generate/{cron_id?}', "CertificateGenerationController@generateCertificate");

});
