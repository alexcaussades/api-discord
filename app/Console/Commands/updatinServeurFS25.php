<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $url = config('services.serveur_fs25.url');

        try {
            $reponse = Http::timeout(30)->get($url);
            if ($reponse->successful()) {
                $this->info('Successfully updated serveur FS25.');
                return self::SUCCESS;
            } else {
                $this->error('Failed to update serveur FS25: ' . $reponse->status());
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Failed to update serveur FS25: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
