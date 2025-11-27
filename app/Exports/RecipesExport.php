<?php

namespace App\Exports;

use App\Models\Recipe;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon; // Импортируем Carbon для работы с датами

class RecipesExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Recipe::with('user'); // Изменено с 'author' на 'user'

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
            'Название',
            'Описание',
            'Автор',
            'Дата создания',
            'Категория',
            'Калории',
            'Порции',
            'Сложность',
            'Статус',
        ];
    }

    public function map($recipe): array
    {
        return [
            $recipe->title,
            $recipe->description,
            $recipe->user->username ?? $recipe->user->email, // Изменено с author на user
            $recipe->created_at->format('d.m.Y H:i:s'),
            $recipe->category->name ?? 'N/A', // Предполагаем связь с категорией
            $recipe->calories,
            $recipe->servings,
            $recipe->difficulty,
            $recipe->status,
        ];
    }
} 