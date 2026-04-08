<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Product;
use App\Widgets\BaseWidget;
use Illuminate\Database\Eloquent\Collection;

class ProductSectionWidget extends BaseWidget
{
    public static string $label = 'Section Sản phẩm  ';
    public static string $description = 'Hiển thị sản phẩm theo nhiều kiểu: Grid, Slider, Tabs hoặc Countdown';
    public static string $icon = 'fa-solid fa-layer-group';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề section', 'type' => 'text', 'default' => 'Sản phẩm mới'],
            ['key' => 'subtitle', 'label' => 'Tiêu đề phụ', 'type' => 'text'],
            [
                'key' => 'layout',
                'label' => 'Kiểu hiển thị',
                'type' => 'select',
                'options' => [
                    'grid' => 'Lưới (Grid)',
                    'slider' => 'Thanh trượt (Slider)',
                    'tabs' => 'Tab danh mục',
                    'countdown' => 'Deal có đếm ngược',
                ],
                'default' => 'grid'
            ],
            [
                'key' => 'columns',
                'label' => 'Số cột (Desktop)',
                'type' => 'select',
                'options' => ['2' => '2 cột', '3' => '3 cột', '4' => '4 cột', '5' => '5 cột', '6' => '6 cột'],
                'default' => '5',
                'col' => 'col-md-6'
            ],
            ['key' => 'limit', 'label' => 'Số sản phẩm tối đa', 'type' => 'number', 'default' => 10, 'col' => 'col-md-6'],

            // Filters (Used for Grid/Slider)
            [
                'key' => 'filter',
                'label' => 'Lọc theo',
                'type' => 'select',
                'options' => ['new' => 'Mới nhất', 'sale' => 'Khuyến mãi', 'best_selling' => 'Bán chạy', 'featured' => 'Nổi bật', 'all' => 'Tất cả'],
                'default' => 'new'
            ],
            ['key' => 'category_id', 'label' => 'Lọc theo danh mục', 'type' => 'category_select', 'default' => []],

            // Tabs Layout Config
            [
                'key' => 'tabs',
                'label' => 'Danh sách Tab (Dành cho kiểu Tab)',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'label', 'label' => 'Tên Tab', 'type' => 'text', 'col' => 'col-md-4'],
                    ['key' => 'category_id', 'label' => 'Danh mục', 'type' => 'category_select', 'single' => true, 'col' => 'col-md-4'],
                    [
                        'key' => 'filter',
                        'label' => 'Lọc theo',
                        'type' => 'select',
                        'col' => 'col-md-4',
                        'options' => ['best_selling' => 'Bán chạy', 'new' => 'Mới nhất', 'sale' => 'Giảm giá', 'all' => 'Tất cả']
                    ],
                ],
            ],

            // Countdown Layout Config
            ['key' => 'campaign_id', 'label' => 'Chiến dịch Flash Sale', 'type' => 'campaign_select', 'col' => 'col-md-6'],
            ['key' => 'end_date', 'label' => 'Ngày kết thúc thủ công', 'type' => 'datetime', 'placeholder' => '10/05/2026 10:20:00', 'col' => 'col-md-6'],
            [
                'key' => 'promo_cards',
                'label' => 'Banner quảng cáo (Bên trái)',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'image', 'label' => 'Ảnh nền', 'type' => 'image'],
                    ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text'],
                    ['key' => 'price', 'label' => 'Giá hiển thị', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'link', 'label' => 'Link', 'type' => 'text'],
                ]
            ],
            ['key' => 'btn_text', 'label' => 'Text nút xem thêm', 'type' => 'text', 'default' => 'Xem tất cả', 'col' => 'col-md-6'],
            ['key' => 'btn_link', 'label' => 'Link xem thêm', 'type' => 'text', 'default' => '/shop', 'col' => 'col-md-6'],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        // Detect layout if not explicitly set (Legacy support)
        $layout = $config['layout'] ?? match ($widget->type) {
            'prod_tabs' => 'tabs',
            'deal_flash' => 'countdown',
            default => 'grid'
        };

        $data = [
            'config' => array_merge($config, ['layout' => $layout]),
            'widget' => $widget,
        ];

        if ($layout === 'tabs') {
            $tabs = $config['tabs'] ?? [];
            $limit = (int) ($config['limit'] ?? 10);
            $tabProducts = [];
            foreach ($tabs as $i => $tab) {
                $tabProducts[$i] = static::fetchProducts([
                    'category_id' => $tab['category_id'] ?? null,
                    'filter' => $tab['filter'] ?? 'new',
                    'limit' => $limit
                ]);
            }
            $data['tabProducts'] = $tabProducts;
        } elseif ($layout === 'countdown') {
            $campaignId = $config['campaign_id'] ?? null;
            if ($campaignId) {
                $campaign = \App\Models\FlashSaleCampaign::find($campaignId);
            } else {
                $campaign = \App\Models\FlashSaleCampaign::where('status', 'active')->latest()->first();
            }

            if ($campaign) {
                $data['config']['end_date'] = $campaign->ends_at->format('m/d/Y H:i:s');
                $productIds = $campaign->productItems()->pluck('product_id')->toArray();
                $data['products'] = Product::where('status', 'active')->whereIn('id', $productIds)->limit($config['limit'] ?? 5)->get();
            } else {
                // Manual fallback
                $data['products'] = static::fetchProducts($config);
            }
        } else {
            $data['products'] = static::fetchProducts($config);
        }

        return static::view('widgets.types.product_section', $data);
    }

    public static function fetchProducts(array $params): Collection
    {
        $query = Product::where('status', 'active');
        $limit = (int) ($params['limit'] ?? 10);

        if (!empty($params['category_id'])) {
            if (is_array($params['category_id'])) {
                $query->whereIn('category_id', array_filter($params['category_id']));
            } else {
                $query->where('category_id', (int) $params['category_id']);
            }
        }

        $filter = $params['filter'] ?? 'new';
        match ($filter) {
            'sale' => $query->whereNotNull('compare_price')->whereColumn('price', '<', 'compare_price'),
            'best_selling' => $query->orderByDesc('id'), // Thêm logic bán chạy thực tế nếu có bảng order
            'featured' => $query->where('is_featured', true),
            default => $query->latest(),
        };

        return $query->limit($limit)->get();
    }
}
