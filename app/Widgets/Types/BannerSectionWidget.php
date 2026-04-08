<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class BannerSectionWidget extends BaseWidget
{
    public static string $label = 'Section Banner  ';
    public static string $description = 'Hiển thị banner đơn lẻ, dạng lưới hoặc slider.';
    public static string $icon = 'fa-solid fa-panorama';

    public static function fields(): array
    {
        return [
            [
                'key' => 'layout',
                'label' => 'Kiểu hiển thị',
                'type' => 'select',
                'options' => [
                    'slider' => 'Slider (Hero Style 1)',
                    'hero_2' => 'Hero Split (Style 2)',
                    'hero_3' => 'Hero Centered (Style 3)',
                    'grid' => 'Lưới banner (Promo Grid)',
                    'single' => 'Banner đơn lẻ',
                ],
                'default' => 'slider'
            ],
            [
                'key' => 'columns',
                'label' => 'Số cột (Dành cho kiểu Lưới)',
                'type' => 'select',
                'options' => ['2' => '2 cột', '3' => '3 cột', '4' => '4 cột'],
                'default' => '4'
            ],
            [
                'key' => 'slides',
                'label' => 'Danh sách Banner/Slide',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text'],
                    ['key' => 'subtitle', 'label' => 'Phụ đề (hoặc Pre-title)', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'badge', 'label' => 'Badge/Label', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'image', 'label' => 'Ảnh nền', 'type' => 'image'],
                    ['key' => 'btn_text', 'label' => 'Text nút', 'type' => 'text', 'default' => 'Mua ngay', 'col' => 'col-md-6'],
                    ['key' => 'btn_link', 'label' => 'Link nút', 'type' => 'text', 'default' => '/shop', 'col' => 'col-md-6'],
                    ['key' => 'price', 'label' => 'Giá hiển thị', 'type' => 'text', 'col' => 'col-md-6'],
                ]
            ],
            ['key' => 'autoplay_delay', 'label' => 'Tốc độ slider (ms)', 'type' => 'number', 'default' => 4000],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        // Support both old 'slides' (Hero) and legacy 'items' (Promo)
        $slides = $config['slides'] ?? ($config['items'] ?? []);

        // Normalize subfields: subtitle/pre_title
        foreach ($slides as &$s) {
            $s['subtitle'] = $s['subtitle'] ?? ($s['pre_title'] ?? ($s['badge'] ?? ''));
        }

        $layout = $config['layout'] ?? match ($widget->type) {
            'promo_banners' => 'grid',
            'hero_main' => 'slider',
            default => 'slider'
        };

        return static::view('widgets.types.banner_section', [
            'config' => array_merge($config, ['layout' => $layout]),
            'widget' => $widget,
            'slides' => $slides,
        ]);
    }
}
