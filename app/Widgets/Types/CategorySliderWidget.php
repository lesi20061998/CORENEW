<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Category;
use App\Widgets\BaseWidget;

class CategorySliderWidget extends BaseWidget
{
    public static string $label       = 'Thanh trượt Danh mục';
    public static string $description = 'Hiển thị icon các danh mục sản phẩm nổi bật';
    public static string $icon        = 'fa-solid fa-list';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề Section', 'type' => 'text', 'default' => ''],
            ['key' => 'category_id', 'label' => 'Chọn danh mục', 'type' => 'category_select', 'default' => []],
            ['key' => 'style', 'label' => 'Kiểu hiển thị', 'type' => 'select', 
                'options' => ['circle' => 'Hình tròn (V1)', 'card' => 'Thẻ vuông (V2)'],
                'default' => 'circle'],
            ['key' => 'slidesPerView', 'label' => 'Số icon hiển thị', 'type' => 'number', 'default' => 10],
            ['key' => 'show_count',    'label' => 'Hiện số lượng SP', 'type' => 'toggle', 'default' => false],
            ['key' => 'loop', 'label' => 'Chạy vòng lặp', 'type' => 'toggle', 'default' => true],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $categoryIds = array_filter((array)($config['category_id'] ?? []));
        $query       = Category::where('is_active', true);

        if (!empty($categoryIds)) {
            $query->whereIn('id', $categoryIds);
        } else {
            $query->whereHas('products')->limit((int)($config['slidesPerView'] ?? 10));
        }

        $categories = $query->get();

        return static::view('widgets.types.cat_swiper', [
            'config'     => $config,
            'widget'     => $widget,
            'categories' => $categories
        ]);
    }
}
