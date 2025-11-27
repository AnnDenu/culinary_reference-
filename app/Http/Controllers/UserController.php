<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Показать всех пользователей
    public function index()
    {
        // Проверка роли администратора
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Доступ запрещен. У вас нет прав администратора.');
        }

        // Пагинация: 10 пользователей на страницу
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Обновление роли пользователя
    public function updateRole(Request $request, User $user)
    {
        // Проверка роли администратора
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Доступ запрещен. У вас нет прав администратора.');
        }

        // Проверка, что администратор не меняет свою роль
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Вы не можете изменить свою роль.');
        }

        $request->validate([
            'role' => 'required|string',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Роль пользователя успешно обновлена.');
    }

    // Забанить/Разбанить пользователя
    public function ban(User $user)
    {
        // Проверка роли администратора
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Доступ запрещен. У вас нет прав администратора.');
        }

        // Проверка, что администратор не банит самого себя
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Вы не можете забанить самого себя.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'забанен' : 'разбанен';
        return redirect()->route('admin.users.index')->with('success', "Пользователь {$user->username} успешно {$status}.");
    }
}
