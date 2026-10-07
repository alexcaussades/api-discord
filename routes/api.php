<?php

use App\Http\Controllers\Api\FarmlandController;
use Illuminate\Support\Facades\Route;

Route::prefix('farmlands')->group(function () {

    Route::get('/', [
        FarmlandController::class,
        'index'
    ]);

    Route::get('/{farmland}', [
        FarmlandController::class,
        'show'
    ]);

    Route::post('/{farmland}/reserve', [
        FarmlandController::class,
        'reserve'
    ]);

    Route::delete('/{farmland}/reserve', [
        FarmlandController::class,
        'release'
    ]);
});