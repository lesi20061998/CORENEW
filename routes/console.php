<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Đăng ký widget:make command
\Illuminate\Console\Application::starting(function ($artisan) {
    $artisan->resolveCommands([
        \App\Console\Commands\MakeWidget::class,
    ]);
});
