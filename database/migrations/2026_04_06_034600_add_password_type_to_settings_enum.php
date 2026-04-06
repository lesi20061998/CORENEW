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
        \DB::statement("ALTER TABLE settings MODIFY COLUMN type ENUM('text', 'boolean', 'json', 'image', 'textarea', 'color', 'select', 'integer', 'number', 'password') DEFAULT 'text'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE settings MODIFY COLUMN type ENUM('text', 'boolean', 'json', 'image', 'textarea', 'color', 'select', 'integer', 'number') DEFAULT 'text'");
    }
};
