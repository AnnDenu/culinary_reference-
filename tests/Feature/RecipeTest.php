<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_recipes_list()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/recipes');
        $response->assertStatus(200);
    }

    public function test_guest_can_view_recipe_details()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();
        
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Recipe',
            'description' => 'Test Description',
            'cooking_time' => 30,
            'difficulty' => 'medium',
            'is_approved' => true
        ]);
        
        $response = $this->get('/recipes/' . $recipe->id);
        
        $response->assertStatus(200);
        $response->assertSee('Test Recipe');
    }

    public function test_authenticated_user_can_add_recipe_to_favorites()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $recipe = Recipe::factory()->create([
            'user_id' => User::factory()->create()->id,
            'category_id' => $category->id,
            'is_approved' => true
        ]);

        $response = $this->actingAs($user)
            ->post('/recipes/' . $recipe->id . '/favorite');

        $response->assertStatus(302); // Redirect after successful addition
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id
        ]);
    }

    public function test_authenticated_user_can_create_recipe()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->post('/recipes', [
                'title' => 'New Recipe',
                'description' => 'Recipe Description',
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

        $response->assertStatus(302); // Redirect after successful creation
        $this->assertDatabaseHas('recipes', [
            'title' => 'New Recipe',
            'user_id' => $user->id
        ]);
    }

    public function test_recipe_owner_can_edit_recipe()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($user)
            ->put('/recipes/' . $recipe->id, [
                'title' => 'Updated Recipe',
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

        $response->assertStatus(302); // Redirect after successful update
        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'title' => 'Updated Recipe'
        ]);
    }

    public function test_recipe_owner_can_delete_recipe()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $recipe = Recipe::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($user)
            ->delete('/recipes/' . $recipe->id);

        $response->assertStatus(302); // Redirect after successful deletion
        $this->assertDatabaseMissing('recipes', [
            'id' => $recipe->id
        ]);
    }

    public function test_guest_cannot_create_recipe()
    {
        $category = Category::factory()->create();

        $response = $this->post('/recipes', [
            'title' => 'New Recipe',
            'description' => 'Recipe Description',
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
                ]
            ],
            'steps' => [
                [
                    'description' => 'Step 1 description'
                ]
            ]
        ]);

        $response->assertStatus(302); // Redirect to login
        $this->assertDatabaseMissing('recipes', [
            'title' => 'New Recipe'
        ]);
    }
} 