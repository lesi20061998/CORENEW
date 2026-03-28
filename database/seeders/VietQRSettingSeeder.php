<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VietQRSettingSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'key'         => 'vietqr_bank_id',
                'value'       => '',
                'group'       => 'payment',
                'section'     => 'VietQR',
                'sort_order'  => 1,
                'type'        => 'text',
                'label'       => 'Bank ID',
                'description' => 'Mã BIN hoặc tên ngân hàng, vd: mbbank, vietinbank, 970415',
                'options'     => null,
            ],
            [
                'key'         => 'vietqr_account_no',
                'value'       => '',
                'group'       => 'payment',
                'section'     => 'VietQR',
                'sort_order'  => 2,
                'type'        => 'text',
                'label'       => 'Số tài khoản',
                'description' => 'Số tài khoản nhận tiền (tối đa 19 ký tự)',
                'options'     => null,
            ],
            [
                'key'         => 'vietqr_account_name',
                'value'       => '',
                'group'       => 'payment',
                'section'     => 'VietQR',
                'sort_order'  => 3,
                'type'        => 'text',
                'label'       => 'Tên người thụ hưởng',
                'description' => 'Tên hiển thị trên ảnh QR',
                'options'     => null,
            ],
            [
                'key'         => 'vietqr_template',
                'value'       => 'compact2',
                'group'       => 'payment',
                'section'     => 'VietQR',
                'sort_order'  => 4,
                'type'        => 'select',
                'label'       => 'Template QR',
                'description' => 'Kiểu hiển thị ảnh QR',
                'options'     => json_encode([
                    'compact2' => 'compact2 — 540x640 (khuyên dùng)',
                    'compact'  => 'compact — 540x540',
                    'qr_only'  => 'qr_only — 480x480 (chỉ QR)',
                    'print'    => 'print — 600x776 (đầy đủ)',
                ]),
            ],
            [
                'key'         => 'vietqr_description',
                'value'       => 'Thanh toan don hang',
                'group'       => 'payment',
                'section'     => 'VietQR',
                'sort_order'  => 5,
                'type'        => 'text',
                'label'       => 'Nội dung chuyển khoản mặc định',
                'description' => 'Toi da 50 ky tu, khong ky tu dac biet. Ma don hang se duoc them tu dong.',
                'options'     => null,
            ],
        ];

        foreach ($rows as $row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
            DB::table('settings')->updateOrInsert(['key' => $row['key']], $row);
        }
    }
}
