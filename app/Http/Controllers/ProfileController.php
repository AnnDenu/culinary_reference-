<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\RecipeView;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function history()
    {
        $history = RecipeView::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc') // Сортировка по времени последнего просмотра
            ->with('recipe')
            ->take(10) // Ограничение истории
            ->get();

        return view('profile.history', compact('history'));
    }

    public function trackView($recipeId)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            // Проверяем, есть ли уже запись в истории
            $view = RecipeView::where('user_id', $userId)
                ->where('recipe_id', $recipeId)
                ->first();

            if ($view) {
                // Если запись есть, обновляем время просмотра
                $view->touch();
            } else {
                // Если записи нет, создаем новую
                RecipeView::create([
                    'user_id' => $userId,
                    'recipe_id' => $recipeId
                ]);
            }
        }

        return redirect()->route('recipes.show', $recipeId);
    }

    public function clearHistory()
    {
        if (auth()->check()) {
            RecipeView::where('user_id', auth()->id())->delete();
            return redirect()->route('profile.history')->with('success', 'История просмотров успешно очищена.');
        }

        return redirect()->route('login')->with('error', 'Для очистки истории необходимо авторизоваться.');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'avatar' => ['nullable', 'image', 'max:1024'], // максимум 1MB
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он существует
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Сохраняем новый аватар
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = Storage::url($path);
        }

        $user->fill($request->only('username', 'email'));
        $user->save();

        return back()->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
