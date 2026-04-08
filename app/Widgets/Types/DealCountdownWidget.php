<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Product;
use App\Widgets\BaseWidget;

class DealCountdownWidget extends BaseWidget
{
    public static string $label       = 'Flash Sale (Giảm giá)';
    public static string $description = 'Hiển thị sản phẩm Big Sale với đồng hồ đếm ngược';
    public static string $icon        = 'fa-solid fa-bolt';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề section', 'type' => 'text', 'default' => 'Flash Sale Cuối Tuần'],
            ['key' => 'campaign_id', 'label' => 'Chọn chiến dịch Flash Sale', 'type' => 'campaign_select'],
            ['key' => 'limit', 'label' => 'Số sản phẩm hiển thị', 'type' => 'number', 'default' => 4],
            ['type' => 'tab_start', 'label' => 'Cấu hình Banner', 'key' => 'banner_tab'],
            ['key' => 'promo_cards', 'label' => 'Banners Khuyến mãi (Bên trái)', 'type' => 'repeater', 
                'default' => [
                    ['title' => "Ưu đãi\nĐặc biệt", 'price' => 'Giảm 50%', 'bg_class' => 'bg-1', 'link' => '/cua-hang'],
                ],
                'fields' => [
                    ['key' => 'title', 'label' => 'Tiêu đề banner', 'type' => 'text'],
                    ['key' => 'price', 'label' => 'Giá/Nội dung', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'bg_class', 'label' => 'Màu nền (bg-1, bg-2)', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'image', 'label' => 'Ảnh nền', 'type' => 'image'],
                    ['key' => 'link',  'label' => 'Link', 'type' => 'text'],
                ]
            ],
            ['key' => 'banner_tab_end', 'type' => 'tab_end'],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $campaignId = $config['campaign_id'] ?? null;
        
        if ($campaignId) {
            $campaign = \App\Models\FlashSaleCampaign::find($campaignId);
        } else {
            $campaign = \App\Models\FlashSaleCampaign::running()->latest()->first();
        }
        
        if ($campaign) {
            $config['title'] = $config['title'] ?? $campaign->name;
            $config['end_date'] = $campaign->ends_at->format('m/d/Y H:i:s');
            
            // Lấy sản phẩm từ campaign items
            $productIds = $campaign->productItems()->pluck('product_id')->toArray();
            
            $products = Product::where('status', 'active')
                ->whereIn('id', $productIds)
                ->limit((int)($config['limit'] ?? 4))
                ->get();
        } else {
            // Fallback: Logic cũ cho sản phẩm giảm giá thủ công
            $categoryIds = array_filter((array)($config['category_id'] ?? []));
            $products = Product::where('status', 'active')
                ->when(!empty($categoryIds), fn($q) => $q->whereIn('category_id', $categoryIds))
                ->whereNotNull('compare_price')
                ->whereColumn('price', '<', 'compare_price')
                ->latest()
                ->limit((int)($config['limit'] ?? 4))
                ->get();
        }

        return static::view('widgets.types.deal_flash', [
            'config'   => $config,
            'widget'   => $widget,
            'products' => $products
        ]);
    }
}
