<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class AboutIntroWidget extends BaseWidget
{
    public static string $label       = 'About Intro';
    public static string $description = 'Phần giới thiệu chi tiết với ảnh một bên và nội dung một bên kèm các dòng cam kết.';
    public static string $icon        = 'fa-solid fa-address-card';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text', 'default' => ''],
            ['key' => 'image', 'label' => 'Hình ảnh bên trái', 'type' => 'image', 'default' => ''],
            ['key' => 'content', 'label' => 'Nội dung chi tiết', 'type' => 'textarea', 'default' => ''],
            ['key' => 'checks', 'label' => 'Các dòng cam kết', 'type' => 'repeater',
                'fields' => [
                    ['key' => 'text', 'label' => 'Text', 'type' => 'text'],
                ]
            ],
            ['key' => 'img_right', 'label' => 'Đảo ảnh sang phải', 'type' => 'toggle', 'default' => false],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.about_intro', compact('config', 'widget'));
    }
}
