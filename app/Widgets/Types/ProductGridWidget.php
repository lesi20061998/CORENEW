<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Product;
use App\Widgets\BaseWidget;

class ProductGridWidget extends BaseWidget
{
    public static string $label       = 'Lưới sản phẩm';
    public static string $description = 'Hiển thị sản phẩm dạng grid hoặc slider với tab lọc';
    public static string $icon        = 'fa-solid fa-grid-2';

    public static function fields(): array
    {
        return [
            ['key' => 'title',       'label' => 'Tiêu đề section',     'type' => 'text',   'default' => 'Featured Grocery'],
            ['key' => 'layout',      'label' => 'Kiểu hiển thị',       'type' => 'select',
                'options' => ['slider' => 'Slider', 'grid' => 'Grid', 'grid_tabs' => 'Grid có tab'],
                'default' => 'slider'],
            ['key' => 'columns',     'label' => 'Số cột',               'type' => 'select',
                'options' => ['2' => '2 cột', '3' => '3 cột', '4' => '4 cột', '5' => '5 cột', '6' => '6 cột'],
                'default' => '5'],
            ['key' => 'limit',       'label' => 'Số sản phẩm',         'type' => 'number', 'default' => 10],
            ['key' => 'filter',      'label' => 'Lọc sản phẩm (nếu không có Tab)',        'type' => 'select',
                'options' => ['new' => 'Mới nhất', 'sale' => 'Đang giảm giá', 'best_selling' => 'Bán chạy', 'all' => 'Tất cả'],
                'default' => 'new'],
            ['key' => 'category_id', 'label' => 'Lọc theo danh mục', 'type' => 'category_select', 'default' => []],
            ['key' => 'show_btn',    'label' => 'Hiện nút xem thêm',   'type' => 'toggle', 'default' => false],
            ['key' => 'btn_text',    'label' => 'Text nút',             'type' => 'text',   'default' => 'Xem tất cả'],
            ['key' => 'btn_link',    'label' => 'Link nút',             'type' => 'text',   'default' => '/shop'],
            ['key' => 'wrap_class',  'label' => 'CSS class wrapper',    'type' => 'text',   'default' => 'rts-grocery-feature-area rts-section-gapBottom'],
            ['key' => 'tabs',        'label' => 'Tabs lọc (Dành cho kiểu Grid có tab)', 'type' => 'repeater', 
                'default' => [
                    ['label' => 'All Products', 'filter' => 'all'],
                    ['label' => 'New Arrivals', 'filter' => 'new'],
                    ['label' => 'Best Sellers', 'filter' => 'best_selling'],
                ],
                'fields' => [
                    ['key' => 'label',       'label' => 'Tên tab',     'type' => 'text', 'col' => 'col-md-4'],
                    ['key' => 'category_id', 'label' => 'Danh mục',    'type' => 'category_select', 'single' => true, 'col' => 'col-md-4'],
                    ['key' => 'filter',      'label' => 'Lọc theo',    'type' => 'select', 'col' => 'col-md-4',
                        'options' => ['new' => 'Mới nhất', 'sale' => 'Giảm giá', 'best_selling' => 'Bán chạy', 'all' => 'Tất cả']],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $products = static::fetchProducts($config);

        return static::view('widgets.types.prod_featured', [
            'config'   => $config,
            'widget'   => $widget,
            'products' => $products,
        ]);
    }

    public static function fetchProducts(array $config, ?string $tabFilter = null, ?int $tabCategoryId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Product::where('status', 'active');

        $filter      = $tabFilter ?? ($config['filter'] ?? 'new');
        $categoryIds = $tabCategoryId
            ? [$tabCategoryId]
            : (array_filter((array)($config['category_id'] ?? [])));

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        match ($filter) {
            'sale'         => $query->whereNotNull('compare_price')->whereColumn('price', '<', 'compare_price'),
            'best_selling' => $query->orderByDesc('id'),
            'featured'     => $query->where('is_featured', true)->latest(),
            default        => $query->latest(),
        };

        return $query->limit((int)($config['limit'] ?? 8))->get();
    }
}
