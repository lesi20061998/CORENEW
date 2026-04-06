<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class AboutTestimonialsWidget extends BaseWidget
{
    public static string $label       = 'About Testimonials';
    public static string $description = 'Feedbacks area with specific About page style.';
    public static string $icon        = 'fa-solid fa-quote-left';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Customer Feedbacks'],
            ['key' => 'items', 'label' => 'Đánh giá', 'type' => 'repeater',
                'fields' => [
                    ['key' => 'name', 'label' => 'Tên', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'role', 'label' => 'Chức vụ', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'avatar', 'label' => 'Avatar (70x70)', 'type' => 'image', 'col' => 'col-md-6'],
                    ['key' => 'logo', 'label' => 'Logo/Quote Icon', 'type' => 'image', 'col' => 'col-md-6'],
                    ['key' => 'content', 'label' => 'Nội dung', 'type' => 'textarea'],
                ]
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.about_testimonials', compact('config', 'widget'));
    }
}
