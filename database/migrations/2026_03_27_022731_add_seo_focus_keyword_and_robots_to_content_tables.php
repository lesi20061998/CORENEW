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
        foreach (['posts', 'products', 'pages', 'categories'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'seo_focus_keyword')) {
                    $table->string('seo_focus_keyword')->nullable();
                }
                if (!Schema::hasColumn($table->getTable(), 'robots_meta')) {
                    $table->json('robots_meta')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['posts', 'products', 'pages', 'categories'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['seo_focus_keyword', 'robots_meta']);
            });
        }
    }
};
