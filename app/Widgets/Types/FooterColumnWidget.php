<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class FooterColumnWidget extends BaseWidget
{
    public static string $label = 'Cột chân trang';
    public static string $description = 'Hiển thị các cột thông tin, menu hoặc bản tin ở chân trang.';
    public static string $icon = 'fa-solid fa-list-ul';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề cột', 'type' => 'text'],
            [
                'key' => 'type',
                'label' => 'Loại nội dung',
                'type' => 'select',
                'options' => [
                    'contact' => 'Thông tin liên hệ & giờ mở cửa',
                    'menu' => 'Menu liên kết',
                    'newsletter' => 'Đăng ký bản tin',
                    'html' => 'HTML tùy chỉnh',
                ],
                'default' => 'menu'
            ],
            
            // For Contact type
            ['key' => 'phone', 'label' => 'Số điện thoại', 'type' => 'text', 'placeholder' => '+258 3692 2569'],
            ['key' => 'phone_label', 'label' => 'Nhãn hotline', 'type' => 'text', 'default' => 'Hotline hỗ trợ 24/7'],
            ['key' => 'hours', 'label' => 'Giờ làm việc', 'type' => 'textarea', 'placeholder' => "Thứ 2 - Thứ 6: 8:00am - 6:00pm\nThứ 7: 8:00am - 6:00pm\nChủ nhật: Nghỉ"],
            
            // For Menu type
            ['key' => 'menu_slug', 'label' => 'Chọn Menu', 'type' => 'select', 'options' => [
                'footer-info' => 'Thông tin (footer-info)',
                'footer-categories' => 'Danh mục (footer-categories)',
                'footer-links' => 'Liên kết hữu ích (footer-links)',
            ]],
            ['key' => 'show_sitemap', 'label' => 'Hiển thị Sitemap', 'type' => 'toggle', 'default' => false],

            // For Newsletter type
            ['key' => 'newsletter_desc', 'label' => 'Mô tả ngắn', 'type' => 'textarea'],
            ['key' => 'newsletter_note', 'label' => 'Ghi chú dưới nút', 'type' => 'text'],
            ['key' => 'html_content', 'label' => 'Nội dung HTML', 'type' => 'html'],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.footer_column', [
            'config' => $config,
            'widget' => $widget,
            'type' => $config['type'] ?? 'menu',
        ]);
    }
}
