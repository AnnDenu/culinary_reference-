<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Завтраки', 'slug' => 'breakfast'],
            ['name' => 'Обеды', 'slug' => 'lunch'],
            ['name' => 'Ужины', 'slug' => 'dinner'],
            ['name' => 'Десерты', 'slug' => 'desserts'],
            ['name' => 'Закуски', 'slug' => 'snacks'],
            ['name' => 'Салаты', 'slug' => 'salads'],
            ['name' => 'Супы', 'slug' => 'soups'],
            ['name' => 'Выпечка', 'slug' => 'baking'],
            ['name' => 'Напитки', 'slug' => 'drinks'],
            ['name' => 'Вегетарианские блюда', 'slug' => 'vegetarian']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 