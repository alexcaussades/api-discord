<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
#[Signature('app:updatin-serveur-f-s25')]
#[Description('Command description')]


class updatinServeurFS25 extends Command
{
    /**
     * Execute the console command.
     */

    protected $signature = 'app:updatin-serveur-f-s25';
    protected $description = 'Command description';

    public function handle()
    {
        $url = config('services.fs25.url');

        try {
            $reponse = Http::timeout(30)->get($url);
            if ($reponse->successful()) {
                $this->info('Successfully updated serveur FS25.');
                Log::info('Successfully updated serveur FS25.');
                DB::table('update_serveur')->insert([
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return self::SUCCESS;
            } else {
                $this->error('Failed to update serveur FS25: ' . $reponse->status());
                Log::error('Failed to update serveur FS25: ' . $reponse->status());
                DB::table('update_serveur')->insert([
                    'status' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            Log::error('Failed to update serveur FS25: ' . $e->getMessage());
            $this->error('Failed to update serveur FS25: ' . $e->getMessage());
            DB::table('update_serveur')->insert([
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return self::FAILURE;
        }
    }
}
