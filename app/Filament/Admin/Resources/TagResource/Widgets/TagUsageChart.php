<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Widgets;

use Filament\Widgets\ChartWidget;
use Liamtseva\Cinema\Models\Tag;

class TagUsageChart extends ChartWidget
{
    protected static ?string $heading = 'Популярність тегів';

    protected function getData(): array
    {
        $tags = Tag::select('name')
            ->withCount('movies') // Заміни на відношення до фільмів або іншого контенту
            ->orderByDesc('movies_count')
            ->limit(10)
            ->get();

        return [
            'labels' => $tags->pluck('name'),
            'datasets' => [
                [
                    'label' => 'Кількість використань',
                    'data' => $tags->pluck('movies_count'),
                    'backgroundColor' => '#4F46E5',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
