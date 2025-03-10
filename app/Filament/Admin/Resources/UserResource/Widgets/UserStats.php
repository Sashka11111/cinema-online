<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Liamtseva\Cinema\Models\User;

class UserStats extends ChartWidget
{
    protected static ?string $heading = 'Статистика нових користувачів';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('F');
            $data[] = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Нові користувачі',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Кількість',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Місяць',
                    ],
                ],
            ],
        ];
    }
}
