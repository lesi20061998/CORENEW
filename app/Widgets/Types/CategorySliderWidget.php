<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Category;
use App\Widgets\BaseWidget;

/**
 * Widget: Category Slider - Danh mục dạng slider
 * Map từ: .rts-caregory-area-one (index.html)
 */
class CategorySliderWidget extends BaseWidget
{
    public static string $label       = 'Danh mục slider';
    public static string $description = 'Hiển thị danh mục dạng slider cuộn ngang';
    public static string $icon        = 'fa-solid fa-layer-group';

    public static function fields(): array
    {
        return [
            ['key' => 'title',        'label' => 'Tiêu đề section',  'type' => 'text',   'default' => ''],
            ['key' => 'limit',        'label' => 'Số danh mục',      'type' => 'number', 'default' => 10],
            ['key' => 'slides_per_view','label' => 'Số cột desktop', 'type' => 'number', 'default' => 10],
            ['key' => 'style',        'label' => 'Kiểu hiển thị',    'type' => 'select',
                'options' => ['circle' => 'Tròn (style 1)', 'card' => 'Card (style 2)'],
                'default' => 'circle'],
            ['key' => 'show_count',   'label' => 'Hiện số sản phẩm', 'type' => 'toggle', 'default' => false],
            ['key' => 'source',       'label' => 'Nguồn dữ liệu',    'type' => 'select',
                'options' => ['db' => 'Từ database', 'manual' => 'Nhập tay'],
                'default' => 'db'],
            ['key' => 'items',        'label' => 'Danh mục (nhập tay)', 'type' => 'repeater', 'default' => [],
                'fields' => [
                    ['key' => 'name',  'label' => 'Tên danh mục', 'type' => 'text'],
                    ['key' => 'image', 'label' => 'Ảnh',          'type' => 'image'],
                    ['key' => 'link',  'label' => 'Link',          'type' => 'text'],
                    ['key' => 'count', 'label' => 'Số sản phẩm',  'type' => 'text'],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $categories = collect();
        if (($config['source'] ?? 'db') === 'db') {
            $categories = Category::where('is_active', true)
                ->withCount('products')
                ->limit((int)($config['limit'] ?? 10))
                ->get();
        }

        return static::view('widgets.types.category_slider', [
            'config'     => $config,
            'widget'     => $widget,
            'categories' => $categories,
        ]);
    }
}
