<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farmlands', function (Blueprint $table) {
            $table->string('npc_name')->nullable();
            $table->decimal('price_scale', 10, 6)->default(1);
            $table->decimal('price_per_ha', 12, 2)->default(60000);
            $table->string('field_type')->nullable();
            $table->boolean('cultivable')->default(true);
            $table->boolean('reservable')->default(true);
            $table->boolean('default_farm_property')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('farmlands', function (Blueprint $table) {
            $table->dropColumn([
                'npc_name',
                'price_scale',
                'price_per_ha',
                'field_type',
                'cultivable',
                'reservable',
                'default_farm_property',
            ]);
        });
    }
};