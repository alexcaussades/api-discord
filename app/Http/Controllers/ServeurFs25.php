<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GithubController;
class ServeurFs25 extends Controller
{
    // recuperer les statistiques du serveur FS25 le liens se trouve dans la variable d'environnement SERVEUR_FS25
    public function getStats()
    {
        $url = env('SERVEUR_FS25');
        $response = file_get_contents($url);

        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, true);

        return response()->json($data, 200);

    }

    // ajouter d'autres méthodes pour interagir avec le serveur FS25 si nécessaire

    public function ping()
    {
        $url = env('SERVEUR_FS25');
        $headers = @get_headers($url);
        if($headers && strpos($headers[0], '200')) {
            return response()->json(['status' => 'online'], 200);
        } else {
            return response()->json(['status' => 'offline'], 503);
        }
    }

    // ajouter d'autres méthodes pour interagir avec le serveur FS25 si nécessaire

    public function getServerStatus()
    {
        $url = env('SERVEUR_FS25');
        $headers = @get_headers($url);
        if($headers && strpos($headers[0], '200')) {
            return response()->json(['status' => 'online'], 200);
        } else {
            return response()->json(['status' => 'offline'], 503);
        }
    }

    public function getMods()
    {
        $url = env('SERVEUR_FS25');
        $response = file_get_contents($url);

        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, true);

        if(isset($data['Mods'])) {
            return response()->json($data['Mods'], 200);
        } else {
            return response()->json(['Mods' => []], 200);
        }
    }

    public function getPlayer()
    {
        $url = env('SERVEUR_FS25');
        $response = file_get_contents($url);

        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, true);

        if(isset($data['Slots']['@attributes']['numUsed'])) {
            return response()->json(['count' => (int)$data['Slots']['@attributes']['numUsed']], 200);
        } else {
            return response()->json(['count' => 0], 200);
        }
    }

    public function getUptime()
    {
        $url = env('SERVEUR_FS25');
        $response = file_get_contents($url);

        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $data = json_decode($json, true);
               
        if(isset($data['@attributes']['dayTime'])) {
            $time = CarbonInterval::milliseconds($data['@attributes']['dayTime'])->cascade();
            return response()->json(
                [
                    'Data' => [
                        'Server' => [
                            'uptime' => $time->format('%H:%I:%S'),
                            'name_serveur' => $data['@attributes']['name'] ?? 'Not Available',
                            'service' => 'online',
                            'Version' => $data['@attributes']['version'] ?? 0,
                            'players' => (int)($data['Slots']['@attributes']['numUsed'] ?? 0),
                            'Mods' => count($data['Mods']['Mod']),
                        ],
                        'Api' => [
                            'status' => 'online',
                            'version' => app(GithubController::class)->release_last() ?? '1.0.0',
                            'open_issues' => app(GithubController::class)->open_issues() ?? '--',
                        ]
                    ],                
                ], 200);
        } else {
            return response()->json(['uptime' => "Server offline"], 200);
        }
    }

    public function buy()
    {
        $response = file_get_contents(env('SERVEUR_FS25'));
        $data = json_decode(json_encode(
            simplexml_load_string($response)
        ), true);

        $updated = 0;

        DB::transaction(function () use ($data, &$updated) {
            foreach (data_get($data, 'Farmlands.Farmland', []) as $f) {
                $a = $f['@attributes'] ?? $f;
                $id = (int) $a['id'];
                $owner = (int) $a['owner'];

                if ($owner === 0) continue;

                DB::table('farmland_reservations')
                    ->where('farmland_id', $id)
                    ->delete();

                $updated += DB::table('farmlands')
                    ->where('id', $id)
                    ->update(['status' => 'buy']);
            }
        });

    return response()->json(['updated' => $updated]);
    }
}
