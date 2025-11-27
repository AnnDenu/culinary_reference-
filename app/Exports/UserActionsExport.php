<?php

namespace App\Exports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class UserActionsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Activity::with('user');

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
            'Действие',
            'Дата и время',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->user->username ?? $activity->user->email,
            $activity->description,
            $activity->created_at->format('d.m.Y H:i:s'),
        ];
    }
} 