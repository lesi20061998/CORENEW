<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'key'         => 'notification_order_confirmed_customer',
                'value'       => '1',
                'group'       => 'notification',
                'section'     => 'Email đơn hàng',
                'type'        => 'boolean',
                'label'       => 'Đơn hàng mới - Khách hàng',
                'description' => 'Gửi email xác nhận cho khách hàng khi đặt hàng thành công.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'notification_order_confirmed_admin',
                'value'       => '1',
                'group'       => 'notification',
                'section'     => 'Email đơn hàng',
                'type'        => 'boolean',
                'label'       => 'Đơn hàng mới - Quản trị',
                'description' => 'Gửi email xác nhận cho admin khi đạt hàng thành công.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'order_cancellation_reasons',
                'value'       => json_encode([
                    'KH thay đổi / KH Hủy đơn',
                    'Không liên hệ được KH',
                    'Đơn hàng sai thông tin',
                    'Sản phẩm không có sẵn'
                ], JSON_UNESCAPED_UNICODE),
                'group'       => 'notification',
                'section'     => 'Lý do hủy đơn',
                'type'        => 'text', // Use 'text' to avoid truncation issues
                'label'       => 'Cấu hình lý do hủy đơn',
                'description' => 'Quản lý các lý do khách hàng hủy đơn hàng',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'notification_order_confirmed_customer',
            'notification_order_confirmed_admin',
            'order_cancellation_reasons'
        ])->delete();
    }
};
