<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class AboutHeroWidget extends BaseWidget
{
    public static string $label       = 'About Banner';
    public static string $description = 'Banner chính cho trang Giới thiệu có tiêu đề và mô tả.';
    public static string $icon        = 'fa-solid fa-image';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề lớn', 'type' => 'text', 'default' => 'Về Chúng Tôi'],
            ['key' => 'subtitle', 'label' => 'Mô tả ngắn', 'type' => 'text', 'default' => 'Sứ mệnh mang đến thực phẩm sạch.'],
            ['key' => 'image', 'label' => 'Ảnh nền', 'type' => 'image', 'default' => ''],
            ['key' => 'btn_text', 'label' => 'Text nút bấm', 'type' => 'text', 'default' => 'Liên hệ ngay'],
            ['key' => 'btn_link', 'label' => 'Link nút bấm', 'type' => 'text', 'default' => '/lien-he'],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.about_hero', compact('config', 'widget'));
    }
}
