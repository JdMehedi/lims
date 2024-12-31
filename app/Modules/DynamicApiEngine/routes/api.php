<?php
use App\Modules\DynamicApiEngine\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['module' => 'DynamicApiEngine', 'middleware' => ['api']], function() {

    Route::resource('DynamicApiEngine', 'DynamicApiEngineController');

});
