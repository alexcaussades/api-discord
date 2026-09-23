<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {

    Route::get('/', function () {
        return response()->json(['message' => "API Elite Dangerous"]);
    });

    Route::get('/status', function () {
        /** dans resources/json/app.json on peut définir la version de l'application */
        $appVersion = json_decode(file_get_contents(resource_path('json/app.json')), true)['APP_version'] ?? '1.0.0';
        $status = ['status' => 'Actuellement en ligne', 'version' => $appVersion];
        return response()->json($status);
    });
});