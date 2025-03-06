<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Liamtseva\Cinema\Models\Studio;

class StudioActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Активність студій';

    protected function getData(): array
    {
        $data = Trend::model(Studio::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Додані студії',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
