<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\Http\Controllers\PackageController;
use Modules\Package\Http\Controllers\SubpackageController;
use Modules\Package\Http\Controllers\SubpackageSportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Packages route
Route::get('/packages', [PackageController::class, 'index']);
Route::post('/subpackages/store-subpackage', [SubpackageController::class, 'storeSubpackage']);


// Authenthicated routes
Route::middleware('auth:api')->group(function () {

    Route::get('/own-packages', [PackageController::class, 'ownIndex']);
    
    Route::get('/subpackages/{id}', [SubpackageController::class, 'show']);

    Route::get('/subpackages/{id}/sports', [SubpackageSportController::class, 'index']);

    Route::get('/packages/subpackages/detail', [PackageController::class, 'showDetailPackage']);


});
