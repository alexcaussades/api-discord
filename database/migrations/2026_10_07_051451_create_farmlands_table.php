<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmlands', function (Blueprint $table) {
            $table->id();

            // Identification de la carte / parcelle
            $table->unsignedBigInteger('game_map_id')->default(1);
            $table->unsignedBigInteger('farmland_id');

            // Informations gÃ©nÃ©rales
            $table->string('name');
            $table->decimal('area', 10, 4)->default(0);
            $table->decimal('price', 12, 2)->default(0);

            // Informations PNJ / propriÃ©taire
            $table->string('npc_name')->nullable();

            // ParamÃ¨tres Ã©conomiques
            $table->decimal('price_scale', 10, 6)->default(1);
            $table->decimal('price_per_ha', 12, 2)->default(60000);

            // Type de terrain
            $table->string('field_type')->nullable();

            // Ã‰tat du terrain
            $table->boolean('cultivable')->default(true);
            $table->boolean('reservable')->default(true);
            $table->boolean('default_farm_property')->default(false);

            // GÃ©omÃ©trie du terrain
            $table->json('polygon')->nullable();

            // DisponibilitÃ©
            $table->string('status')->default('free');

            $table->timestamps();

            // Un farmland_id doit Ãªtre unique Ã  l'intÃ©rieur d'une carte
            $table->unique(
                ['game_map_id', 'farmland_id'],
                'farmlands_game_map_farmland_unique'
            );

            $table->index('game_map_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmlands');
    }
};
