<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\Recipe;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Получаем все категории
        $categories = Category::all();

        // Инициализируем запрос для рецептов
        $query = Recipe::with(['ingredients', 'user'])
            ->withAvg('comments', 'rating') // Загружаем средний рейтинг
            ->where('is_approved', true);

        // Поиск по названию или ингредиентам
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('ingredients', function($q) use ($searchTerm) {
                      $q->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Фильтр по категории
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->input('category'));
        }

        // Фильтр по калориям
        if ($request->has('calories') && $request->calories != '') {
            $query->where('calories', '<=', $request->input('calories'));
        }

        // Фильтр по белкам
        if ($request->has('min_proteins') && $request->min_proteins != '') {
            $query->where('proteins', '>=', $request->input('min_proteins'));
        }
        if ($request->has('max_proteins') && $request->max_proteins != '') {
            $query->where('proteins', '<=', $request->input('max_proteins'));
        }

        // Фильтр по жирам
        if ($request->has('min_fats') && $request->min_fats != '') {
            $query->where('fats', '>=', $request->input('min_fats'));
        }
        if ($request->has('max_fats') && $request->max_fats != '') {
            $query->where('fats', '<=', $request->input('max_fats'));
        }

        // Фильтр по углеводам
        if ($request->has('min_carbs') && $request->min_carbs != '') {
            $query->where('carbs', '>=', $request->input('min_carbs'));
        }
        if ($request->has('max_carbs') && $request->max_carbs != '') {
            $query->where('carbs', '<=', $request->input('max_carbs'));
        }

        // Применяем сортировку
        switch ($request->sort) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'rating_asc':
                $query->orderBy('comments_avg_rating', 'asc');
                break;
            case 'rating_desc':
                $query->orderBy('comments_avg_rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Получаем отфильтрованные рецепты с пагинацией
        $recipes = $query->paginate(12)->withQueryString();

        // Получаем все не одобренные ингредиенты
        $ingredients = Ingredient::where('is_approved', false)->get();

        return view('catalog', compact('ingredients', 'recipes', 'categories'));
    }
}
