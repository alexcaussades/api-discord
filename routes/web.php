<?php


use App\Http\Controllers\ApiEDController;
use App\Http\Controllers\DiscordServeurController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index']);
Route::get('/endepoint', [HomeController::class, 'endepoint']);

Route::prefix('api-elite')->group(function () {

    Route::get('/', [HomeController::class, 'index']);

    Route::get('/status', [HomeController::class, 'status']);

    Route::get('/action/{id}/{info}', [ApiEDController::class, 'actionWithInfo']);

    Route::get('/action/{id}/{galaxie}/{id_discord}', [ApiEDController::class, 'action']);
});



Route::prefix('discord')->group(function () {
    Route::get('/discord-serveur', [DiscordServeurController::class, 'getDiscordServeur']);
    Route::get('/discord-serveur-invite', [DiscordServeurController::class, 'getDiscordServeurInvite']);
    Route::get('/discord-serveur-widget', [DiscordServeurController::class, 'getDiscordServeurWidget']);
    Route::get('/discord-serveur-de-test', [DiscordServeurController::class, 'getDiscordServeurDeTest']);
    Route::get('/presence', [DiscordServeurController::class, 'getpresence']);
}); 