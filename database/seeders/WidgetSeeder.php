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
        // HOMEPAGE - Main Layout Builder
        // Sắp xếp các Section trên trang chủ (area: homepage)
        // ═══════════════════════════════════════════════════════════════

        // 1. Hero Slider
        Widget::create([
            'name'       => 'Banner Chính',
            'type'       => 'hero_main',
            'area'       => 'homepage',
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

        // 2. Category Slider
        Widget::create([
            'name'       => 'Danh mục nổi bật',
            'type'       => 'cat_swiper',
            'area'       => 'homepage',
            'sort_order' => 2,
            'is_active'  => true,
            'config'     => [
                'title'           => '',
                'limit'           => 10,
                'slides_per_view' => 10,
                'style'           => 'circle',
                'show_count'      => true,
                'source'          => 'db',
                'items'           => [],
            ],
        ]);

        // 3. Features Bar
        Widget::create([
            'name'       => 'Cam kết chất lượng',
            'type'       => 'feature_icons',
            'area'       => 'homepage',
            'sort_order' => 3,
            'is_active'  => true,
            'config'     => [
                'items' => [
                    ['icon' => 'fa-solid fa-dollar-sign',   'title' => 'Giá siêu rẻ',    'subtitle' => 'Đơn hàng từ 500k'],
                    ['icon' => 'fa-solid fa-rotate-left',   'title' => 'Đổi trả dễ dàng', 'subtitle' => 'Trong vòng 7 ngày'],
                    ['icon' => 'fa-solid fa-tag',           'title' => 'Ưu đãi mỗi ngày','subtitle' => 'Cập nhật liên tục'],
                    ['icon' => 'fa-solid fa-headset',       'title' => 'Hỗ trợ 24/7',        'subtitle' => 'Tận tâm phục vụ'],
                    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'An toàn 100%',     'subtitle' => 'VietGAP & Organic'],
                ],
            ],
        ]);

        // 4. Promo Banners
        Widget::create([
            'name'       => 'Banner khuyến mãi',
            'type'       => 'promo_banners',
            'area'       => 'homepage',
            'sort_order' => 4,
            'is_active'  => true,
            'config'     => [
                'columns' => '4',
                'items'   => [
                    [
                        'badge'    => 'Giảm giá cuối tuần',
                        'title'    => 'Nước ngô tươi mát',
                        'subtitle' => 'Vị ngọt tự nhiên',
                        'bg_class' => 'bg_image one',
                        'btn_text' => 'Mua ngay',
                        'btn_link' => '/shop',
                    ],
                    [
                        'badge'    => 'Ưu đãi lớn',
                        'title'    => 'Chanh hữu cơ',
                        'subtitle' => 'Chuối sấy giòn',
                        'bg_class' => 'bg_image two',
                        'btn_text' => 'Mua ngay',
                        'btn_link' => '/shop',
                    ],
                    [
                        'badge'    => 'Mới về',
                        'title'    => 'Hạt dẻ Brazil',
                        'subtitle' => 'Snack Socola',
                        'bg_class' => 'bg_image three',
                        'btn_text' => 'Mua ngay',
                        'btn_link' => '/shop',
                    ],
                    [
                        'badge'    => 'Yêu thích',
                        'title'    => 'Nước dâu tươi',
                        'subtitle' => 'Hương vị tuyệt vời',
                        'bg_class' => 'bg_image four',
                        'btn_text' => 'Mua ngay',
                        'btn_link' => '/shop',
                    ],
                ],
            ],
        ]);

        // 5. Featured Products Grid (Sản phẩm nổi bật)
        Widget::create([
            'name'       => 'Sản phẩm tiêu biểu',
            'type'       => 'prod_featured',
            'area'       => 'homepage',
            'sort_order' => 5,
            'is_active'  => true,
            'config'     => [
                'title'      => 'Sản phẩm tiêu biểu',
                'layout'     => 'swiper',
                'columns'    => '5',
                'limit'      => 10,
                'filter'     => 'featured',
                'show_btn'   => true,
                'btn_text'   => 'Xem tất cả',
                'btn_link'   => '/shop',
                'wrap_class' => 'rts-grocery-feature-area rts-section-gapBottom',
            ],
        ]);

        // 6. Deal Countdown
        Widget::create([
            'name'       => 'Ưu đãi Flash Sale',
            'type'       => 'deal_flash',
            'area'       => 'homepage',
            'sort_order' => 6,
            'is_active'  => true,
            'config'     => [
                'title'        => 'Flash Sale hôm nay',
                'countdown_to' => date('m/d/Y', strtotime('+3 days')) . ' 23:59:59',
                'limit'        => 4,
                'promo_cards'  => [
                    [
                        'title'    => "Nước ép cam\nhữu cơ Alpro",
                        'price'    => '150.000đ',
                        'bg_class' => 'bg-1',
                        'link'     => '/shop',
                    ],
                    [
                        'title'    => "Nước ép táo\ntươi mát Alpro",
                        'price'    => '120.000đ',
                        'bg_class' => 'bg-2',
                        'link'     => '/shop',
                    ],
                ],
            ],
        ]);

        // 7. Product Tabs (Weekly Best Selling)
        Widget::create([
            'name'       => 'Bán chạy nhất tuần',
            'type'       => 'prod_tabs',
            'area'       => 'homepage',
            'sort_order' => 7,
            'is_active'  => true,
            'config'     => [
                'title'    => 'Thực phẩm bán chạy tuần này',
                'columns'  => '6',
                'limit'    => 12,
                'bg_light' => true,
                'tabs'     => [
                    ['label' => 'Đồ đông lạnh',   'filter' => 'best_selling', 'category_id' => null],
                    ['label' => 'Đồ ăn kiêng',    'filter' => 'new',          'category_id' => null],
                    ['label' => 'Thực phẩm sạch',  'filter' => 'sale',         'category_id' => null],
                    ['label' => 'Vitamin & TPCN', 'filter' => 'all',          'category_id' => null],
                ],
            ],
        ]);

        // 8. Top Trending Products
        Widget::create([
            'name'       => 'Xu hướng mua sắm',
            'type'       => 'prod_featured',
            'area'       => 'homepage',
            'sort_order' => 8,
            'is_active'  => true,
            'config'     => [
                'title'      => 'Sản phẩm đang hot',
                'layout'     => 'grid',
                'columns'    => '4',
                'limit'      => 8,
                'filter'     => 'best_seller',
                'show_btn'   => false,
                'wrap_class' => 'top-tranding-product rts-section-gap',
            ],
        ]);

        // 9. Testimonials
        Widget::create([
            'name'       => 'Đánh giá khách hàng',
            'type'       => 'testimonials',
            'area'       => 'homepage',
            'sort_order' => 9,
            'is_active'  => true,
            'config'     => [
                'title'    => 'Khách hàng nói gì về VietTinMart',
                'subtitle' => 'Sự hài lòng của khách hàng là ưu tiên hàng đầu của chúng tôi',
                'items'    => [
                    [
                        'name'     => 'Chị Lan Anh',
                        'position' => 'Nội trợ, Hà Nội',
                        'avatar'   => '',
                        'content'  => 'Rau củ ở đây rất tươi, giao hàng đúng hẹn. Cảm ơn VietTinMart nhé!',
                        'stars'    => 5,
                    ],
                    [
                        'name'     => 'Anh Minh Tuân',
                        'position' => 'Nhân viên VP, HCM',
                        'avatar'   => '',
                        'content'  => 'Dịch vụ tốt, trái cây nhập khẩu chất lượng rất ổn định.',
                        'stars'    => 5,
                    ],
                ],
            ],
        ]);

        // 10. Newsletters
        Widget::create([
            'name'       => 'Newsletter',
            'type'       => 'newsletter_bar',
            'area'       => 'homepage',
            'sort_order' => 10,
            'is_active'  => true,
            'config'     => [
                'title'       => 'Đăng ký nhận tin khuyến mãi',
                'subtitle'    => 'Nhận ngay Voucher 100k khi đăng ký thành viên mới',
                'placeholder' => 'Email của bạn...',
                'btn_text'    => 'Đăng ký ngay',
            ],
        ]);

        // 11. Latest Posts
        Widget::create([
            'name'       => 'Tin tức mới nhất',
            'type'       => 'posts_latest',
            'area'       => 'homepage',
            'sort_order' => 11,
            'is_active'  => true,
            'config'     => [
                'title'        => 'Góc tin tức & Sức khỏe',
                'limit'        => 4,
                'columns'      => '4',
                'show_excerpt' => false,
                'show_btn'     => true,
            ],
        ]);

        $this->command->info('✅ Đã seed đầy đủ ' . Widget::count() . ' widgets cho Homepage Builder.');
    }
}
