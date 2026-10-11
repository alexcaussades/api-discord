<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EndepointController extends Controller
{
    public function Index()
    {
        return response()->json([
            'message' => "Private API from AlexCaussades",
            'status' => 'online',
            'version' => app(GithubController::class)->release_last() ?? '1.0.0',
            'open_issues' => app(GithubController::class)->open_issues() ?? '--',
            'github_url_api_discord' => "https://github.com/alexcaussades/api-discord",
            'github_url_api_bot_discord' => "https://github.com/alexcaussades/FarmingBot",
            'auth' => [
                'required' => 'Authentication is required for certain endpoints.',
                'false' => 'No authentication is required for certain endpoints.'
            ],
            'scope' => [
                "public" => "Indicates that the endpoint is publicly accessible.",
                "private" => "Indicates that the endpoint requires specific permissions.",
            ],
            'Secure_users' => [
                'discord_id' => 'The unique identifier for the Discord user.'
            ],
            'Farming_url_api' => [
                'start' => [
                'url' => url('/api/farmlands'),
                'method' => 'GET',
                'auth' => 'false'
                ],
                'check' => [
                    'url' => url('/api/farmlands/{id}/check'),
                    'method' => 'GET',
                    'auth' => 'false'
                ],
                'reserve' => [
                    'url' => url('/api/farmlands/{id}/reserve'),
                    'method' => 'POST',
                    'auth' => 'required',
                    'scope' => 'private'
                ],
                'cancel' => [
                    'url' => url('/api/farmlands/{id}/cancel'),
                    'method' => 'DELETE',
                    'auth' => 'required',
                    'scope' => 'private'
                ]

            ],
            'serveur-fs25' => [
                'start' => [
                    'url' => url('/api/serveur-fs25'),
                    'method' => 'GET',
                    'auth' => 'false'
                ]
                ,
                'ping' => [
                    'url' => url('/api/serveur-fs25/ping'),
                    'method' => 'GET',
                    'auth' => 'false'
                ],
                'status' => [
                    'url' => url('/api/serveur-fs25/status'),
                    'method' => 'GET',
                    'auth' => 'false'
                ],
                'mods' => [
                    'url' => url('/api/serveur-fs25/mods'),
                    'method' => 'GET',
                    'auth' => 'false'
                ],
                'players' => [
                    'url' => url('/api/serveur-fs25/players'),
                    'method' => 'GET',
                    'auth' => 'false'
                ],
                'stats' => [
                    'url' => url('/api/serveur-fs25/stats'),
                    'method' => 'GET',
                    'auth' => 'false'
                ],
                'uptime' => [
                    'url' => url('/api/serveur-fs25/uptime'),
                    'method' => 'GET',
                    'auth' => 'false'
                ],
                'buy' => [
                    'url' => url('/api/serveur-fs25/buy'),
                    'method' => 'GET',
                    'auth' => 'false',
                ]                
            ]
        ],headers: ['Content-Type' => 'application/json'],);
    }
}

