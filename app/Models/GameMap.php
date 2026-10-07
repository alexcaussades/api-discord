<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMap extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'game',
    ];

    public function farmlands(): HasMany
    {
        return $this->hasMany(Farmland::class);
    }
}