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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->integer('rating')->default(5);
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('comment')->nullable();
            $table->text('reply')->nullable();
            $table->json('images')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'spam'])->default('pending');
            $table->boolean('is_auto_generated')->default(false);
            $table->string('ip_address')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Seed default Review Settings into existing 'settings' table
        $reviewSettings = [
            ['key' => 'review_product_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'review'],
            ['key' => 'review_post_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'review'],
            ['key' => 'review_auto_approve', 'value' => '1', 'type' => 'boolean', 'group' => 'review'],
            ['key' => 'review_star_color', 'value' => '#ffc107', 'type' => 'color', 'group' => 'review'],
            ['key' => 'review_forbidden_keywords', 'value' => 'tệ, kém, ghét', 'type' => 'text', 'group' => 'review'],
            ['key' => 'review_display_position', 'value' => 'center', 'type' => 'text', 'group' => 'review'],
            ['key' => 'review_sort_order', 'value' => '45', 'type' => 'integer', 'group' => 'review'],
            ['key' => 'review_allow_reply', 'value' => 'all', 'type' => 'text', 'group' => 'review'],
            
            ['key' => 'review_auto_gen_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'review'],
            ['key' => 'review_auto_gen_min', 'value' => '0', 'type' => 'integer', 'group' => 'review'],
            ['key' => 'review_auto_gen_max', 'value' => '10', 'type' => 'integer', 'group' => 'review'],
            ['key' => 'review_auto_gen_ratio_5', 'value' => '90', 'type' => 'integer', 'group' => 'review'],
            ['key' => 'review_auto_gen_ratio_4', 'value' => '30', 'type' => 'integer', 'group' => 'review'],
            ['key' => 'review_auto_gen_ratio_3', 'value' => '0', 'type' => 'integer', 'group' => 'review'],
        ];

        foreach ($reviewSettings as $s) {
            \DB::table('settings')->updateOrInsert(['key' => $s['key']], $s);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
