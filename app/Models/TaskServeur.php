<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Player::class);
    }

    public function farmland()
    {
        return $this->belongsTo(Farmland::class);
    }

    public function insertTask(array $data)
    {
        return self::create($data);
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
        return self::where('month', $month)->get();
    }

}
