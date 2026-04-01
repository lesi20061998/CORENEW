<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

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

        // Tự động xóa cache Admin khi có thay đổi dữ liệu
        $clearAdminCache = function() {
            Cache::forever('admin_cache_version', time());
        };

        // Danh sách các Model cần theo dõi
        $monitoredModels = [
            \App\Models\Setting::class,
            \App\Models\Product::class,
            \App\Models\Category::class,
            \App\Models\Post::class,
            \App\Models\Page::class,
            \App\Models\Order::class,
            \App\Models\Widget::class,
            \App\Models\FlashSaleCampaign::class,
        ];

        foreach ($monitoredModels as $modelClass) {
            $modelClass::saved($clearAdminCache);
            $modelClass::deleted($clearAdminCache);
        }
    }
}
