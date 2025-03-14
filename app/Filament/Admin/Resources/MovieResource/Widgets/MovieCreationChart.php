<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Liamtseva\Cinema\Models\Movie;

class MovieCreationChart extends ChartWidget
{
    protected static ?string $heading = 'Графік створення медіа';

    protected static string $color = 'primary';

    protected function getData(): array
    {
        $data = Trend::model(Movie::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Нові медіа',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
