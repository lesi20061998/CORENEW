<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

/**
 * Widget: Newsletter - Form đăng ký nhận tin
 */
class NewsletterWidget extends BaseWidget
{
    public static string $label       = 'Đăng ký nhận tin';
    public static string $description = 'Form đăng ký email nhận khuyến mãi';
    public static string $icon        = 'fa-solid fa-envelope-open-text';

    public static function fields(): array
    {
        return [
            ['key' => 'title',       'label' => 'Tiêu đề',       'type' => 'text',  'default' => 'Đăng ký nhận ưu đãi'],
            ['key' => 'subtitle',    'label' => 'Phụ đề',        'type' => 'text',  'default' => 'Nhận thông báo khuyến mãi mới nhất từ chúng tôi'],
            ['key' => 'placeholder', 'label' => 'Placeholder',   'type' => 'text',  'default' => 'Nhập địa chỉ email của bạn'],
            ['key' => 'btn_text',    'label' => 'Text nút',      'type' => 'text',  'default' => 'Đăng ký ngay'],
            ['key' => 'bg_image',    'label' => 'Ảnh nền',       'type' => 'image'],
            ['key' => 'bg_color',    'label' => 'Màu nền (hex)', 'type' => 'text',  'default' => '#f5f5f5'],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.newsletter', ['config' => $config, 'widget' => $widget]);
    }
}
