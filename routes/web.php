<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiEDController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'returnAppiUrl']);

Route::prefix('api-ED')->group(function () {

    Route::get('/', [HomeController::class, 'index']);

    Route::get('/status', [HomeController::class, 'status']);

    Route::get('/action/{id}/{info}', [ApiEDController::class, 'actionWithInfo']);

    Route::get('/action/{id}/{galaxie}', [ApiEDController::class, 'action']);
});

Route::prefix('api-FS')->group(function () {

});