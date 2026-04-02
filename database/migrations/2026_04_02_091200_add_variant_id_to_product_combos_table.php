<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_combos', function (Blueprint $table) {
            $table->foreignId('combo_product_variant_id')->nullable()->after('combo_product_id')->constrained('product_variants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('product_combos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('combo_product_variant_id');
        });
    }
};
