<?php

namespace App\Widgets;

use App\Models\Widget as WidgetModel;

/**
 * Base class cho tất cả Widget.
 *
 * Để tạo widget mới:
 *   php artisan widget:make TenWidget
 *
 * Sau đó đăng ký trong WidgetRegistry::areas() hoặc tự động qua auto-discovery.
 */
abstract class BaseWidget
{
    /** Tên hiển thị trong admin */
    public static string $label = 'Widget';

    /** Mô tả ngắn */
    public static string $description = '';

    /** Icon FontAwesome (vd: fa-solid fa-image) */
    public static string $icon = 'fa-solid fa-puzzle-piece';

    /**
     * Định nghĩa các fields config của widget.
     * Mỗi field: ['key', 'label', 'type', 'default'?, 'placeholder'?, 'options'?]
     * Types: text | textarea | image | number | select | repeater | html | toggle
     */
    public static function fields(): array
    {
        return [];
    }

    /**
     * Render widget ra HTML.
     * $config = mảng dữ liệu đã lưu từ admin
     * $widget = Widget model instance
     */
    abstract public static function render(array $config, WidgetModel $widget): string;

    /**
     * Helper: lấy giá trị field với fallback default
     */
    protected static function get(array $config, string $key, mixed $default = null): mixed
    {
        return $config[$key] ?? $default;
    }

    /**
     * Helper: render blade view cho widget
     */
    protected static function view(string $view, array $data = []): string
    {
        return view($view, $data)->render();
    }
}
