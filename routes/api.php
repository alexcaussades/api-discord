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
});

Route::prefix('serveur-fs25')->group(function () {

    Route::get('/stats', [
        \App\Http\Controllers\ServeurFs25::class,
        'getStats'
    ]);

    Route::get('/ping', [
        \App\Http\Controllers\ServeurFs25::class,
        'ping'
    ]);

    Route::get('/status', [
        \App\Http\Controllers\ServeurFs25::class,
        'getServerStatus'
    ]);

    Route::get('/mods', [
        \App\Http\Controllers\ServeurFs25::class,
        'getMods'
    ]);

    Route::get('/player', [
        \App\Http\Controllers\ServeurFs25::class,
        'getPlayer'
    ]);

    Route::get('/uptime', [
        \App\Http\Controllers\ServeurFs25::class,
        'getUptime'
    ]);

    Route::get('/buy', [
        \App\Http\Controllers\ServeurFs25::class,
        'buy'
    ]);
});
