<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        $checks = [
            'seo_check_kw_set'           => 'Đặt Từ khóa tập trung cho nội dung',
            'seo_check_kw_in_title'      => 'Từ khóa chính trong tiêu đề SEO',
            'seo_check_kw_title_start'   => 'Từ khóa gần đầu tiêu đề SEO',
            'seo_check_title_length'     => 'Độ dài tiêu đề SEO (40–70 ký tự)',
            'seo_check_kw_in_meta'       => 'Từ khóa trong mô tả meta SEO',
            'seo_check_meta_desc_length' => 'Độ dài mô tả meta (120–160 ký tự)',
            'seo_check_kw_in_url'        => 'Từ khóa trong URL (slug)',
            'seo_check_url_length'       => 'Độ dài URL (≤ 75 ký tự)',
            'seo_check_kw_content_start' => 'Từ khóa ở đầu nội dung (200 ký tự đầu)',
            'seo_check_kw_in_content'    => 'Từ khóa xuất hiện trong nội dung',
            'seo_check_word_count'       => 'Độ dài nội dung (600–2500 từ)',
            'seo_check_internal_links'   => 'Có liên kết nội bộ trong nội dung',
            'seo_check_kw_in_headings'   => 'Từ khóa trong tiêu đề phụ H2/H3/H4',
            'seo_check_kw_in_alt'        => 'Từ khóa trong thuộc tính alt hình ảnh',
            'seo_check_kw_density'       => 'Mật độ từ khóa (0.5%–2.5%)',
            'seo_check_short_paras'      => 'Đoạn văn ngắn, súc tích (≤ 150 từ/đoạn)',
            'seo_check_has_image'        => 'Có hình ảnh trong nội dung',
        ];

        $sort = 1;
        foreach ($checks as $key => $label) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value'       => '1',
                    'group'       => 'seo',
                    'section'     => 'SEO Checklist',
                    'type'        => 'boolean',
                    'label'       => $label,
                    'description' => null,
                    'sort_order'  => $sort++,
                ]
            );
        }

        // Bật checklist cho từng loại nội dung
        foreach (['seo_checklist_post', 'seo_checklist_product', 'seo_checklist_page'] as $i => $key) {
            $labels = ['Hiển thị SEO Checklist trong Bài viết', 'Hiển thị SEO Checklist trong Sản phẩm', 'Hiển thị SEO Checklist trong Trang'];
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value'      => '1',
                    'group'      => 'seo',
                    'section'    => 'Hiển thị Checklist',
                    'type'       => 'boolean',
                    'label'      => $labels[$i],
                    'sort_order' => 100 + $i,
                ]
            );
        }
    }

    public function down(): void
    {
        $keys = [
            'seo_check_kw_set','seo_check_kw_in_title','seo_check_kw_title_start',
            'seo_check_title_length','seo_check_kw_in_meta','seo_check_meta_desc_length',
            'seo_check_kw_in_url','seo_check_url_length','seo_check_kw_content_start',
            'seo_check_kw_in_content','seo_check_word_count','seo_check_internal_links',
            'seo_check_kw_in_headings','seo_check_kw_in_alt','seo_check_kw_density',
            'seo_check_short_paras','seo_check_has_image',
            'seo_checklist_post','seo_checklist_product','seo_checklist_page',
        ];
        Setting::whereIn('key', $keys)->delete();
    }
};
