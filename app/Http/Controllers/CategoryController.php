<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //Для администратора
    public function index()
    {
        $categories = Category::all(); // Получаем все категории
        return view('admin.categories.index', [
            'categories' => $categories
        ]); // Передаем категории в представление
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно создана.');
    }

    //Изменение данных
    public function update(Request $request, Category $category)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'required|string|max:255',
        ]);

        // Обновление категории
        $category->update($validatedData);

        // Перенаправление с сообщением
        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно обновлена');
    }

    public function destroy(Category $category)
    {
        // Проверяем, есть ли рецепты, привязанные к этой категории
        if ($category->recipes()->exists()) {
            return redirect()->route('admin.categories.index')
                             ->with('error', 'Невозможно удалить категорию, так как к ней привязаны рецепты.');
        }

        $category->delete(); // Удаление категории
        return redirect()->route('admin.categories.index')->with('success', 'Категория удалена!');
    }
}
