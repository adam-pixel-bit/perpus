<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal otomatis kalkulasi denda & pengingat setiap hari jam 00:00 tengah malam
Schedule::command('perpus:calculate-fines')->dailyAt('00:00');
