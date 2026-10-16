<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDatabasefromserveur extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [];

        for ($i = 0; $i < 10; $i++) {
            $rows[] = [
                'status' => random_int(1, 4) === 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('update_serveur')->insert($rows);
    }
}
