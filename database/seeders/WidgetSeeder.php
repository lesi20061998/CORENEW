<?php

namespace Database\Seeders;

use App\Models\Widget;
use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    public function run(): void
    {
        Widget::truncate();

        // ═══════════════════════════════════════════════════════════════
        // HOMEPAGE V1 — Giống 100% index.html (Ekomart)
        // Thứ tự: Hero Slider → Category Slider → Features Bar →
        //         Featured Grocery (Slider) → Deal Countdown →
        //         Weekly Best Selling (Tabs) → Promo Banners (4 card) →
        //         Top Trending (Grid) → Latest Posts (Blog)
        // ═══════════════════════════════════════════════════════════════

        // 1. Hero Slider — .rts-banner-area-one (2 slides, bg_one-banner)
        Widget::create([
            'name'       => 'Hero Slider',
            'type'       => 'hero_slider',
            'area'       => 'homepage_v1',
            'sort_order' => 1,
            'is_active'  => true,
            'config'     => [
                'style'    => 'fullwidth',
                'autoplay' => true,
                'interval' => 4000,
                'slides'   => [
                    [
                        'bg_class'    => 'bg_one-banner',
                        'pre_title'   => 'Giảm đến 30% cho đơn hàng đầu tiên từ 1.500.000đ',
                        'title'       => "Đừng bỏ lỡ những ưu đãi\nthực phẩm tuyệt vời",
                        'description' => '',
                        'btn_text'    => 'Mua ngay',
                        'btn_link'    => '/shop',
                        'price'       => '',
                    ],
                    [
                        'bg_class'    => 'bg_one-banner two',
                        'pre_title'   => 'Giảm đến 30% cho đơn hàng đầu tiên từ 1.500.000đ',
                        'title'       => "Thực phẩm tươi sạch\nmỗi ngày cho gia đình bạn",
                        'description' => '',
                        'btn_text'    => 'Khám phá ngay',
                        'btn_link'    => '/shop',
                        'price'       => '',
                    ],
                ],
            ],
        ]);

        // 2. Category Slider — .rts-caregory-area-one (10 danh mục, circle style)
        Widget::create([
            'name'       => 'Danh mục sản phẩm',
            'type'       => 'category_slider',
            'area'       => 'homepage_v1',
            'sort_order' => 2,
            'is_active'  => true,
            'config'     => [
                'title'           => '',
                'limit'           => 10,
                'slides_per_view' => 10,
                'style'           => 'circle',
                'show_count'      => false,
                'source'          => 'db',
                'items'           => [],
            ],
        ]);

        // 3. Features Bar — .rts-feature-area (5 tính năng, SVG icons)
        Widget::create([
            'name'       => 'Thanh tính năng',
            'type'       => 'features_bar',
            'area'       => 'homepage_v1',
            'sort_order' => 3,
            'is_active'  => true,
            'config'     => [
                'items' => [
                    ['icon' => 'fa-solid fa-dollar-sign',   'title' => 'Wide Assortment',    'subtitle' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-rotate-left',   'title' => 'Easy Return Policy', 'subtitle' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-tag',           'title' => 'Best Prices & Offers','subtitle' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-headset',       'title' => 'Support 24/7',        'subtitle' => 'Orders $50 or more'],
                    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Best Prices & Offers','subtitle' => 'Orders $50 or more'],
                ],
            ],
        ]);

        // 4. Featured Grocery — .rts-grocery-feature-area (Slider 5-6 sản phẩm)
        Widget::create([
            'name'       => 'Featured Grocery',
            'type'       => 'product_grid',
            'area'       => 'homepage_v1',
            'sort_order' => 4,
            'is_active'  => true,
            'config'     => [
                'title'      => 'Featured Grocery',
                'layout'     => 'slider',
                'columns'    => '5',
                'limit'      => 10,
                'filter'     => 'new',
                'show_btn'   => false,
                'wrap_class' => 'rts-grocery-feature-area rts-section-gapBottom',
                'tabs'       => [],
            ],
        ]);

        // 5. Deal Countdown — .rts-grocery-feature-area (countdown + 2 promo cards + 4 sản phẩm)
        Widget::create([
            'name'       => 'Products With Discounts',
            'type'       => 'deal_countdown',
            'area'       => 'homepage_v1',
            'sort_order' => 5,
            'is_active'  => true,
            'config'     => [
                'title'        => 'Products With Discounts',
                'countdown_to' => '10/05/2025 10:20:00',
                'limit'        => 4,
                'promo_cards'  => [
                    [
                        'title'    => "Alpro Organic Flavored\nFresh Juice",
                        'price'    => '$15.00',
                        'bg_class' => 'bg-1',
                        'link'     => '/shop',
                    ],
                    [
                        'title'    => "Alpro Organic Flavored\nFresh Juice",
                        'price'    => '$15.00',
                        'bg_class' => 'bg-2',
                        'link'     => '/shop',
                    ],
                ],
            ],
        ]);

        // 6. Weekly Best Selling — .weekly-best-selling-area (4 tabs, 6 cột)
        Widget::create([
            'name'       => 'Weekly Best Selling Groceries',
            'type'       => 'product_tabs',
            'area'       => 'homepage_v1',
            'sort_order' => 6,
            'is_active'  => true,
            'config'     => [
                'title'    => 'Weekly Best Selling Groceries',
                'columns'  => '6',
                'limit'    => 12,
                'bg_light' => true,
                'tabs'     => [
                    ['label' => 'Frozen Foods',   'filter' => 'best_selling', 'category_id' => null],
                    ['label' => 'Diet Foods',      'filter' => 'new',          'category_id' => null],
                    ['label' => 'Healthy Foods',   'filter' => 'sale',         'category_id' => null],
                    ['label' => 'Vitamin Items',   'filter' => 'all',          'category_id' => null],
                ],
            ],
        ]);

        // 7. Promo Banners — .category-feature-area (4 card với bg_image one/two/three/four)
        Widget::create([
            'name'       => 'Category Feature Banners',
            'type'       => 'promo_banners',
            'area'       => 'homepage_v1',
            'sort_order' => 7,
            'is_active'  => true,
            'config'     => [
                'columns' => '4',
                'items'   => [
                    [
                        'badge'    => 'Weekend Discount',
                        'title'    => 'Drink Fresh Corn Juice',
                        'subtitle' => 'Good Taste',
                        'bg_class' => 'bg_image one',
                        'btn_text' => 'Shop Now',
                        'btn_link' => '/shop',
                    ],
                    [
                        'badge'    => 'Weekend Discount',
                        'title'    => 'Organic Lemon Flavored',
                        'subtitle' => 'Banana Chips',
                        'bg_class' => 'bg_image two',
                        'btn_text' => 'Shop Now',
                        'btn_link' => '/shop',
                    ],
                    [
                        'badge'    => 'Weekend Discount',
                        'title'    => 'Nozes Pecanera Brasil',
                        'subtitle' => 'Chocolate Snacks',
                        'bg_class' => 'bg_image three',
                        'btn_text' => 'Shop Now',
                        'btn_link' => '/shop',
                    ],
                    [
                        'badge'    => 'Weekend Discount',
                        'title'    => 'Strawberry Water Drinks',
                        'subtitle' => 'Flavors Awesome',
                        'bg_class' => 'bg_image four',
                        'btn_text' => 'Shop Now',
                        'btn_link' => '/shop',
                    ],
                ],
            ],
        ]);

        // 8. Top Trending Products — .top-tranding-product (Grid 4 cột)
        Widget::create([
            'name'       => 'Top Trending Products',
            'type'       => 'product_grid',
            'area'       => 'homepage_v1',
            'sort_order' => 8,
            'is_active'  => true,
            'config'     => [
                'title'      => 'Top Trending Products',
                'layout'     => 'grid',
                'columns'    => '4',
                'limit'      => 8,
                'filter'     => 'new',
                'show_btn'   => false,
                'wrap_class' => 'top-tranding-product rts-section-gap',
                'tabs'       => [],
            ],
        ]);

        // 9. Latest Posts — .blog-area-start (4 cột, 4 bài)
        Widget::create([
            'name'       => 'Latest Blog Posts',
            'type'       => 'latest_posts',
            'area'       => 'homepage_v1',
            'sort_order' => 9,
            'is_active'  => true,
            'config'     => [
                'title'        => 'Latest Blog Posts',
                'limit'        => 4,
                'columns'      => '4',
                'show_excerpt' => false,
                'show_btn'     => false,
            ],
        ]);

        // ═══════════════════════════════════════════════════════════════
        // HOMEPAGE V2 — Layout hiện đại (index-two.html)
        // ═══════════════════════════════════════════════════════════════

        // 1. Hero Slider V2 (full-width với giá)
        Widget::create([
            'name'       => 'Hero Banner - Trang chủ V2',
            'type'       => 'hero_slider',
            'area'       => 'homepage_v2',
            'sort_order' => 1,
            'is_active'  => true,
            'config'     => [
                'style'    => 'fullwidth',
                'autoplay' => false,
                'interval' => 5000,
                'slides'   => [
                    [
                        'bg_class'    => 'bg_banner-2',
                        'pre_title'   => 'Giảm đến 30% cho đơn hàng đầu tiên từ 1.500.000đ',
                        'title'       => "Nuôi dưỡng gia đình\nvới giá tốt nhất",
                        'description' => 'Chúng tôi đã chuẩn bị những ưu đãi đặc biệt cho bạn trên các sản phẩm thực phẩm. Đừng bỏ lỡ cơ hội này...',
                        'btn_text'    => 'Mua ngay',
                        'btn_link'    => '/shop',
                        'price_label' => 'từ',
                        'price'       => '80.000đ',
                    ],
                ],
            ],
        ]);

        // 2. Category Slider V2 (style card)
        Widget::create([
            'name'          => 'Danh mục - V2',
            'type'          => 'category_slider',
            'area'          => 'homepage_v2',
            'sort_order'    => 2,
            'is_active'     => true,
            'config'        => [
                'title'          => 'Mua sắm theo danh mục',
                'limit'          => 8,
                'slides_per_view'=> 8,
                'style'          => 'card',
                'show_count'     => true,
                'source'         => 'db',
                'items'          => [],
            ],
        ]);

        // 3. Features Bar V2
        Widget::create([
            'name'       => 'Tính năng - V2',
            'type'       => 'features_bar',
            'area'       => 'homepage_v2',
            'sort_order' => 3,
            'is_active'  => true,
            'config'     => [
                'items' => [
                    ['icon' => 'fa-solid fa-leaf',          'title' => 'Hữu cơ 100%',        'subtitle' => 'Sản phẩm tự nhiên'],
                    ['icon' => 'fa-solid fa-truck-fast',    'title' => 'Giao hàng nhanh',    'subtitle' => 'Trong ngày'],
                    ['icon' => 'fa-solid fa-award',         'title' => 'Chất lượng cao',     'subtitle' => 'Đảm bảo chất lượng'],
                    ['icon' => 'fa-solid fa-headset',       'title' => 'Hỗ trợ 24/7',        'subtitle' => 'Luôn sẵn sàng'],
                ],
            ],
        ]);

        // 4. Sản phẩm mới (Grid)
        Widget::create([
            'name'       => 'Sản phẩm mới - V2',
            'type'       => 'product_grid',
            'area'       => 'homepage_v2',
            'sort_order' => 4,
            'is_active'  => true,
            'config'     => [
                'title'   => 'Sản phẩm mới nhất',
                'layout'  => 'grid',
                'columns' => '4',
                'limit'   => 8,
                'filter'  => 'new',
                'show_btn'=> true,
                'btn_text'=> 'Xem tất cả',
                'btn_link'=> '/shop',
                'tabs'    => [],
            ],
        ]);

        // 5. Deal Countdown V2
        Widget::create([
            'name'       => 'Flash Sale - V2',
            'type'       => 'deal_countdown',
            'area'       => 'homepage_v2',
            'sort_order' => 5,
            'is_active'  => true,
            'config'     => [
                'title'        => 'Flash Sale hôm nay',
                'countdown_to' => '12/31/2025 23:59:59',
                'limit'        => 4,
                'promo_cards'  => [],
            ],
        ]);

        // 6. Testimonial
        Widget::create([
            'name'       => 'Đánh giá khách hàng',
            'type'       => 'testimonial',
            'area'       => 'homepage_v2',
            'sort_order' => 6,
            'is_active'  => true,
            'config'     => [
                'title'    => 'Khách hàng nói gì về chúng tôi',
                'subtitle' => 'Hơn 10.000 khách hàng tin tưởng lựa chọn',
                'items'    => [
                    [
                        'name'     => 'Nguyễn Thị Lan',
                        'position' => 'Hà Nội',
                        'avatar'   => '',
                        'content'  => 'Sản phẩm tươi ngon, giao hàng nhanh. Tôi rất hài lòng với dịch vụ của VietTinMart. Sẽ tiếp tục ủng hộ!',
                        'stars'    => 5,
                    ],
                    [
                        'name'     => 'Trần Văn Minh',
                        'position' => 'TP. Hồ Chí Minh',
                        'avatar'   => '',
                        'content'  => 'Giá cả hợp lý, chất lượng đảm bảo. Đặc biệt thích phần đổi trả dễ dàng. Rất tiện lợi cho gia đình tôi.',
                        'stars'    => 5,
                    ],
                    [
                        'name'     => 'Lê Thị Hoa',
                        'position' => 'Đà Nẵng',
                        'avatar'   => '',
                        'content'  => 'Mua hàng lần đầu nhưng rất ấn tượng. Rau củ tươi, đóng gói cẩn thận. Chắc chắn sẽ quay lại mua tiếp.',
                        'stars'    => 4,
                    ],
                ],
            ],
        ]);

        // 7. Newsletter
        Widget::create([
            'name'       => 'Đăng ký nhận tin',
            'type'       => 'newsletter',
            'area'       => 'homepage_v2',
            'sort_order' => 7,
            'is_active'  => true,
            'config'     => [
                'title'       => 'Đăng ký nhận ưu đãi độc quyền',
                'subtitle'    => 'Nhận ngay voucher 50.000đ cho đơn hàng đầu tiên khi đăng ký email',
                'placeholder' => 'Nhập địa chỉ email của bạn...',
                'btn_text'    => 'Đăng ký ngay',
                'bg_color'    => '#f0f7e6',
            ],
        ]);

        // 8. Latest Posts V2
        Widget::create([
            'name'       => 'Blog mới nhất - V2',
            'type'       => 'latest_posts',
            'area'       => 'homepage_v2',
            'sort_order' => 8,
            'is_active'  => true,
            'config'     => [
                'title'        => 'Góc sức khỏe & Ẩm thực',
                'limit'        => 3,
                'columns'      => '3',
                'show_excerpt' => true,
                'show_btn'     => true,
                'btn_text'     => 'Xem tất cả bài viết',
                'btn_link'     => '/blog',
            ],
        ]);

        $this->command->info('✅ Đã seed ' . Widget::count() . ' widgets (Homepage V1 + V2)');
    }
}
