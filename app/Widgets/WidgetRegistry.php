<?php

namespace App\Widgets;

/**
 * Registry trung tâm cho Widget Areas và Widget Types.
 *
 * THÊM WIDGET TYPE MỚI:
 *   1. php artisan widget:make TenWidget
 *   2. Đăng ký class vào $widgetClasses bên dưới
 *
 * THÊM WIDGET AREA MỚI:
 *   Thêm vào mảng areas() bên dưới
 */
class WidgetRegistry
{
    /**
     * Danh sách Widget Type Classes đã đăng ký.
     * key => FQCN
     */
    protected static array $widgetClasses = [
        // Homepage sections
        'hero_slider'      => \App\Widgets\Types\HeroSliderWidget::class,
        'category_slider'  => \App\Widgets\Types\CategorySliderWidget::class,
        'features_bar'     => \App\Widgets\Types\FeaturesBarWidget::class,
        'product_grid'     => \App\Widgets\Types\ProductGridWidget::class,
        'deal_countdown'   => \App\Widgets\Types\DealCountdownWidget::class,
        'promo_banners'    => \App\Widgets\Types\PromoBannersWidget::class,
        'testimonial'      => \App\Widgets\Types\TestimonialWidget::class,
        'latest_posts'     => \App\Widgets\Types\LatestPostsWidget::class,
        'newsletter'       => \App\Widgets\Types\NewsletterWidget::class,
        'product_tabs'     => \App\Widgets\Types\ProductTabsWidget::class,
        'html_block'       => \App\Widgets\Types\HtmlBlockWidget::class,
    ];

    /**
     * Định nghĩa Widget Areas.
     * key => ['label', 'description', 'icon']
     */
    public static function areas(): array
    {
        return [
            // Homepage
            'homepage_v1'    => ['label' => 'Trang chủ',          'description' => 'Khu vực trang chủ chính',         'icon' => 'fa-house'],

            // Shop areas
            'shop_top'       => ['label' => 'Shop - Trên',        'description' => 'Banner trên trang shop',          'icon' => 'fa-store'],
            'shop_sidebar'   => ['label' => 'Shop - Sidebar',     'description' => 'Sidebar trang shop',              'icon' => 'fa-sidebar'],
            'product_below'  => ['label' => 'Sản phẩm - Dưới',   'description' => 'Dưới chi tiết sản phẩm',         'icon' => 'fa-box'],

            // Blog areas
            'blog_sidebar'   => ['label' => 'Blog - Sidebar',     'description' => 'Sidebar trang blog',              'icon' => 'fa-newspaper'],

            // Footer areas
            'footer'         => ['label' => 'Footer',             'description' => 'Khu vực footer',                  'icon' => 'fa-table-columns'],
        ];
    }

    /**
     * Lấy tất cả widget types đã đăng ký
     */
    public static function types(): array
    {
        $result = [];
        foreach (static::$widgetClasses as $key => $class) {
            if (class_exists($class)) {
                $result[$key] = [
                    'label'       => $class::$label,
                    'description' => $class::$description,
                    'icon'        => $class::$icon,
                    'class'       => $class,
                    'fields'      => $class::fields(),
                ];
            }
        }
        return $result;
    }

    /**
     * Lấy class của một widget type
     */
    public static function getClass(string $type): ?string
    {
        return static::$widgetClasses[$type] ?? null;
    }

    /**
     * Đăng ký thêm widget type từ bên ngoài (ServiceProvider)
     */
    public static function register(string $key, string $class): void
    {
        static::$widgetClasses[$key] = $class;
    }

    /**
     * Đăng ký thêm widget area từ bên ngoài
     */
    public static function registerArea(string $key, string $label, string $description = '', string $icon = 'fa-puzzle-piece'): void
    {
        static::$extraAreas[$key] = compact('label', 'description', 'icon');
    }

    protected static array $extraAreas = [];
}
