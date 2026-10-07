<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farmland extends Model
{
    protected $fillable = [
        'game_map_id',
        'farmland_id',
        'name',
        'area',
        'price',
        'status',
        'polygon',
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'price' => 'decimal:2',
        'polygon' => 'array',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(GameMap::class, 'game_map_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(FarmlandReservation::class);
    }

    public function activeReservation()
    {
        return $this->hasOne(FarmlandReservation::class)
            ->where('status', 'active');
    }
}