<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\Favorite;
use App\Models\RecipeStep;
use App\Models\Ingredient;
use App\Models\RecipeView;
use App\Notifications\RecipeRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $favorites = $user->favorites;

            if ($user->role === 'admin') {
                $recipes = Recipe::all();
            } else {
                $recipes = Recipe::where('is_approved', true)
                    ->where('user_id', $user->id)
                    ->paginate(8); 
            }

            $comments = Comment::with('recipe')->where('user_id', $user->id)->latest()->get();
            $categories = Category::all();

            return view('recipes.index', compact('recipes', 'categories', 'favorites', 'comments'));
        }

        return redirect()->route('login')->with('error', 'Для доступа к рецептам необходимо авторизоваться.');
    }

    public function show($id)
    {
        $recipe = Recipe::with('comments.user', 'ingredients', 'steps', 'category')->findOrFail($id);

        // Записываем просмотр в историю, если пользователь авторизован
        if (auth()->check()) {
            RecipeView::updateOrCreate(
                ['user_id' => auth()->id(), 'recipe_id' => $id],
                ['updated_at' => now()] // Обновляем время просмотра
            );
        }
        return view('recipes.show', compact('recipe'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('recipes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'calories' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|in:easy,medium,hard',
            'cooking_time' => 'required|integer|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'proteins' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Загрузка основного изображения рецепта
            $imagePath = $request->file('image')->store('recipe-images', 'public');

            // Создание рецепта
            $recipe = Recipe::create([
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'calories' => $request->calories,
                'servings' => $request->servings,
                'difficulty' => $request->difficulty,
                'cooking_time' => $request->cooking_time,
                'image_url' => Storage::url($imagePath),
                'user_id' => auth()->id(),
                'status' => 'pending',
                'proteins' => $request->proteins,
                'fats' => $request->fats,
                'carbs' => $request->carbs,
            ]);

            // Создание шагов без изображений
            foreach ($request->steps as $index => $stepData) {
                $recipe->steps()->create([
                    'step_number' => $index + 1,
                    'description' => $stepData['description'],
                ]);
            }

            // Создание ингредиентов
            foreach ($request->ingredients as $ingredientData) {
                $recipe->ingredients()->create(array_merge($ingredientData, ['is_approved' => false]));
            }

            DB::commit();

            return redirect()->route('recipes.show', $recipe)
                ->with('success', 'Рецепт успешно создан и отправлен на модерацию.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Ошибка при создании рецепта: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при создании рецепта. Пожалуйста, попробуйте снова.');
        }
    }

    public function edit(Recipe $recipe)
    {
        $recipe->load('ingredients');
        $categories = Category::all();
        return view('recipes.edit', compact('recipe', 'categories'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'calories' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|in:easy,medium,hard',
            'cooking_time' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'steps' => 'required|array|min:1',
            'steps.*.id' => 'nullable|exists:recipe_steps,id',
            'steps.*.description' => 'required|string',
            'steps.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'proteins' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'nullable|exists:ingredients,id',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'required|string',
        ]);

        // Обновление основных полей рецепта
        $recipe->update($request->only([
            'title', 'description', 'category_id', 'calories', 'servings',
            'difficulty', 'cooking_time', 'proteins', 'fats', 'carbs'
        ]));

        // Обработка загрузки основного изображения рецепта
        if ($request->hasFile('image')) {
            // Удалить старое изображение, если оно есть
            if ($recipe->image_url) {
                $oldImage = str_replace('/storage/', 'public/', $recipe->image_url);
                Storage::delete($oldImage);
            }
            $imagePath = $request->file('image')->store('recipe-images', 'public');
            $recipe->image_url = Storage::url($imagePath);
            $recipe->save();
        }

        // --- Обработка ингредиентов ---
        $existingIngredientIds = $recipe->ingredients->pluck('id')->toArray();
        $submittedIngredientIds = [];

        foreach ($request->ingredients as $ingredientData) {
            if (isset($ingredientData['id']) && $ingredientData['id']) {
                // Обновление существующего ингредиента
                $ingredient = $recipe->ingredients()->find($ingredientData['id']);
                if ($ingredient) {
                    $ingredient->update($ingredientData);
                    $submittedIngredientIds[] = $ingredient->id;
                }
            } else {
                // Создание нового ингредиента
                $newIngredient = $recipe->ingredients()->create($ingredientData);
                $submittedIngredientIds[] = $newIngredient->id;
            }
        }

        // Удаление ингредиентов, которые были удалены из формы
        $ingredientsToDelete = array_diff($existingIngredientIds, $submittedIngredientIds);
        $recipe->ingredients()->whereIn('id', $ingredientsToDelete)->delete();

        // --- Обработка шагов ---
        $existingStepIds = $recipe->steps->pluck('id')->toArray();
        $submittedStepIds = [];

        foreach ($request->steps as $index => $stepData) {
            if (isset($stepData['id']) && $stepData['id']) {
                // Обновление существующего шага
                $step = $recipe->steps()->find($stepData['id']);
                if ($step) {
                    $step->update(['description' => $stepData['description']]);
                    $submittedStepIds[] = $step->id;
                }
            } else {
                // Создание нового шага
                $newStep = $recipe->steps()->create([
                    'description' => $stepData['description'],
                    'step_number' => $index + 1
                ]);
                $submittedStepIds[] = $newStep->id;
            }
        }

        // Удаление шагов, которые были удалены из формы
        $stepsToDelete = array_diff($existingStepIds, $submittedStepIds);
        $recipe->steps()->whereIn('id', $stepsToDelete)->delete();

        // Перенаправляем в зависимости от роли пользователя
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.ingredients.index')->with('success', 'Рецепт успешно обновлен.');
        } else {
            return redirect()->route('profile.recipes')->with('success', 'Рецепт успешно обновлен.');
        }
    }

    public function destroy(Recipe $recipe)
    {
        if ($recipe->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'У вас нет прав на удаление этого рецепта.');
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
            if (Auth::user()->is_admin) {
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

    public function addToFavorites($id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = Auth::user();

        if (!$user->favorites()->where('recipe_id', $recipe->id)->exists()) {
            Favorite::create([
                'user_id' => $user->id,
                'recipe_id' => $recipe->id,
            ]);
        }

        return redirect()->back()->with('success', 'Рецепт добавлен в избранное.');
    }

    public function removeFromFavorites($id)
    {
        $favorite = Favorite::where('user_id', Auth::id())->where('recipe_id', $id)->first();

        if ($favorite) {
            $favorite->delete();
        }

        return redirect()->back()->with('success', 'Рецепт удалён из избранного.');
    }

    public function userRecipes()
    {
        $user = auth()->user();
        $recipes = $user->recipes; // Возвращаем получение только рецептов
        $categories = \App\Models\Category::all(); // Добавляем получение категорий
        return view('profile.ingredients.index', compact('recipes', 'categories')); // Передаем категории в шаблон
    }

    public function reject(Request $request, Recipe $recipe)
    {
        // Добавим логирование для отладки
        \Log::info('Отклонение рецепта', [
            'recipe_id' => $recipe->id,
            'reason' => $request->reason
        ]);

        try {
            // Валидация
            $request->validate([
                'reason' => 'required|string|min:10',
            ]);

            // Обновляем статус и причину отклонения
            $recipe->update([
                'status' => 'rejected',
                'is_approved' => false,
                'rejection_reason' => $request->reason
            ]);

            // Отправляем уведомление автору
            $recipe->user->notify(new RecipeRejected($recipe, $request->reason));

            return redirect()
                ->route('admin.recipes.index')
                ->with('success', 'Рецепт отклонен, автор уведомлен.');
        } catch (\Exception $e) {
            \Log::error('Ошибка при отклонении рецепта: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Произошла ошибка при отклонении рецепта. Пожалуйста, попробуйте снова.');
        }
    }

    public function adminIndex()
    {
        // Получаем рецепты, ожидающие модерации
        $recipes = Recipe::with(['user', 'ingredients'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $recipes->each(function ($recipe) {
            $recipe->isEdited = $recipe->created_at->notEqualTo($recipe->updated_at);
        });

        return view('admin.manage-recipes', compact('recipes'));
    }

    // Добавим метод для одобрения рецепта
    public function approve(Recipe $recipe)
    {
        $recipe->update([
            'status' => 'approved',
            'is_approved' => true
        ]);

        return redirect()
            ->route('admin.recipes.index')
            ->with('success', 'Рецепт успешно одобрен');
    }

    public function catalog(Request $request)
    {
        $query = Recipe::where('status', 'approved');

        // Фильтрация по названию
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Фильтрация по сложности
        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Фильтрация по времени приготовления
        if ($request->has('cooking_time')) {
            $query->where('cooking_time', '<=', $request->cooking_time);
        }

        // Фильтрация по калориям
        if ($request->has('calories')) {
            $query->where('calories', '<=', $request->calories);
        }

        // Фильтрация по белкам
        if ($request->has('min_proteins')) {
            $query->where('proteins', '>=', $request->min_proteins);
        }
        if ($request->has('max_proteins')) {
            $query->where('proteins', '<=', $request->max_proteins);
        }

        // Фильтрация по жирам
        if ($request->has('min_fats')) {
            $query->where('fats', '>=', $request->min_fats);
        }
        if ($request->has('max_fats')) {
            $query->where('fats', '<=', $request->max_fats);
        }

        // Фильтрация по углеводам
        if ($request->has('min_carbs')) {
            $query->where('carbs', '>=', $request->min_carbs);
        }
        if ($request->has('max_carbs')) {
            $query->where('carbs', '<=', $request->max_carbs);
        }

        // Сортировка
        $sort = $request->get('sort', 'title_asc'); // По умолчанию сортировка по названию А-Я
        switch ($sort) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'rating_asc':
                $query->withAvg('comments', 'rating')
                    ->orderBy('comments_avg_rating', 'asc');
                break;
            case 'rating_desc':
                $query->withAvg('comments', 'rating')
                    ->orderBy('comments_avg_rating', 'desc');
                break;
            default:
                $query->orderBy('title', 'asc');
        }

        $recipes = $query->paginate(12);

        return view('catalog', compact('recipes'));
    }
}
