<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_serveurs', function (Blueprint $table) {
            $table->id();

            $table->string('player_id');
            $table->foreign('player_id')
                ->references('discord_id')
                ->on('players')
                ->cascadeOnDelete();

            $table->foreignId('farmland_id')
                ->constrained('farmlands')
                ->cascadeOnDelete();
            
            $table->text('task_name');
            $table->string('month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_serveurs');
    }
};
