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
        return response()->json(\App\Models\TaskServeur::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'player_id' => ['required', 'exists:players,id'],
            'farmland_id' => ['required', 'exists:farmlands,id'],
            'task_name' => ['required', 'string', 'max:255'],
            'month' => ['required', 'string', 'regex:/^(?:[1-9]|1[0-2]|janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)$/iu'],
        ]);

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

        $monthInput = $data['month'];

        $month = is_numeric($monthInput)
            ? $months[(int) $monthInput]
            : mb_strtolower($monthInput, 'UTF-8');
        
        //insert a new task
        $taskServeur = DB::table('task_serveurs')->insertGetId([
            'player_id' => $data['player_id'],
            'farmland_id' => $data['farmland_id'],
            'task_name' => $data['task_name'],
            'month' => $month,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $taskServeur = \App\Models\TaskServeur::findOrFail($taskServeur);
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
        $request->validate([
            'month' => ['required', 'string', 'regex:/^(?:[1-9]|1[0-2]|janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)$/iu'],
        ]);
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

        $tasks = \App\Models\TaskServeur::where('month', $month)->get();
        return response()->json($tasks);
    }

    public function getTasksByPlayer(Request $request, string $playerId)
    {
        $tasks = \App\Models\TaskServeur::where('player_id', $playerId)->get();
        return response()->json($tasks);
    }

    
}
