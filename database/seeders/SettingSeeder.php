<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ══════════════════════════════════════════════════════════════
            // GROUP: contact
            // ══════════════════════════════════════════════════════════════

            // Section: Thông tin liên hệ
            ['key' => 'contact_email',      'value' => '', 'group' => 'contact', 'section' => 'Thông tin liên hệ',   'sort_order' => 1,  'type' => 'text',     'label' => 'Email',           'description' => 'Email liên hệ dùng để nhận mail'],
            ['key' => 'contact_phone',      'value' => '', 'group' => 'contact', 'section' => 'Thông tin liên hệ',   'sort_order' => 2,  'type' => 'text',     'label' => 'Điện Thoại',      'description' => 'Số điện thoại chăm sóc khách hàng, hotline tư vấn...'],
            ['key' => 'contact_address',    'value' => '', 'group' => 'contact', 'section' => 'Thông tin liên hệ',   'sort_order' => 3,  'type' => 'textarea', 'label' => 'Địa chỉ',         'description' => 'Địa chỉ công ty, shop của bạn.'],
            ['key' => 'contact_phone_2',    'value' => '', 'group' => 'contact', 'section' => 'Thông tin liên hệ',   'sort_order' => 4,  'type' => 'text',     'label' => 'Điện Thoại (2)',   'description' => 'Số điện thoại phụ (Số thứ 2)'],
            ['key' => 'contact_map_url',    'value' => '', 'group' => 'contact', 'section' => 'Thông tin liên hệ',   'sort_order' => 5,  'type' => 'text',     'label' => 'Link Google Map',  'description' => ''],
            ['key' => 'contact_zalo',       'value' => '', 'group' => 'contact', 'section' => 'Thông tin liên hệ',   'sort_order' => 6,  'type' => 'text',     'label' => 'Số Zalo',          'description' => ''],
            ['key' => 'contact_fb_msg_id',  'value' => '', 'group' => 'contact', 'section' => 'Thông tin liên hệ',   'sort_order' => 7,  'type' => 'text',     'label' => 'Facebook Message ID', 'description' => ''],

            // Section: Thông tin SMTP
            ['key' => 'mail_host',          'value' => '',     'group' => 'contact', 'section' => 'Thông tin SMTP', 'sort_order' => 1,  'type' => 'text',  'label' => 'SMTP Host',        'description' => ''],
            ['key' => 'mail_port',          'value' => '587',  'group' => 'contact', 'section' => 'Thông tin SMTP', 'sort_order' => 2,  'type' => 'text',  'label' => 'SMTP Port',        'description' => ''],
            ['key' => 'mail_username',      'value' => '',     'group' => 'contact', 'section' => 'Thông tin SMTP', 'sort_order' => 3,  'type' => 'text',  'label' => 'SMTP Username',    'description' => ''],
            ['key' => 'mail_password',      'value' => '',     'group' => 'contact', 'section' => 'Thông tin SMTP', 'sort_order' => 4,  'type' => 'text',  'label' => 'SMTP Password',    'description' => ''],
            ['key' => 'mail_from_address',  'value' => '',     'group' => 'contact', 'section' => 'Thông tin SMTP', 'sort_order' => 5,  'type' => 'text',  'label' => 'From Email',       'description' => ''],
            ['key' => 'mail_from_name',     'value' => 'Kalles Store', 'group' => 'contact', 'section' => 'Thông tin SMTP', 'sort_order' => 6, 'type' => 'text', 'label' => 'From Name', 'description' => ''],
            ['key' => 'mail_encryption',    'value' => 'tls',  'group' => 'contact', 'section' => 'Thông tin SMTP', 'sort_order' => 7,  'type' => 'text',  'label' => 'Encryption',       'description' => 'tls hoặc ssl'],

            // ══════════════════════════════════════════════════════════════
            // GROUP: fonts
            // ══════════════════════════════════════════════════════════════

            // Section: CMS Fonts
            ['key' => 'font_1_key',         'value' => '',      'group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 1,  'type' => 'text',  'label' => 'Font Key',    'description' => 'Tên định danh font (vd: primary)'],
            ['key' => 'font_1_type',        'value' => 'google','group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 2,  'type' => 'text',  'label' => 'Font Type',   'description' => 'google / custom / system'],
            ['key' => 'font_1_label',       'value' => '',      'group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 3,  'type' => 'text',  'label' => 'Font Label',  'description' => 'Tên hiển thị (vd: Inter)'],
            ['key' => 'font_1_load',        'value' => '',      'group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 4,  'type' => 'text',  'label' => 'Font Load',   'description' => 'URL hoặc @import để load font'],
            ['key' => 'font_2_key',         'value' => '',      'group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 5,  'type' => 'text',  'label' => 'Font Key (2)',    'description' => ''],
            ['key' => 'font_2_type',        'value' => 'google','group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 6,  'type' => 'text',  'label' => 'Font Type (2)',   'description' => ''],
            ['key' => 'font_2_label',       'value' => '',      'group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 7,  'type' => 'text',  'label' => 'Font Label (2)',  'description' => ''],
            ['key' => 'font_2_load',        'value' => '',      'group' => 'fonts', 'section' => 'CMS Fonts', 'sort_order' => 8,  'type' => 'text',  'label' => 'Font Load (2)',   'description' => ''],

            // ══════════════════════════════════════════════════════════════
            // GROUP: toc
            // ══════════════════════════════════════════════════════════════

            ['key' => 'toc_enabled',        'value' => '1',       'group' => 'toc', 'section' => 'TOC', 'sort_order' => 1, 'type' => 'boolean', 'label' => 'Bật mục lục tự động',          'description' => ''],
            ['key' => 'toc_title',          'value' => 'Mục lục', 'group' => 'toc', 'section' => 'TOC', 'sort_order' => 2, 'type' => 'text',    'label' => 'Tiêu đề TOC',                  'description' => ''],
            ['key' => 'toc_min_headings',   'value' => '3',       'group' => 'toc', 'section' => 'TOC', 'sort_order' => 3, 'type' => 'text',    'label' => 'Số heading tối thiểu',         'description' => 'Số lượng heading tối thiểu để hiện TOC'],
            ['key' => 'toc_heading_tags',   'value' => 'h2,h3',   'group' => 'toc', 'section' => 'TOC', 'sort_order' => 4, 'type' => 'text',    'label' => 'Heading tags',                 'description' => 'Các tag heading dùng để tạo TOC (vd: h2,h3,h4)'],

            // ══════════════════════════════════════════════════════════════
            // GROUP: social
            // ══════════════════════════════════════════════════════════════
            ['key' => 'social_links', 'value' => json_encode([
                'facebook'  => '',
                'instagram' => '',
                'tiktok'    => '',
                'zalo'      => '',
                'youtube'   => '',
                'twitter'   => '',
                'pinterest' => '',
            ]), 'group' => 'social', 'section' => 'Liên kết mạng xã hội', 'sort_order' => 1, 'type' => 'json', 'label' => 'Cài đặt liên kết MXH', 'description' => 'Tất cả liên kết mạng xã hội được lưu trong một JSON'],

            // ══════════════════════════════════════════════════════════════
            // GROUP: general
            // ══════════════════════════════════════════════════════════════

            ['key' => 'site_name',          'value' => 'Kalles Store', 'group' => 'general', 'section' => 'Cài đặt chung', 'sort_order' => 1, 'type' => 'text',  'label' => 'Tên website',         'description' => ''],
            ['key' => 'site_logo',          'value' => '',             'group' => 'general', 'section' => 'Cài đặt chung', 'sort_order' => 2, 'type' => 'image', 'label' => 'Logo',                'description' => ''],
            ['key' => 'favicon',            'value' => '',             'group' => 'general', 'section' => 'Cài đặt chung', 'sort_order' => 3, 'type' => 'image', 'label' => 'Favicon',             'description' => ''],
            ['key' => 'currency',           'value' => 'VND',          'group' => 'general', 'section' => 'Cài đặt chung', 'sort_order' => 4, 'type' => 'text',  'label' => 'Mã tiền tệ',          'description' => ''],
            ['key' => 'currency_symbol',    'value' => '₫',            'group' => 'general', 'section' => 'Cài đặt chung', 'sort_order' => 5, 'type' => 'text',  'label' => 'Ký hiệu tiền tệ',     'description' => ''],
            ['key' => 'products_per_page',  'value' => '12',           'group' => 'general', 'section' => 'Cài đặt chung', 'sort_order' => 6, 'type' => 'text',  'label' => 'Sản phẩm mỗi trang',  'description' => ''],

            // ══════════════════════════════════════════════════════════════
            // GROUP: payment
            // ══════════════════════════════════════════════════════════════

            ['key' => 'cod_enabled',            'value' => '1',  'group' => 'payment', 'section' => 'Thanh toán',        'sort_order' => 1, 'type' => 'boolean', 'label' => 'COD (Thanh toán khi nhận hàng)', 'description' => ''],
            ['key' => 'bank_transfer_enabled',  'value' => '1',  'group' => 'payment', 'section' => 'Thanh toán',        'sort_order' => 2, 'type' => 'boolean', 'label' => 'Chuyển khoản ngân hàng',         'description' => ''],
            ['key' => 'bank_name',              'value' => '',   'group' => 'payment', 'section' => 'Thông tin ngân hàng','sort_order' => 1, 'type' => 'text',    'label' => 'Tên ngân hàng',                  'description' => ''],
            ['key' => 'bank_account_number',    'value' => '',   'group' => 'payment', 'section' => 'Thông tin ngân hàng','sort_order' => 2, 'type' => 'text',    'label' => 'Số tài khoản',                   'description' => ''],
            ['key' => 'bank_account_name',      'value' => '',   'group' => 'payment', 'section' => 'Thông tin ngân hàng','sort_order' => 3, 'type' => 'text',    'label' => 'Tên chủ tài khoản',              'description' => ''],
            ['key' => 'bank_branch',            'value' => '',   'group' => 'payment', 'section' => 'Thông tin ngân hàng','sort_order' => 4, 'type' => 'text',    'label' => 'Chi nhánh',                      'description' => ''],
            ['key' => 'vnpay_enabled',          'value' => '0',  'group' => 'payment', 'section' => 'VNPay',             'sort_order' => 1, 'type' => 'boolean', 'label' => 'Bật VNPay',                      'description' => ''],
            ['key' => 'vnpay_tmn_code',         'value' => '',   'group' => 'payment', 'section' => 'VNPay',             'sort_order' => 2, 'type' => 'text',    'label' => 'TMN Code',                       'description' => ''],
            ['key' => 'vnpay_hash_secret',      'value' => '',   'group' => 'payment', 'section' => 'VNPay',             'sort_order' => 3, 'type' => 'text',    'label' => 'Hash Secret',                    'description' => ''],

            // Section: VietQR
            ['key' => 'vietqr_bank_id',         'value' => '',   'group' => 'payment', 'section' => 'VietQR',            'sort_order' => 1, 'type' => 'text',    'label' => 'Bank ID',                        'description' => 'Mã BIN hoặc tên ngân hàng, vd: mbbank, vietinbank, 970415'],

            // ══════════════════════════════════════════════════════════════
            // GROUP: shipping
            // ══════════════════════════════════════════════════════════════

            ['key' => 'free_shipping_threshold','value' => '500000', 'group' => 'shipping', 'section' => 'Vận chuyển', 'sort_order' => 1, 'type' => 'text',    'label' => 'Miễn phí vận chuyển từ (VNĐ)',    'description' => ''],
            ['key' => 'default_shipping_fee',   'value' => '30000',  'group' => 'shipping', 'section' => 'Vận chuyển', 'sort_order' => 2, 'type' => 'text',    'label' => 'Phí vận chuyển mặc định (VNĐ)',   'description' => ''],
            ['key' => 'ghn_enabled',            'value' => '0',      'group' => 'shipping', 'section' => 'GHN',        'sort_order' => 1, 'type' => 'boolean', 'label' => 'Bật GHN',                         'description' => ''],
            ['key' => 'ghn_token',              'value' => '',       'group' => 'shipping', 'section' => 'GHN',        'sort_order' => 2, 'type' => 'text',    'label' => 'GHN API Token',                   'description' => ''],
            ['key' => 'ghn_shop_id',            'value' => '',       'group' => 'shipping', 'section' => 'GHN',        'sort_order' => 3, 'type' => 'text',    'label' => 'GHN Shop ID',                     'description' => ''],

            // ══════════════════════════════════════════════════════════════
            // GROUP: review
            // ══════════════════════════════════════════════════════════════

            ['key' => 'review_enabled',         'value' => '1', 'group' => 'review', 'section' => 'Đánh giá', 'sort_order' => 1, 'type' => 'boolean', 'label' => 'Bật đánh giá sản phẩm',              'description' => ''],
            ['key' => 'review_auto_approve',     'value' => '0', 'group' => 'review', 'section' => 'Đánh giá', 'sort_order' => 2, 'type' => 'boolean', 'label' => 'Tự động duyệt đánh giá',             'description' => ''],
            ['key' => 'review_require_purchase', 'value' => '1', 'group' => 'review', 'section' => 'Đánh giá', 'sort_order' => 3, 'type' => 'boolean', 'label' => 'Chỉ người đã mua mới được đánh giá', 'description' => ''],
            ['key' => 'review_max_stars',        'value' => '5', 'group' => 'review', 'section' => 'Đánh giá', 'sort_order' => 4, 'type' => 'text',    'label' => 'Số sao tối đa',                      'description' => ''],

            // ══════════════════════════════════════════════════════════════
            // GROUP: button
            // ══════════════════════════════════════════════════════════════

            ['key' => 'btn_zalo_enabled',       'value' => '1', 'group' => 'button', 'section' => 'Button liên hệ', 'sort_order' => 1, 'type' => 'boolean', 'label' => 'Hiện nút Zalo',        'description' => ''],
            ['key' => 'btn_zalo_number',        'value' => '',  'group' => 'button', 'section' => 'Button liên hệ', 'sort_order' => 2, 'type' => 'text',    'label' => 'Số Zalo',              'description' => ''],
            ['key' => 'btn_messenger_enabled',  'value' => '0', 'group' => 'button', 'section' => 'Button liên hệ', 'sort_order' => 3, 'type' => 'boolean', 'label' => 'Hiện nút Messenger',   'description' => ''],
            ['key' => 'btn_messenger_page_id',  'value' => '',  'group' => 'button', 'section' => 'Button liên hệ', 'sort_order' => 4, 'type' => 'text',    'label' => 'Facebook Page ID',     'description' => ''],
            ['key' => 'btn_phone_enabled',      'value' => '1', 'group' => 'button', 'section' => 'Button liên hệ', 'sort_order' => 5, 'type' => 'boolean', 'label' => 'Hiện nút gọi điện',   'description' => ''],
            ['key' => 'btn_phone_number',       'value' => '',  'group' => 'button', 'section' => 'Button liên hệ', 'sort_order' => 6, 'type' => 'text',    'label' => 'Số điện thoại',        'description' => ''],

            // ══════════════════════════════════════════════════════════════
            // GROUP: redirect
            // ══════════════════════════════════════════════════════════════

            ['key' => 'redirect_404_enabled',   'value' => '1', 'group' => 'redirect', 'section' => '404 Redirect', 'sort_order' => 1, 'type' => 'boolean', 'label' => 'Bật redirect 404',       'description' => ''],
            ['key' => 'redirect_404_default',   'value' => '/', 'group' => 'redirect', 'section' => '404 Redirect', 'sort_order' => 2, 'type' => 'text',    'label' => 'URL mặc định khi 404',   'description' => ''],

            // ══════════════════════════════════════════════════════════════
            // GROUP: seo
            // ══════════════════════════════════════════════════════════════

            ['key' => 'meta_title',             'value' => 'Kalles Store',          'group' => 'seo', 'section' => 'SEO', 'sort_order' => 1, 'type' => 'text',     'label' => 'Meta Title mặc định',       'description' => ''],
            ['key' => 'meta_description',       'value' => '',                      'group' => 'seo', 'section' => 'SEO', 'sort_order' => 2, 'type' => 'textarea', 'label' => 'Meta Description mặc định', 'description' => ''],
            ['key' => 'meta_keywords',          'value' => '',                      'group' => 'seo', 'section' => 'SEO', 'sort_order' => 3, 'type' => 'text',     'label' => 'Meta Keywords',             'description' => ''],
            ['key' => 'og_image',               'value' => '',                      'group' => 'seo', 'section' => 'SEO', 'sort_order' => 4, 'type' => 'image',    'label' => 'OG Image mặc định',         'description' => ''],
            ['key' => 'google_site_verification','value' => '',                     'group' => 'seo', 'section' => 'SEO', 'sort_order' => 5, 'type' => 'text',     'label' => 'Google Site Verification',  'description' => ''],
            ['key' => 'robots_txt',             'value' => "User-agent: *\nAllow: /", 'group' => 'seo', 'section' => 'SEO', 'sort_order' => 6, 'type' => 'textarea', 'label' => 'Nội dung robots.txt',   'description' => ''],

            // Section: Bật SEO cho nội dung
            ['key' => 'seo_products_enabled',   'value' => '1', 'group' => 'seo', 'section' => 'Bật SEO cho nội dung', 'sort_order' => 1, 'type' => 'boolean', 'label' => 'SEO cho Sản phẩm',   'description' => 'Hiển thị tab SEO (meta title, description, schema...) khi tạo/sửa sản phẩm'],
            ['key' => 'seo_posts_enabled',      'value' => '1', 'group' => 'seo', 'section' => 'Bật SEO cho nội dung', 'sort_order' => 2, 'type' => 'boolean', 'label' => 'SEO cho Bài viết',   'description' => 'Hiển thị tab SEO khi tạo/sửa bài viết'],
            ['key' => 'seo_pages_enabled',      'value' => '1', 'group' => 'seo', 'section' => 'Bật SEO cho nội dung', 'sort_order' => 3, 'type' => 'boolean', 'label' => 'SEO cho Trang tĩnh', 'description' => 'Hiển thị tab SEO khi tạo/sửa trang tĩnh'],
            ['key' => 'seo_categories_enabled', 'value' => '1', 'group' => 'seo', 'section' => 'Bật SEO cho nội dung', 'sort_order' => 4, 'type' => 'boolean', 'label' => 'SEO cho Danh mục',   'description' => 'Hiển thị các trường SEO khi tạo/sửa danh mục'],

            // ══════════════════════════════════════════════════════════════
            // GROUP: tracking
            // ══════════════════════════════════════════════════════════════

            ['key' => 'ga4_id',     'value' => '', 'group' => 'tracking', 'section' => 'Tracking', 'sort_order' => 1, 'type' => 'text', 'label' => 'GA4 Measurement ID',    'description' => ''],
            ['key' => 'fb_pixel_id','value' => '', 'group' => 'tracking', 'section' => 'Tracking', 'sort_order' => 2, 'type' => 'text', 'label' => 'Facebook Pixel ID',     'description' => ''],
            ['key' => 'gtm_id',     'value' => '', 'group' => 'tracking', 'section' => 'Tracking', 'sort_order' => 3, 'type' => 'text', 'label' => 'Google Tag Manager ID', 'description' => ''],

            // ══════════════════════════════════════════════════════════════
            // GROUP: shop
            // ══════════════════════════════════════════════════════════════

            ['key' => 'price_filter_type',  'value' => 'slider',  'group' => 'shop', 'section' => 'Bộ lọc giá', 'sort_order' => 1, 'type' => 'select',   'label' => 'Kiểu bộ lọc giá',   'description' => 'Chọn kiểu hiển thị bộ lọc giá trên trang shop', 'options' => '{"slider":"Thanh kéo (Slider)","presets":"Mốc giá nhanh (Presets)","both":"Cả hai"}'],
            ['key' => 'price_presets',      'value' => '[{"label":"Dưới 200k","min":0,"max":200000},{"label":"200k – 500k","min":200000,"max":500000},{"label":"500k – 1tr","min":500000,"max":1000000},{"label":"Trên 1tr","min":1000000,"max":0}]', 'group' => 'shop', 'section' => 'Bộ lọc giá', 'sort_order' => 2, 'type' => 'textarea', 'label' => 'Mốc giá nhanh (JSON)', 'description' => 'Mảng JSON: [{"label":"Tên","min":0,"max":200000},...]. max=0 nghĩa là không giới hạn trên.'],

            // ══════════════════════════════════════════════════════════════
            // GROUP: ghost_notification
            // ══════════════════════════════════════════════════════════════

            ['key' => 'ghost_notif_enabled',   'value' => '0',  'group' => 'ghost_notification', 'section' => 'Thông báo ảo', 'sort_order' => 1, 'type' => 'boolean',  'label' => 'Bật thông báo đơn hàng ảo',          'description' => ''],
            ['key' => 'ghost_notif_interval',  'value' => '30', 'group' => 'ghost_notification', 'section' => 'Thông báo ảo', 'sort_order' => 2, 'type' => 'text',     'label' => 'Khoảng cách hiển thị (giây)',         'description' => ''],
            ['key' => 'ghost_notif_duration',  'value' => '5',  'group' => 'ghost_notification', 'section' => 'Thông báo ảo', 'sort_order' => 3, 'type' => 'text',     'label' => 'Thời gian hiển thị (giây)',           'description' => ''],
            ['key' => 'ghost_notif_names',     'value' => '',   'group' => 'ghost_notification', 'section' => 'Thông báo ảo', 'sort_order' => 4, 'type' => 'textarea', 'label' => 'Danh sách tên (mỗi dòng 1 tên)',      'description' => ''],
            ['key' => 'ghost_notif_locations', 'value' => '',   'group' => 'ghost_notification', 'section' => 'Thông báo ảo', 'sort_order' => 5, 'type' => 'textarea', 'label' => 'Danh sách địa điểm (mỗi dòng 1 nơi)', 'description' => ''],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
