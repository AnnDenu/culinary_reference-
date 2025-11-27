<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $recipes = Recipe::all();

        $comments = [
            [
                'comment' => 'Отличный рецепт! Очень вкусно получилось.',
                'rating' => 5,
                'user_id' => $users->first()->id,
                'recipe_id' => $recipes->first()->id
            ],
            [
                'comment' => 'Попробовал сделать, но получилось слишком солено.',
                'rating' => 3,
                'user_id' => $users->first()->id,
                'recipe_id' => $recipes->first()->id
            ],
            [
                'comment' => 'Очень простой и вкусный рецепт. Рекомендую!',
                'rating' => 5,
                'user_id' => $users->first()->id,
                'recipe_id' => $recipes->last()->id
            ]
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }
    }
} 