<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'cooking_time' => $this->faker->numberBetween(10, 120),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'is_approved' => true
        ];
    }
} 