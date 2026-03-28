<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();   // ORD-20260322-0001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Trạng thái
            $table->enum('status', [
                'pending','processing','confirmed',
                'shipping','delivered','completed','cancelled','refunded'
            ])->default('pending');
            $table->enum('payment_status', ['unpaid','paid','partial','refunded'])->default('unpaid');
            $table->enum('payment_method', ['cod','bank_transfer','vnpay','momo','other'])->default('cod');

            // Tiền
            $table->decimal('subtotal', 12, 0)->default(0);
            $table->decimal('discount', 12, 0)->default(0);
            $table->decimal('shipping_fee', 12, 0)->default(0);
            $table->decimal('total', 12, 0)->default(0);

            // Thông tin khách hàng
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('shipping_province')->nullable();
            $table->string('shipping_district')->nullable();
            $table->string('shipping_ward')->nullable();

            // Ghi chú
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();

            // Mã giảm giá
            $table->string('coupon_code')->nullable();

            $table->timestamps();
            $table->index(['status', 'created_at']);
            $table->index('order_number');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_label')->nullable();  // "Size: M / Màu: Đỏ"
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 12, 0);
            $table->integer('quantity');
            $table->decimal('total', 12, 0);
            $table->timestamps();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
