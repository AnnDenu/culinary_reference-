<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $categories = Category::all();

        $recipes = [
            [
                'title' => 'Омлет с овощами',
                'description' => 'Вкусный и полезный завтрак с овощами',
                'cooking_time' => 15,
                'difficulty' => 'easy',
                'servings' => 2,
                'calories' => 250,
                'category_id' => $categories->where('slug', 'breakfast')->first()->id,
                'user_id' => $user->id,
                'is_approved' => true,
                'status' => 'approved',
                'image_url' => 'https://images.unsplash.com/photo-1551782450-17144efb9c50?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80'
            ],
            [
                'title' => 'Греческий салат',
                'description' => 'Классический греческий салат с фетой',
                'cooking_time' => 20,
                'difficulty' => 'easy',
                'servings' => 4,
                'calories' => 180,
                'category_id' => $categories->where('slug', 'salads')->first()->id,
                'user_id' => $user->id,
                'is_approved' => true,
                'status' => 'approved',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1160&q=80'
            ],
            [
                'title' => 'Тыквенный суп',
                'description' => 'Нежный крем-суп из тыквы',
                'cooking_time' => 40,
                'difficulty' => 'medium',
                'servings' => 6,
                'calories' => 150,
                'category_id' => $categories->where('slug', 'soups')->first()->id,
                'user_id' => $user->id,
                'is_approved' => true,
                'status' => 'approved',
                'image_url' => 'https://avatars.mds.yandex.net/get-entity_search/4787573/952289863/S600xU_2x'
            ]
        ];

        foreach ($recipes as $recipe) {
            Recipe::create($recipe);
        }
    }
} 