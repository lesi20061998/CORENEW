<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class FeaturesBarWidget extends BaseWidget
{
    public static string $label       = 'Thanh Tính năng';
    public static string $description = 'Hiển thị các icon cam kết dịch vụ (Giao hàng, Bảo hành...)';
    public static string $icon        = 'fa-solid fa-star';

    public static function fields(): array
    {
        return [
            ['key' => 'items', 'label' => 'Danh sách Tính năng', 'type' => 'repeater',
                'default' => [
                    ['icon' => 'fa-solid fa-dollar-sign',   'title' => 'Wide Assortment',    'sub' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-rotate-left',   'title' => 'Easy Return Policy', 'sub' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-tag',           'title' => 'Best Prices & Offers','sub' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-headset',       'title' => 'Support 24/7',        'sub' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Best Prices & Offers','sub' => 'Orders $50 or more'],
                ],
                'fields' => [
                    ['key' => 'icon',  'label' => 'FontAwesome Icon', 'type' => 'text', 'placeholder' => 'fa-truck-fast', 'col' => 'col-md-4'],
                    ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text', 'col' => 'col-md-8'],
                    ['key' => 'sub',   'label' => 'Mô tả ngắn', 'type' => 'text'],
                ]
            ],
            ['key' => 'grid_class', 'label' => 'CSS Class số cột', 'type' => 'text', 'default' => 'col-xl-20 col-lg-6 col-md-6 col-sm-6 col-12'],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.feature_icons', [
            'config' => $config,
            'widget' => $widget,
            'features' => $config['items'] ?? []
        ]);
    }
}
