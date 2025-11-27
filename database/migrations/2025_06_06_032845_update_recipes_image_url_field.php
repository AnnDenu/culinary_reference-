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
        Schema::table('recipes', function (Blueprint $table) {
            // Сначала удаляем старое поле
            $table->dropColumn('image_url');
        });

        Schema::table('recipes', function (Blueprint $table) {
            // Добавляем новое поле с поддержкой более длинных путей
            $table->string('image_url', 1000)->nullable()->after('servings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // При откате возвращаем старое поле
            $table->dropColumn('image_url');
            $table->string('image_url')->nullable()->after('servings');
        });
    }
};
