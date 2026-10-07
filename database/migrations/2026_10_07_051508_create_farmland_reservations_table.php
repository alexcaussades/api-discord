<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmland_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farmland_id')
                ->constrained('farmlands')
                ->cascadeOnDelete();

            $table->foreignId('player_id')
                ->constrained('players')
                ->cascadeOnDelete();

            $table->timestamp('reserved_at');

            $table->timestamp('released_at')->nullable();

            // active / released
            $table->string('status')->default('active');

            $table->timestamps();

            $table->index([
                'farmland_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmland_reservations');
    }
};