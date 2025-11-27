<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Recipe;

class RecipeRejected extends Notification
{
    use Queueable;

    protected $recipe;
    protected $rejection_reason;

    public function __construct(Recipe $recipe, $rejection_reason)
    {
        $this->recipe = $recipe;
        $this->rejection_reason = $rejection_reason;
        
        // Отладка конструктора
        \Log::info('RecipeRejected notification created:', [
            'recipe_id' => $recipe->id,
            'rejection_reason' => $rejection_reason
        ]);
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'title' => 'Рецепт отклонен',
            'message' => "Ваш рецепт '{$this->recipe->title}' был отклонен.",
            'recipe_id' => $this->recipe->id,
            'rejection_reason' => $this->rejection_reason
        ];

        // Отладка данных уведомления
        \Log::info('Notification data for database:', $data);

        return $data;
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
