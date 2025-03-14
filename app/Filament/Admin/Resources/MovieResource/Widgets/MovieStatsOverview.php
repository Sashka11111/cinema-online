<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Models\Movie;

class MovieStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Всього медіа', Movie::count())
                ->description('Загальна кількість медіа у базі')
                ->icon('heroicon-o-film')
                ->color('primary'),
            Stat::make('Опубліковані', Movie::where('is_published', true)->count())
                ->description('Кількість опублікованих медіа')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Фільми', Movie::where('kind', Kind::MOVIE)->count())
                ->description('Кількість повнометражних фільмів')
                ->icon('heroicon-o-video-camera')
                ->color('info'),
            Stat::make('Серіали', Movie::where('kind', Kind::TV_SERIES)->count())
                ->description('Кількість серіалів')
                ->icon('heroicon-o-tv')
                ->color('warning'),
        ];
    }
}
