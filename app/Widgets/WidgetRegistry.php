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
        // Homepage Sections (The Builder)
        'hero_main' => \App\Widgets\Types\HeroSliderWidget::class,
        'cat_swiper' => \App\Widgets\Types\CategorySliderWidget::class,
        'feature_icons' => \App\Widgets\Types\FeaturesBarWidget::class,
        'prod_featured' => \App\Widgets\Types\ProductGridWidget::class,
        'deal_flash' => \App\Widgets\Types\DealCountdownWidget::class,
        'prod_tabs' => \App\Widgets\Types\ProductTabsWidget::class,

        // Advanced
        'html_custom' => \App\Widgets\Types\HtmlBlockWidget::class,
        'newsletter_bar' => \App\Widgets\Types\NewsletterWidget::class,
        'posts_latest' => \App\Widgets\Types\LatestPostsWidget::class,
        'promo_banners' => \App\Widgets\Types\PromoBannersWidget::class,
        'testimonials' => \App\Widgets\Types\TestimonialWidget::class,

        // About Page Widgets
        'about_hero' => \App\Widgets\Types\AboutHeroWidget::class,
        'stats_counter' => \App\Widgets\Types\StatsCounterWidget::class,
        'about_intro' => \App\Widgets\Types\AboutIntroWidget::class,
        'team_list' => \App\Widgets\Types\TeamGridWidget::class,
        'choosing_reason' => \App\Widgets\Types\ChoosingReasonWidget::class,
        'about_testimonials' => \App\Widgets\Types\AboutTestimonialsWidget::class,
        'section_sep' => \App\Widgets\Types\SectionSeparatorWidget::class,
    ];

    /**
     * Định nghĩa Widget Areas.
     * key => ['label', 'description', 'icon']
     */
    public static function areas(): array
    {
        return [
            'homepage' => ['label' => 'Homepage Layout', 'description' => 'Sắp xếp các Section trên trang chủ', 'icon' => 'fa-house-chimney-window'],
            'about_page' => ['label' => 'About Page Layout', 'description' => 'Sắp xếp các Section trên trang Giới thiệu', 'icon' => 'fa-address-card'],
            'footer' => ['label' => 'Footer Columns', 'description' => 'Cài đặt chân trang', 'icon' => 'fa-table-columns'],
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
                    'fields'      => static::getFields($key),
                ];
            }
        }
        return $result;
    }

    /**
     * Lấy danh sách fields của một widget type (đã merge common fields)
     */
    public static function getFields(string $type): array
    {
        $class = static::$widgetClasses[$type] ?? null;
        if (!$class || !class_exists($class)) return [];

        $fields = $class::fields();

        if (method_exists($class, 'commonFields')) {
            $common = $class::commonFields();
            $keyedFields = [];
            foreach ($fields as $f) {
                $keyedFields[$f['key'] ?? ''] = $f;
            }
            foreach ($common as $cf) {
                if (!isset($keyedFields[$cf['key'] ?? ''])) {
                    $keyedFields[$cf['key'] ?? ''] = $cf;
                }
            }
            $fields = array_values($keyedFields);
        }

        return $fields;
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
