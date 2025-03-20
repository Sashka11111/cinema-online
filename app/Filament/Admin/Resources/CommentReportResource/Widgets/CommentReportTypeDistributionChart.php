<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Widgets;

use Filament\Widgets\ChartWidget;
use Liamtseva\Cinema\Enums\CommentReportType;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource;
use Liamtseva\Cinema\Models\CommentReport;

class CommentReportTypeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Розподіл скарг за типами';

    protected static string $color = 'primary';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $modelClass = CommentReportResource::getModel();

        $types = CommentReportType::cases();
        $data = [];
        $labels = [];
        $colors = ['#3490dc', '#38c172', '#f6993f', '#e3342f', '#9561e2']; // Кольори для різних типів

        foreach ($types as $index => $type) {
            $count = CommentReport::where('type', $type->value)->count();
            $data[] = $count;
            $labels[] = $type->getLabel();
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($types)),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
