<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Đổi enum type để hỗ trợ thêm textarea, color, select
        DB::statement("ALTER TABLE settings MODIFY COLUMN type ENUM('text','boolean','json','image','textarea','color','select') NOT NULL DEFAULT 'text'");

        // Thêm cột options cho type=select nếu chưa có
        if (!Schema::hasColumn('settings', 'options')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->text('options')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE settings MODIFY COLUMN type ENUM('text','boolean','json','image') NOT NULL DEFAULT 'text'");

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
