<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Widgets;

use Filament\Widgets\ChartWidget;
use Liamtseva\Cinema\Enums\PersonType;
use Liamtseva\Cinema\Filament\Admin\Resources\PersonResource;

class PersonTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Розподіл персон за типом';

    protected static ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $modelClass = PersonResource::getModel();
        $model = app($modelClass);

        $counts = [
            $model::where('type', PersonType::ACTOR)->count(),
            $model::where('type', PersonType::DIRECTOR)->count(),
            $model::where('type', PersonType::WRITER)->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Типи персон',
                    'data' => $counts,
                    'backgroundColor' => ['#48bb78', '#4299e1', '#ecc94b'],
                ],
            ],
            'labels' => ['Актори', 'Режисери', 'Сценаристи'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
