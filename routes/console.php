<?php

use App\Console\Commands\PaymentCheckingCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(PaymentCheckingCommand::class)->everyFifteenMinutes()
    ->description('Membatalkan pembayaran yang belum dibayar 1 jam sebelum waktu pertandingan')
    ->withoutOverlapping()
    ->onFailure(function () {
        // Log the failure or send a notification
        \Log::error('Payment checking command failed.');
    });
