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
     * Common fields for ALL widgets (Margin, Padding, Background, Box layout, Title settings)
     */
    public static function commonFields(): array
    {
        return [
            ['key' => 'common_tab_start', 'label' => 'Cấu hình Layout & Nền', 'type' => 'tab_start'],
            
            // Section Title Settings
            ['key' => 'title', 'label' => 'Tiêu đề Section', 'type' => 'text', 'default' => ''],
            ['key' => 'title_align', 'label' => 'Căn lề tiêu đề', 'type' => 'alignment', 'default' => 'left'],
            ['key' => 'show_title', 'label' => 'Hiển thị tiêu đề', 'type' => 'toggle', 'default' => true],
            
            // Background Settings
            ['key' => 'bg_type', 'label' => 'Loại nền', 'type' => 'select', 'default' => 'none', 'options' => [
                'none' => 'Không nền',
                'color' => 'Màu sắc',
                'image' => 'Hình ảnh',
                'video' => 'Video'
            ]],
            ['key' => 'bg_color', 'label' => 'Màu nền', 'type' => 'color', 'default' => 'transparent'],
            ['key' => 'bg_image', 'label' => 'Ảnh nền URL', 'type' => 'image', 'default' => ''],
            
            // Layout Settings
            ['key' => 'box_align', 'label' => 'Căn lề (Box Alignment)', 'type' => 'alignment', 'default' => 'center'],
            ['key' => 'box_model', 'label' => 'Margin & Padding', 'type' => 'box_model', 'default' => [
                'margin_top' => '', 'margin_bottom' => '', 'margin_left' => '', 'margin_right' => '',
                'padding_top' => '', 'padding_bottom' => '', 'padding_left' => '', 'padding_right' => '',
            ]],

            ['key' => 'common_tab_end', 'type' => 'tab_end'],
        ];
    }

    /**
     * Render widget ra HTML.
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
        // Tự động inject inline styles cho Section
        if (isset($data['config'])) {
            $data['sectionStyles'] = static::getStyles($data['config']);
        }
        
        return view($view, $data)->render();
    }

    /**
     * Tạo chuỗi inline style cho Section dựa trên config
     */
    public static function getStyles(array $config): string
    {
        $styles = [];
        
        // Background
        if (!empty($config['bg_color']) && $config['bg_color'] !== 'transparent') {
            $styles[] = "background-color: {$config['bg_color']} !important";
        }
        
        if (!empty($config['bg_image'])) {
            $styles[] = "background-image: url('{$config['bg_image']}') !important";
            $styles[] = "background-size: cover !important";
            $styles[] = "background-position: center !important";
            $styles[] = "background-repeat: no-repeat !important";
        }

        // Box Model (Margin & Padding)
        $bm = $config['box_model'] ?? [];
        $sides = ['top', 'right', 'bottom', 'left'];
        
        foreach($sides as $side) {
            if (isset($bm["margin_{$side}"]) && $bm["margin_{$side}"] !== '') {
                $val = (int)$bm["margin_{$side}"];
                $styles[] = "margin-{$side}: {$val}px !important";
            }
            if (isset($bm["padding_{$side}"]) && $bm["padding_{$side}"] !== '') {
                $val = (int)$bm["padding_{$side}"];
                $styles[] = "padding-{$side}: {$val}px !important";
            }
        }

        // Alignment
        $align = $config['box_align'] ?? '';
        if ($align === 'left') $styles[] = "text-align: left !important";
        elseif ($align === 'right') $styles[] = "text-align: right !important";
        elseif ($align === 'center') $styles[] = "text-align: center !important";

        return !empty($styles) ? 'style="' . implode('; ', $styles) . '"' : '';
    }
}
