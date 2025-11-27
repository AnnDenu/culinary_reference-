<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_recipe_lifecycle()
    {
        // 1. Создание пользователей
        $author = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $regularUser = User::factory()->create();
        
        // 2. Создание категории
        $category = Category::factory()->create();
        
        // 3. Создание рецепта
        $this->actingAs($author)
            ->post('/recipes', [
                'title' => 'Test Recipe',
                'description' => 'Test Description',
                'cooking_time' => 45,
                'difficulty' => 'medium',
                'servings' => 4,
                'calories' => 500,
                'category_id' => $category->id,
                'ingredients' => [
                    [
                        'name' => 'Ingredient 1',
                        'quantity' => 100,
                        'unit' => 'г'
                    ],
                    [
                        'name' => 'Ingredient 2',
                        'quantity' => 2,
                        'unit' => 'pcs'
                    ]
                ],
                'steps' => [
                    [
                        'description' => 'Step 1 description'
                    ],
                    [
                        'description' => 'Step 2 description'
                    ]
                ]
            ]);

        // Проверяем, что рецепт создан
        $recipe = Recipe::where('title', 'Test Recipe')->first();
        $this->assertNotNull($recipe);
        $this->assertFalse($recipe->is_approved);
        $this->assertEquals('pending', $recipe->status);

        // 4. Одобрение рецепта администратором
        $this->actingAs($admin)
            ->put('/admin/recipes/' . $recipe->id . '/approve');
        
        $recipe->refresh();
        $this->assertTrue($recipe->is_approved);
        $this->assertEquals('approved', $recipe->status);

        // 5. Добавление рецепта в избранное другим пользователем
        $this->actingAs($regularUser)
            ->post('/recipes/' . $recipe->id . '/favorite');
        
        $this->assertDatabaseHas('favorites', [
            'user_id' => $regularUser->id,
            'recipe_id' => $recipe->id
        ]);

        // 6. Добавление комментария
        $this->actingAs($regularUser)
            ->post('/recipes/' . $recipe->id . '/comments', [
                'comment' => 'Great recipe!',
                'rating' => 5
            ]);
        
        $this->assertDatabaseHas('comments', [
            'user_id' => $regularUser->id,
            'recipe_id' => $recipe->id,
            'comment' => 'Great recipe!',
            'rating' => 5
        ]);

        // 7. Редактирование рецепта автором
        $this->actingAs($author)
            ->put('/recipes/' . $recipe->id, [
                'title' => 'Updated Test Recipe',
                'description' => 'Updated Description',
                'cooking_time' => 60,
                'difficulty' => 'hard',
                'servings' => 6,
                'calories' => 600,
                'category_id' => $category->id,
                'steps' => [
                    [
                        'id' => 1,
                        'description' => 'Updated step description'
                    ]
                ]
            ]);
        
        $recipe->refresh();
        $this->assertEquals('Updated Test Recipe', $recipe->title);
        $this->assertEquals('pending', $recipe->status); // Статус должен измениться на pending после редактирования

        // 8. Повторное одобрение администратором
        $this->actingAs($admin)
            ->put('/admin/recipes/' . $recipe->id . '/approve');
        
        $recipe->refresh();
        $this->assertTrue($recipe->is_approved);
        $this->assertEquals('approved', $recipe->status);

        // 9. Удаление рецепта автором
        $this->actingAs($author)
            ->delete('/recipes/' . $recipe->id);
        
        $this->assertDatabaseMissing('recipes', [
            'id' => $recipe->id
        ]);
        
        // Проверяем, что связанные записи также удалены
        $this->assertDatabaseMissing('favorites', [
            'recipe_id' => $recipe->id
        ]);
        
        $this->assertDatabaseMissing('comments', [
            'recipe_id' => $recipe->id
        ]);
    }
} 