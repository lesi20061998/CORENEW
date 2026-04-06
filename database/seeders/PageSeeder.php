<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::truncate();

        $pages = [
            [
                'title' => 'Giới thiệu về VietTin Mart',
                'slug' => 'gioi-thieu',
                'content' => '<h3>Chào mừng đến với VietTin Mart</h3><p>VietTin Mart là hệ thống siêu thị thực phẩm sạch hàng đầu, chuyên cung cấp các sản phẩm hữu cơ, rau củ quả tươi sống và nhu yếu phẩm chất lượng cao cho gia đình Việt.</p>',
                'status' => 'published',
                'template' => 'default',
            ],
            [
                'title' => 'Chính sách bảo mật',
                'slug' => 'chinh-sach-bao-mat',
                'content' => '<p>Chúng tôi cam kết bảo vệ thông tin cá nhân của khách hàng một cách tuyệt đối...</p>',
                'status' => 'published',
                'template' => 'default',
            ],
            [
                'title' => 'Điều khoản dịch vụ',
                'slug' => 'dieu-khoan-dich-vu',
                'content' => '<p>Bằng việc sử dụng website, bạn đồng ý với các điều khoản của chúng tôi...</p>',
                'status' => 'published',
                'template' => 'default',
            ],
            [
                'title' => 'Liên hệ',
                'slug' => 'lien-he',
                'content' => null,
                'status' => 'published',
                'template' => 'contact',
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
