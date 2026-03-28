<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sale_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->enum('status', ['draft', 'active', 'ended'])->default('draft');
            $table->boolean('apply_to_all')->default(false); // áp dụng toàn bộ sản phẩm
            $table->timestamps();

            $table->index(['status', 'starts_at', 'ends_at']);
        });

        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('flash_sale_campaigns')->cascadeOnDelete();

            // Áp dụng cho sản phẩm hoặc danh mục
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            // Loại giảm giá
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->decimal('discount_value', 12, 0); // % hoặc số tiền cố định

            // Giới hạn số lượng bán trong flash sale (null = không giới hạn)
            $table->integer('sale_limit')->nullable();
            $table->integer('sold_count')->default(0);

            $table->timestamps();

            $table->index(['campaign_id', 'product_id']);
            $table->index(['campaign_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
        Schema::dropIfExists('flash_sale_campaigns');
    }
};
