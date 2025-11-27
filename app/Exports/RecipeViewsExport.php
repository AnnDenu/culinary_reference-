<?php

namespace App\Exports;

use App\Models\RecipeView; // Предполагаем наличие модели RecipeView
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon; // Импортируем Carbon для работы с датами

class RecipeViewsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate) : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
    }

    public function collection()
    {
        $query = RecipeView::with(['recipe', 'user']); // Предполагаем связи с рецептом и пользователем

        if ($this->startDate) {
            $query->where('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('created_at', '<=', $this->endDate);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Рецепт',
            'Пользователь',
            'Дата и время просмотра',
        ];
    }

    public function map($recipeView): array
    {
        return [
            $recipeView->recipe->title ?? 'N/A',
            $recipeView->user->username ?? $recipeView->user->email ?? 'Гость', // Используем username или email пользователя, или указываем Гость
            $recipeView->created_at->format('d.m.Y H:i:s'),
        ];
    }
} 