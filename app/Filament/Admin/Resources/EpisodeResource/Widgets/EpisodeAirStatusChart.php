<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Widgets;

use Filament\Widgets\ChartWidget;
use Liamtseva\Cinema\Models\Episode;

class EpisodeAirStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Статус виходу епізодів';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        // Категорії статусу виходу
        $aired = Episode::whereNotNull('air_date')->where('air_date', '<=', now())->count(); // Вийшли
        $upcoming = Episode::whereNotNull('air_date')->where('air_date', '>', now())->count(); // Майбутні
        $noDate = Episode::whereNull('air_date')->count(); // Без дати

        return [
            'datasets' => [
                [
                    'label' => 'Статус епізодів',
                    'data' => [$aired, $upcoming, $noDate],
                    'backgroundColor' => ['#48bb78', '#4299e1', '#a0aec0'],
                ],
            ],
            'labels' => ['Вийшли', 'Майбутні', 'Без дати'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
