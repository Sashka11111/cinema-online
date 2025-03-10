<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Liamtseva\Cinema\Models\Tag;

class TagCreationChart extends ChartWidget
{
    protected static ?string $heading = 'Кількість тегів за часом';

    protected static string $color = 'primary';

    protected function getData(): array
    {
        $data = Trend::model(Tag::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Нові теги',
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
