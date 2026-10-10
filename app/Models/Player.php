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

    public function activeReservations(): HasMany
    {
        return $this->hasMany(FarmlandReservation::class)
            ->where('status', 'active');
    }

    public function releasedReservations(): HasMany
    {
        return $this->hasMany(FarmlandReservation::class)
            ->where('status', 'released');
    }

    public function allActiveAndReleasedReservations(): HasMany
    {
        return $this->hasMany(FarmlandReservation::class)
            ->whereIn('status', ['active', 'released']);
    }

    public function allReservations(): HasMany
    {
        return $this->hasMany(FarmlandReservation::class);
    }

    public function getuserId(): int
    {
        return $this->discord_id;
    }

    public function check_user_id(int $discord_id): bool
    {
        return $this->discord_id === $discord_id;
    }
}