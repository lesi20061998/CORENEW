<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadyDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại để tránh lỗi khi truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->command->info('🚀 Bắt đầu quá trình nạp dữ liệu Demo...');

        $this->call([
            // 1. Cài đặt hệ thống & Tài khoản
            AdminSeeder::class,             // Tạo user admin (admin@kalles.com / password)
            SettingSeeder::class,           // Cấu hình website
            AppearanceSettingSeeder::class, // Màu sắc, Logo, Giao diện
            VietQRSettingSeeder::class,     // Cấu hình thanh toán QR
            MediaFolderSeeder::class,       // Tạo các thư mục quản lý ảnh

            // 2. Dữ liệu Danh mục & thuộc tính
            CategorySeeder::class,          // Danh mục sản phẩm & bài viết
            AttributeSeeder::class,         // Thuộc tính: Size, Màu, Trọng lượng

            // 3. Nội dung Chính
            ProductSeeder::class,           // Tất cả sản phẩm demo (Grocery, Mart)
            PostSeeder::class,              // Các bài viết Blog tin tức
            PageSeeder::class,              // Các trang Giới thiệu, Liên hệ

            // 4. Cấu hình Trang chủ (Layout)
            WidgetSeeder::class,            // Các Banner, Slider, Section trang chủ
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('✅ HOÀN TẤT: Website đã có đầy đủ dữ liệu demo!');
    }
}
