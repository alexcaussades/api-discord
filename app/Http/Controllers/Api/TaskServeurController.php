<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskServeurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'data' => \App\Models\TaskServeur::all(),
            'count' => \App\Models\TaskServeur::count(),
        ];
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'player_id' => ['required', 'exists:players,discord_id'],
            'farmland_id' => ['required', 'exists:farmlands,farmland_id'],
            'task_name' => ['required', 'string', 'max:255'],
            'month' => [
                'required',
                'string',
                'regex:/^(?:[1-9]|1[0-2]|janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)$/iu',
            ],
        ]);

        $months = [
            1 => 'janvier', 2 => 'février', 3 => 'mars',
            4 => 'avril', 5 => 'mai', 6 => 'juin',
            7 => 'juillet', 8 => 'août', 9 => 'septembre',
            10 => 'octobre', 11 => 'novembre', 12 => 'décembre',
        ];

        $monthInput = $data['month'];
        $data['month'] = is_numeric($monthInput)
            ? $months[(int) $monthInput]
            : mb_strtolower($monthInput, 'UTF-8');

        $data = [
            'player_id' => $data['player_id'],
            'farmland_id' => $data['farmland_id'],
            'task_name' => $data['task_name'],
            'month' => $data['month'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $taskServeur = DB::table('task_serveurs')->insert($data);

        return response()->json($taskServeur, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $taskServeur = \App\Models\TaskServeur::findOrFail($id);
        return response()->json($taskServeur);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $taskServeur = \App\Models\TaskServeur::findOrFail($id);
        $taskServeur->update($request->all());
        return response()->json($taskServeur);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $taskServeur = \App\Models\TaskServeur::findOrFail($id);
        $taskServeur->delete();
        return response()->json(null, 204);
    }

    public function getTasksByMonth(Request $request, string $month)
    {      
        
        $month = is_numeric($month)
            ? (int) $month
            : mb_strtolower($month, 'UTF-8');
        $months = [
            1 => 'janvier',
            2 => 'février',
            3 => 'mars',
            4 => 'avril',
            5 => 'mai',
            6 => 'juin',
            7 => 'juillet',
            8 => 'août',
            9 => 'septembre',
            10 => 'octobre',
            11 => 'novembre',
            12 => 'décembre',
        ];

        if (is_numeric($month)) {
            $month = $months[(int) $month];
        }
        // table join to get player information for the tasks

        $tasks = DB::table('task_serveurs')->join('players', 'task_serveurs.player_id', '=', 'players.discord_id')->where('month', $month)->get();
        
        return response()->json([
            'data' => $tasks,
            'count' => count($tasks)
        ]);
    }

    public function getTasksByPlayer(Request $request, string $playerId)
    {
        $tasks = \App\Models\TaskServeur::where('player_id', $playerId)->get();
        return response()->json($tasks);
    }

    // public function test()
    // {
    //     $data = [
    //         'player_id' => "1145762103655936122",
    //         'farmland_id' => "85",
    //         'task_name' => 'Action de nettoyage',
    //         'month' => '12',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ];
    //     $response = $this->store(new Request($data));
    //     return $response;
    // }

}
