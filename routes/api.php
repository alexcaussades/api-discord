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

    Route::get('/{farmland}/check', [
        FarmlandController::class,
        'check'
    ]);

    Route::post('/{farmland}/reserve/', [
        FarmlandController::class,
        'reserve'
    ]);

    Route::delete('/{farmland}/reserve', [
        FarmlandController::class,
        'release'
    ]);

    Route::get('/serveur-fs25/stats', [
        \App\Http\Controllers\ServeurFs25::class,
        'getStats'
    ]);

    Route::get('/serveur-fs25/ping', [
        \App\Http\Controllers\ServeurFs25::class,
        'ping'
    ]);

    Route::get('/serveur-fs25/status', [
        \App\Http\Controllers\ServeurFs25::class,
        'getServerStatus'
    ]);

    Route::get('/serveur-fs25/mods', [
        \App\Http\Controllers\ServeurFs25::class,
        'getMods'
    ]);

    Route::get('/serveur-fs25/player', [
        \App\Http\Controllers\ServeurFs25::class,
        'getPlayer'
    ]);
});