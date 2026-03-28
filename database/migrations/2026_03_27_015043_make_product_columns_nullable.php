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
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
            $table->decimal('price', 10, 2)->nullable()->change();
            $table->integer('stock')->nullable()->default(0)->change();
            $table->enum('status', ['active', 'inactive', 'draft'])->nullable()->default('active')->change();
            $table->boolean('has_variants')->nullable()->default(false)->change();
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'backorder'])->nullable()->default('in_stock')->change();
            $table->boolean('is_featured')->nullable()->default(false)->change();
            $table->integer('sort_order')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->decimal('price', 10, 2)->nullable(false)->change();
            $table->integer('stock')->nullable(false)->default(0)->change();
            $table->enum('status', ['active', 'inactive', 'draft'])->nullable(false)->default('active')->change();
            $table->boolean('has_variants')->nullable(false)->default(false)->change();
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'backorder'])->nullable(false)->default('in_stock')->change();
            $table->boolean('is_featured')->nullable(false)->default(false)->change();
            $table->integer('sort_order')->nullable(false)->default(0)->change();
        });
    }
};
