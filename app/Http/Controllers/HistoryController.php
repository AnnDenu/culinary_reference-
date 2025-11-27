<?php
namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function clear(Request $request)
    {
        // Удаление всех записей истории для текущего пользователя
        $user = auth()->user();
        History::where('user_id', $user->id)->delete();

        // Перенаправление с сообщением
        return redirect()->route('profile.history')->with('status', 'История просмотров успешно очищена!');
    }
}

