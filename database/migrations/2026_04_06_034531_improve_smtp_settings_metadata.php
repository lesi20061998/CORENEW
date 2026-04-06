<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add/Update MAIL_MAILER setting
        \DB::table('settings')->updateOrInsert(
            ['key' => 'mail_mailer'],
            [
                'value'       => 'smtp',
                'group'       => 'notification',
                'section'     => 'Thông tin SMTP',
                'type'        => 'select',
                'label'       => 'Mailer',
                'options'     => json_encode(['smtp' => 'SMTP', 'log' => 'Log', 'sendmail' => 'Sendmail', 'ses' => 'SES']),
                'description' => 'Chọn trình gửi email.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        // 2. Update existing SMTP fields metadata
        $smtpFields = [
            'mail_host'         => ['label' => 'SMTP Host', 'type' => 'text'],
            'mail_port'         => ['label' => 'SMTP Port', 'type' => 'number'],
            'mail_username'     => ['label' => 'SMTP Username', 'type' => 'text'],
            'mail_password'     => ['label' => 'SMTP Password', 'type' => 'password'],
            'mail_encryption'   => [
                'label'   => 'Encryption', 
                'type'    => 'select', 
                'options' => json_encode(['tls' => 'TLS', 'ssl' => 'SSL', '' => 'None'])
            ],
            'mail_from_address' => ['label' => 'Email gửi đi', 'type' => 'text'],
            'mail_from_name'    => ['label' => 'Tên người gửi', 'type' => 'text'],
        ];

        foreach ($smtpFields as $key => $data) {
            \DB::table('settings')->where('key', $key)->update(array_merge($data, [
                'group'      => 'notification',
                'section'    => 'Thông tin SMTP',
                'updated_at' => now(),
            ]));
        }

        // To make it exactly 7 fields if mail_from_name is too much?
        // Let's keep all 8 for now (Mailer + 7 others = 8).
        // If the user meant 7 fields in TOTAL including Mailer, I might need to hide one.
        // But usually Mailer, Host, Port, Username, Password, Encryption, From Address is 7.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('settings')->where('key', 'mail_mailer')->delete();
    }
};
