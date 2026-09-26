<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiEDController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index']);

Route::prefix('api-elite')->group(function () {

    Route::get('/', [HomeController::class, 'index']);

    Route::get('/status', [HomeController::class, 'status']);

    Route::get('/action/{id}/{info}', [ApiEDController::class, 'actionWithInfo']);

    Route::get('/action/{id}/{galaxie}/{id_discord}', [ApiEDController::class, 'action']);
});

Route::prefix('api-farming')->group(function () {

});