<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once app_path('Helpers/SettingHelper.php');
    }

    public function boot(): void
    {
        // @widgetArea('homepage') — render tất cả widgets trong area
        Blade::directive('widgetArea', function ($area) {
            return "<?php echo app(\App\Services\WidgetService::class)->renderArea($area); ?>";
        });

        // Backward compat: @widgets('homepage')
        Blade::directive('widgets', function ($area) {
            return "<?php echo app(\App\Services\WidgetService::class)->renderArea($area); ?>";
        });
    }
}
