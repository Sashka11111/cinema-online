<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Widgets;

use Filament\Widgets\ChartWidget;
use Liamtseva\Cinema\Models\Movie;

class MovieRatingDistribution extends ChartWidget
{
    protected static ?string $heading = 'Кількість фільмів за рейтингом IMDb';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data = Movie::selectRaw('FLOOR(imdb_score) as rating, COUNT(*) as count')
            ->whereNotNull('imdb_score')
            ->groupBy('rating')
            ->orderBy('rating', 'asc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Кількість фільмів',
                    'data' => $data->pluck('count')->all(),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#C9CBCF', '#7BC225', '#F778A1', '#E7E9ED',
                    ],
                ],
            ],
            'labels' => $data->pluck('rating')->map(fn ($rating) => "$rating - ".($rating + 1))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
