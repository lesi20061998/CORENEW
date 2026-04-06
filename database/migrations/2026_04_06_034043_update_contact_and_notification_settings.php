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
        // 1. Add map iframe to contact settings
        \DB::table('settings')->insertOrIgnore([
            'key'         => 'contact_map_iframe',
            'value'       => '',
            'group'       => 'contact',
            'section'     => 'Thông tin liên hệ',
            'type'        => 'textarea',
            'label'       => 'Bản đồ nhúng (Iframe Code)',
            'description' => 'Mã nhúng iframe Google Maps.',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // 2. Move SMTP settings from contact to notification group
        // And make sure there are 7 fields. Currently there are:
        // mail_host, mail_port, mail_username, mail_password, mail_encryption, mail_from_address, mail_from_name
        \DB::table('settings')
            ->where('section', 'Thông tin SMTP')
            ->update([
                'group'      => 'notification',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert SMTP settings to contact group
        \DB::table('settings')
            ->where('group', 'notification')
            ->whereIn('key', [
                'mail_host', 'mail_port', 'mail_username', 'mail_password', 
                'mail_encryption', 'mail_from_address', 'mail_from_name'
            ])
            ->update([
                'group'      => 'contact',
                'updated_at' => now(),
            ]);

        // Remove contact_map_iframe
        \DB::table('settings')->where('key', 'contact_map_iframe')->delete();
    }
};
