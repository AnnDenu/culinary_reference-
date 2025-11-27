<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Ingredient;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserActionsExport;
use App\Models\Activity;
use App\Exports\CommentsExport;
use App\Notifications\RecipeRejected;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\RecipeApproved;
use App\Exports\RecipesExport;
use App\Exports\RecipeViewsExport;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function manageRecipes()
    {
        $recipes = Recipe::where('status', 'pending')
            ->with(['user', 'ingredients'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $recipes->each(function ($recipe) {
            $recipe->isEdited = $recipe->created_at->notEqualTo($recipe->updated_at);
        });

        return view('admin.manage-recipes', compact('recipes'));
    }
    public function approveRecipe(Recipe $recipe)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.recipes')->with('error', 'У вас нет прав на одобрение рецептов.');
        }

        $recipe->update([
            'status' => 'approved',
            'is_approved' => true
        ]);

        return redirect()->route('admin.recipes.index')->with('success', 'Рецепт успешно одобрен и теперь доступен на главной странице.');
    }
    public function rejectRecipe(Request $request, Recipe $recipe)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);

        try {
            // Обновляем рецепт
            $recipe->update([
                'status' => 'rejected',
                'is_approved' => false,
                'rejection_reason' => $request->rejection_reason
            ]);

            // Создаем уведомление
            Notification::create([
                'user_id' => $recipe->user_id,
                'title' => 'Рецепт отклонен',
                'message' => "Ваш рецепт '{$recipe->title}' был отклонен.",
                'recipe_id' => $recipe->id,
                'is_read' => false,
                'rejection_reason' => $request->rejection_reason
            ]);

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
    public function indexIngr()
    {
        $ingredients = Ingredient::where('is_approved', false)->with('recipe')->get();
        return view('admin.ingredients.index', compact('ingredients'));
    }

    public function update($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Ингредиент успешно одобрен.');
    }

    public function exportUserActions(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен. У вас нет прав администратора.');
        }
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(
            new UserActionsExport($startDate, $endDate), 
            'действия_пользователей_' . now()->format('d-m-Y') . '.xlsx'
        );
    }

    public function exportComments(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        return Excel::download(
            new CommentsExport($startDate, $endDate), 
            'комментарии_' . now()->format('d-m-Y') . '.xlsx'
        );
    }

    public function exportRecipes(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен. У вас нет прав администратора.');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(
            new RecipesExport($startDate, $endDate),
            'рецепты_' . now()->format('d-m-Y') . '.xlsx'
        );
    }

    public function exportRecipeViews(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен. У вас нет прав администратора.');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(
            new RecipeViewsExport($startDate, $endDate),
            'просмотры_рецептов_' . now()->format('d-m-Y') . '.xlsx'
        );
    }
}
