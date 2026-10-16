<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('app:updatin-serveur-f-s25')
    ->hourly()
    ->withoutOverlapping();

Schedule::command('app:sauvegarder-base')
    ->dailyAt('02:00')
    ->withoutOverlapping();