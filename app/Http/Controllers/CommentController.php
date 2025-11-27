<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('recipe', 'user')->paginate(10); // Подгружаем данные о рецепте и пользователе
        return view('admin.comments.index', compact('comments'));
    }
    public function store(Request $request, $recipeId)
    {
        // Валидация данных
        $request->validate([
            'comment' => 'required|string|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Создание комментария
        Comment::create([
            'user_id' => auth()->id(),  // Получение ID авторизованного пользователя
            'recipe_id' => $recipeId,   // ID рецепта, к которому добавляется комментарий
            'comment' => $request->input('comment'),  // Содержимое комментария
            'rating' => $request->input('rating', 0),
        ]);

        // Перенаправление обратно к рецепту с сообщением об успехе
        return redirect()->route('recipes.show', $recipeId)->with('success', 'Комментарий успешно добавлен!');
    }


    public function edit($id)
    {
        $comment = Comment::findOrFail($id);

        // Проверяем, является ли пользователь администратором или владельцем комментария
        if (auth()->user()->role === 'admin' || auth()->id() === $comment->user_id) {
            return view('comments.edit', compact('comment'));
        }

        return redirect()->back()->with('error', 'У вас нет прав для редактирования этого комментария.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment = Comment::findOrFail($id);

        // Проверяем, является ли пользователь администратором или владельцем комментария
        if (auth()->user()->role === 'admin' || auth()->id() === $comment->user_id) {
            $comment->update(['comment' => $request->comment, 'rating' => $request->rating]);

            return redirect()->route('recipes.show', $comment->recipe_id)->with('success', 'Комментарий обновлен.');
        }

        return redirect()->back()->with('error', 'У вас нет прав для обновления этого комментария.');
    }


    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Проверяем, является ли пользователь администратором или владельцем комментария
        if (auth()->user()->role === 'admin' || auth()->id() === $comment->user_id) {
            $comment->delete();
            return redirect()->back()->with('success', 'Комментарий удален.');
        }

        return redirect()->back()->with('error', 'У вас нет прав для удаления этого комментария.');
    }
}
