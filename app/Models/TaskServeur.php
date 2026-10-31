<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskServeur extends Model
{
    protected $fillable = [
        'player_id',
        'farmland_id',
        'task_name',
        'month'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'discord_id');
    }

    public function farmland()
    {
        return $this->belongsTo(Farmland::class, 'farmland_id', 'farmland_id');
    }

    public static function createTask(array $data)
    {
        DB::table('task_serveurs')->insert($data);
    }

    public function updateTask(array $data)
    {
        return $this->update($data);
    }

    public function deleteTask()
    {
        return $this->delete();
    }


    public function getAllTasks()
    {
        return self::all();
    }

    public function getTaskById(string $id)
    {
        return self::findOrFail($id);
    }

    public function getTasksByPlayerId(string $playerId)
    {
        return self::where('player_id', $playerId)->get();
    }

    public function getTasksByFarmlandId(string $farmlandId)
    {
        return self::where('farmland_id', $farmlandId)->get();
    }

    public function getTasksByMonth(string $month)
    {
        $data = DB::table('task_serveurs')->where('month', $month)->get();
        return $data;
    }

    public function taskByNotPrivate()
    {
        $data = DB::table('task_serveurs')->where('private', false)->get();
        return $data;
    }
}
