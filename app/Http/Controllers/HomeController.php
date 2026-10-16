<?php

namespace App\Http\Controllers;
use App\Http\Controllers\GithubController;

class HomeController extends Controller
{
    public function returnAppiUrl()
    {
        return redirect(url('/api-elite'));
    }
   
    public function index()
    {
        return response()->json([
            'title' => "GameHub (API) - V:" . (app(GithubController::class)->release_last() ?? '1.0.0'),
            'version' => app(GithubController::class)->release_last() ?? '1.0.0',
            'description' => 'API REST pour GameHub, la gestion des parcelles et le serveur Farming Simulator 25, et l\'intégration avec Discord.',
            'documentation' => 'https://alexcaussades.freeboxos.fr/api_perso/endepoint',
            'repository' => 'https://github.com/alexcaussades/api-discord',
            'resources' => [
                'farmlands' => '/api/farmlands',
                'farming_simulator_server' => '/api/serveur-fs25',
            ],
        ]);
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
