<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Pastikan teks tanggal Carbon mengikuti bahasa aktif.
        \Illuminate\Support\Carbon::setLocale(config('app.locale'));
    }
}
