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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('cooking_time')->nullable(); // Время приготовления в минутах
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium'); // Сложность рецепта
            $table->integer('servings')->nullable(); // Количество порций
            $table->string('image_url')->nullable();
            $table->integer('calories')->nullable(); // Калорийность
            $table->boolean('is_approved')->default(false); // Одобрен ли администратором
            $table->unsignedBigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
