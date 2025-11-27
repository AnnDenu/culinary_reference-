<?php

namespace App\Exports;

use App\Models\Comment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class CommentsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Comment::with(['user', 'recipe']);

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
            'Пользователь',
            'Комментарий',
            'Рецепт',
            'Дата',
        ];
    }

    public function map($comment): array
    {
        return [
            $comment->user->username,
            $comment->comment,
            $comment->recipe->title,
            $comment->created_at->format('d.m.Y H:i:s'),
        ];
    }
} 