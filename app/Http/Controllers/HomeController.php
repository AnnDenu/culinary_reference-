<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Activity;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Recipe::query()->where('status', 'approved');

        if ($request->filled('filterCategory') && $request->filterCategory !== 'Все') {
            $query->where('category_id', $request->filterCategory);
        }
        if ($request->filled('filterTime')) {
            if ($request->filterTime === 'До 30 минут') {
                $query->where('cooking_time', '<=', 30);
            } elseif ($request->filterTime === '30-60 минут') {
                $query->where('cooking_time', '>', 30)->where('cooking_time', '<=', 60);
            } elseif ($request->filterTime === 'Более 60 минут') {
                $query->where('cooking_time', '>', 60);
            }
        }

        if ($request->filled('filterRating')) {
            $query->withAvg('comments', 'rating') // средний рейтинг по комментариям
            ->having('comments_avg_rating', '>=', $request->filterRating); // Фильтр по среднему рейтингу
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Пагинация
        $recipes = $query->paginate(10);

        // Получаем популярные рецепты
        $popularRecipes = Recipe::where('status', 'approved')
            ->withCount('comments')
            ->withAvg('comments', 'rating') // ср.рейтинг по коммам
            ->having('comments_avg_rating', '>=', 4.5) // Фильтр по ср.рейтингу >= 4.5
            ->orderByDesc('comments_count') // Сортир. по кол-у коммов
            ->take(5)
            ->get();

        // Получаем последние действия с загрузкой пользователей
        $latestActions = Activity::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('home.index', compact('recipes', 'categories', 'popularRecipes', 'latestActions'));
    }

}
