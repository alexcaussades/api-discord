<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = [
        'name',
        'discord_id',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(FarmlandReservation::class);
    }
}