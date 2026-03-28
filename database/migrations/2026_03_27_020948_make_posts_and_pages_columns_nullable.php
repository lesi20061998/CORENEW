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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
            $table->enum('status', ['published', 'draft', 'scheduled'])->nullable()->default('draft')->change();
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->boolean('is_featured')->nullable()->default(false)->change();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
            $table->enum('status', ['published', 'draft'])->nullable()->default('draft')->change();
            $table->string('template')->nullable()->default('default')->change();
            $table->integer('sort_order')->nullable()->default(0)->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
            $table->string('type')->nullable()->default('post')->change();
            $table->boolean('is_active')->nullable()->default(true)->change();
            $table->integer('sort_order')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed or just leave empty as it's a relaxation of rules
    }
};
