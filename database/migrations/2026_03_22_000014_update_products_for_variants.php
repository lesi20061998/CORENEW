<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('compare_price', 12, 0)->nullable()->after('price');
            $table->decimal('cost_price', 12, 0)->nullable()->after('compare_price');
            $table->string('sku')->nullable()->after('cost_price');
            $table->boolean('has_variants')->default(false)->after('sku');
            $table->enum('stock_status', ['in_stock','out_of_stock','backorder'])->default('in_stock')->after('stock');
            $table->string('weight')->nullable()->after('stock_status');
            $table->text('short_description')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['compare_price','cost_price','sku','has_variants','stock_status','weight','short_description']);
        });
    }
};
