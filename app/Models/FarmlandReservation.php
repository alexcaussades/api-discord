<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmlandReservation extends Model
{
    protected $fillable = [
        'farmland_id',
        'player_id',
        'reserved_at',
        'released_at',
        'status',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function farmland(): BelongsTo
    {
        return $this->belongsTo(Farmland::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}