<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource;

class CommentReportTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Кількість скарг за місяцями';

    protected static string $color = 'primary';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $modelClass = CommentReportResource::getModel();

        $data = Trend::model($modelClass)
            ->between(
                start: now()->subYear(),
                end: now()
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Нові скарги',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#3490dc',
                    'borderColor' => '#2b6cb0',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('M Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
