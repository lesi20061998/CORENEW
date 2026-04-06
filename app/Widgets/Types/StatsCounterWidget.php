<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class StatsCounterWidget extends BaseWidget
{
    public static string $label       = 'Stats Counter';
    public static string $description = 'Hiển thị các con số thống kê (60M+, 100+...)';
    public static string $icon        = 'fa-solid fa-calculator';

    public static function fields(): array
    {
        return [
            ['key' => 'items', 'label' => 'Các con số', 'type' => 'repeater',
                'fields' => [
                    ['key' => 'number', 'label' => 'Số', 'type' => 'text', 'placeholder' => '60', 'col' => 'col-md-3'],
                    ['key' => 'suffix', 'label' => 'Hậu tố', 'type' => 'text', 'placeholder' => 'M+', 'col' => 'col-md-2'],
                    ['key' => 'label', 'label' => 'Nhãn', 'type' => 'text', 'placeholder' => 'Happy Customers', 'col' => 'col-md-7'],
                ]
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.stats_counter', compact('config', 'widget'));
    }
}
