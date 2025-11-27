<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Recipe::query();

        // Если пользователь не админ, показываем только его рецепты
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Применяем поиск
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhereHas('ingredients', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        // Применяем сортировку
        switch ($request->sort) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latest();
        }

        $recipes = $query->with(['ingredients', 'steps', 'category'])->paginate(10);
        $ingredients = Ingredient::whereIn('recipe_id', $recipes->pluck('id'))->get();
        $categories = Category::all();

        // Проверяем, является ли запрос админским
        if ($request->route()->getName() === 'admin.ingredients.index') {
            return view('admin.ingredients.index', compact('recipes', 'ingredients', 'categories'));
        }

        return view('profile.ingredients.index', compact('recipes', 'ingredients', 'categories'));
    }

    // Метод для добавления ингредиента
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'recipe_id' => 'required|exists:recipes,id',
        ]);

        $recipe = \App\Models\Recipe::findOrFail($request->recipe_id);

        // Проверка, что текущий пользователь владелец рецепта
        if ($recipe->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('У вас нет прав для добавления ингредиентов к этому рецепту.');
        }

        Ingredient::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'recipe_id' => $request->recipe_id,
            'is_approved' => true
        ]);

        return redirect()->back()->with('success', 'Ингредиент успешно добавлен.');
    }

    // Метод для обновления ингредиента
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'unit' => 'required|string|max:50',
        ]);

        // Проверка, что текущий пользователь владелец рецепта
        if ($ingredient->recipe->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('У вас нет прав для редактирования этого ингредиента.');
        }

        $ingredient->update($request->all());

        return redirect()->back()->with('success', 'Ингредиент успешно обновлен.');
    }

    // Метод для удаления ингредиента
    public function destroy(Ingredient $ingredient)
    {
        $recipe = $ingredient->recipe;

        // Проверка, что текущий пользователь владелец рецепта
        if ($recipe->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('У вас нет прав для удаления ингредиентов этого рецепта.');
        }

        $ingredient->delete();
        return redirect()->back()->with('success', 'Ингредиент успешно удален.');
    }

    // Метод для редактирования рецепта (используется пользователями для своих рецептов)
    public function edit(Recipe $recipe)
    {
        // Проверка, что текущий пользователь владелец рецепта
        if ($recipe->user_id === auth()->id()) {
            $categories = \App\Models\Category::all();
            return view('recipes.edit', compact('recipe', 'categories'));
        } else {
            return redirect()->back()->withErrors('У вас нет прав для редактирования этого рецепта.');
        }
    }

    // Метод для обновления рецепта (используется пользователями для своих рецептов)
    public function updateRecipe(Request $request, Recipe $recipe)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cooking_time' => 'required|integer|min:1',
            'calories' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|in:easy,medium,hard',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        // Проверка, что текущий пользователь владелец рецепта
        if ($recipe->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('У вас нет прав для редактирования этого рецепта.');
        }

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Удалить старое изображение, если существует
            if ($recipe->image_url) {
                Storage::delete(parse_url($recipe->image_url, PHP_URL_PATH));
            }
            // Сохранить новое изображение
            $imagePath = $request->file('image')->store('recipe_images', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        $recipe->update($data);
        $recipe->save();
        
        return redirect()->route('admin.ingredients.index')->with('success', 'Рецепт успешно обновлен.');
    }

    // Метод для удаления рецепта (используется пользователями для своих рецептов)
    public function destroyRecipe(Recipe $recipe)
    {
        // Проверка, что текущий пользователь владелец рецепта или администратор
        if ($recipe->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->withErrors('У вас нет прав для удаления этого рецепта.');
        }

        try {
            DB::beginTransaction();
            
            // Удаляем все связанные записи
            $recipe->ingredients()->delete();
            $recipe->steps()->delete();
            $recipe->comments()->delete();
            $recipe->favorites()->delete();
            $recipe->views()->delete();
            
            // Удаляем изображение рецепта, если оно есть
            if ($recipe->image_url) {
                $oldImage = str_replace('/storage/', 'public/', $recipe->image_url);
                Storage::delete($oldImage);
            }
            
            // Удаляем сам рецепт
            $recipe->delete();
            
            DB::commit();

            // Перенаправляем в зависимости от роли пользователя
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.ingredients.index')->with('success', 'Рецепт успешно удален!');
            } else {
                return redirect()->route('profile.recipes')->with('success', 'Рецепт успешно удален!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Ошибка при удалении рецепта: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Произошла ошибка при удалении рецепта. Пожалуйста, попробуйте снова.');
        }
    }

     // Метод для добавления шага к рецепту (используется пользователями)
    public function addStep(Request $request, Recipe $recipe)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        // Проверка, что текущий пользователь владелец рецепта
        if ($recipe->user_id !== auth()->id()) {
            return redirect()->back()->withErrors('У вас нет прав для добавления шагов к этому рецепту.');
        }

        // Get the next step number
        $nextStepNumber = $recipe->steps()->max('step_number') + 1;
        
        $recipe->steps()->create([
            'description' => $request->description,
            'step_number' => $nextStepNumber
        ]);

        return redirect()->back()->with('success', 'Шаг успешно добавлен.');
    }

    // Метод для обновления шага рецепта (используется пользователями)
    public function updateStep(Request $request, RecipeStep $step)
    {
         $request->validate([
            'description' => 'required|string',
        ]);

        // Проверка, что текущий пользователь владелец рецепта, к которому относится шаг
        if ($step->recipe->user_id !== auth()->id()) {
             return redirect()->back()->withErrors('У вас нет прав для редактирования этого шага.');
        }

        $step->update($request->all());

        return redirect()->back()->with('success', 'Шаг успешно обновлен.');
    }

    // Метод для удаления шага рецепта (используется пользователями)
    public function destroyStep(RecipeStep $step)
    {
        // Проверка, что текущий пользователь владелец рецепта, к которому относится шаг
         if ($step->recipe->user_id !== auth()->id()) {
             return redirect()->back()->withErrors('У вас нет прав для удаления этого шага.');
         }

         $step->delete();
         return redirect()->back()->with('success', 'Шаг успешно удален.');
    }
    public function approve(Ingredient $ingredient)
    {
        $ingredient->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Ингредиент успешно одобрен.');
    }

    public function storeStep(Request $request, Recipe $recipe)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        // Проверка, что текущий пользователь владелец рецепта ИЛИ администратор
        if ($recipe->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->withErrors('У вас нет прав для добавления шагов к этому рецепту.');
        }

        // Получаем максимальный номер шага для этого рецепта
        $maxStepNumber = $recipe->steps()->max('step_number') ?? 0;
        
        $recipe->steps()->create([
            'description' => $request->description,
            'step_number' => $maxStepNumber + 1
        ]);

        return redirect()->back()->with('success', 'Шаг успешно добавлен.');
    }
}

