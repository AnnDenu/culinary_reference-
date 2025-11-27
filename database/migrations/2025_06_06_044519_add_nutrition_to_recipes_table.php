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
            $table->decimal('proteins', 8, 2)->default(0)->comment('Белки в граммах');
            $table->decimal('fats', 8, 2)->default(0)->comment('Жиры в граммах');
            $table->decimal('carbs', 8, 2)->default(0)->comment('Углеводы в граммах');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['proteins', 'fats', 'carbs']);
        });
    }
};
