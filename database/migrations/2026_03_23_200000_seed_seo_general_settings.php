<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'code' type to the enum if not already present
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE settings MODIFY COLUMN type ENUM('text','boolean','json','image','textarea','color','select','code') NOT NULL DEFAULT 'text'"
        );

        $settings = [
            // Cấu hình chung
            ['key' => 'seo_favicon',       'value' => '', 'section' => 'Cấu hình chung', 'type' => 'image',    'label' => 'Favicon',                    'description' => null,                                    'sort_order' => 1],
            ['key' => 'seo_meta_title',    'value' => '', 'section' => 'Cấu hình chung', 'type' => 'text',     'label' => 'Meta title (Shop)',           'description' => null,                                    'sort_order' => 2],
            ['key' => 'seo_meta_desc',     'value' => '', 'section' => 'Cấu hình chung', 'type' => 'textarea', 'label' => 'Meta description (Mô tả trang chủ)', 'description' => null,                            'sort_order' => 3],
            ['key' => 'seo_meta_keywords', 'value' => '', 'section' => 'Cấu hình chung', 'type' => 'textarea', 'label' => 'Meta keyword (Từ khóa trang chủ)', 'description' => null,                              'sort_order' => 4],

            // Script
            ['key' => 'seo_script_header', 'value' => '', 'section' => 'Script', 'type' => 'code', 'label' => 'Script Header', 'description' => 'Chèn các thẻ script, meta vào cuối thẻ <head> của trang.', 'sort_order' => 10],
            ['key' => 'seo_script_body',   'value' => '', 'section' => 'Script', 'type' => 'code', 'label' => 'Script Body',   'description' => 'Chèn code vào ngay sau thẻ <body> mở đầu trang.',          'sort_order' => 11],
            ['key' => 'seo_script_footer', 'value' => '', 'section' => 'Script', 'type' => 'code', 'label' => 'Script Footer', 'description' => 'Chèn code vào cuối trang, trước thẻ </body>.',              'sort_order' => 12],

            // Robots
            ['key' => 'seo_robots_txt', 'value' => "User-agent: *\nDisallow: /search\nDisallow: /cart\nDisallow: /none", 'section' => 'File Robots', 'type' => 'code', 'label' => 'Nội dung file robots', 'description' => 'Điều hướng các robot tìm kiếm cho phép hoặc không cho phép tìm kiếm file, thư mục.', 'sort_order' => 20],

            // 404 Redirect
            ['key' => 'seo_redirect_mode',    'value' => 'manual',  'section' => 'Chuyển hướng 404', 'type' => 'select',  'label' => 'Chuyển hướng đến',   'description' => null, 'sort_order' => 30],
            ['key' => 'seo_redirect_url',     'value' => 'trang-chu','section' => 'Chuyển hướng 404', 'type' => 'text',    'label' => 'URL tùy chỉnh',      'description' => 'Nhập URL tùy chỉnh (không có https://) để sử dụng làm trang chuyển hướng.', 'sort_order' => 31],
            ['key' => 'seo_redirect_log_404', 'value' => '1',        'section' => 'Chuyển hướng 404', 'type' => 'boolean', 'label' => 'Nhật ký 404 đổi',    'description' => 'Ghi lại các trang 404 để xem xét.', 'sort_order' => 32],
        ];

        foreach ($settings as $data) {
            Setting::updateOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['group' => 'seo'])
            );
        }
    }

    public function down(): void
    {
        // Revert enum (remove 'code' type)
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE settings MODIFY COLUMN type ENUM('text','boolean','json','image','textarea','color','select') NOT NULL DEFAULT 'text'"
        );

        $keys = [
            'seo_favicon','seo_meta_title','seo_meta_desc','seo_meta_keywords',
            'seo_script_header','seo_script_body','seo_script_footer',
            'seo_robots_txt',
            'seo_redirect_mode','seo_redirect_url','seo_redirect_log_404',
        ];
        Setting::whereIn('key', $keys)->delete();
    }
};
