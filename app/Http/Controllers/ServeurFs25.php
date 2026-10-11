<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
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

        $date = new DateTime();
        $date->modify('+47486.321 seconds');
        
        if(isset($data['dayTime'])) {
            $time = DateTime::createFromFormat('H:i:s', $data['dayTime']);
            return response()->json(['uptime' => $time->format('H:i:s')], 200);
        } else {
            return response()->json(['uptime' => 0], 200);
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
