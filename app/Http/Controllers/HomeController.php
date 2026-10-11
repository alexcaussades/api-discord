<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{
    public function returnAppiUrl()
    {
        return redirect(url('/api-elite'));
    }
   
    public function index()
    {
        return response()->json(['message' => "Welcome to the API Elite Dangerous private from CMD alexcaussades"]);
    }

    public function status()
    {
        $appVersion = json_decode(file_get_contents(resource_path('json/app.json')), true)['APP_version'] ?? '1.0.0';
        return response()->json(['status' => 'Actuellement en ligne', 'version' => $appVersion]);
    }

    public function endepoint()
    {
        return app(EndepointController::class)->Index();
    }
}
