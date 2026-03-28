<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng translations dùng polymorphic: lưu bản dịch cho bất kỳ model nào
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type');     // App\Models\Product
            $table->unsignedBigInteger('translatable_id');
            $table->string('locale', 10);            // vi, en, zh
            $table->string('field');                 // name, description, meta_title...
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(['translatable_type', 'translatable_id', 'locale', 'field'], 'translations_unique');
            $table->index(['translatable_type', 'translatable_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
