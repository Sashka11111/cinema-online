<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\Episode;

class EpisodeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEpisodes = Episode::count();
        $fillerEpisodes = Episode::where('is_filler', true)->count();
        $averageDuration = Episode::whereNotNull('duration')->avg('duration');

        return [
            Stat::make('Всього епізодів', $totalEpisodes)
                ->description('Загальна кількість')
                ->color('primary')
                ->icon('heroicon-o-puzzle-piece'),

            Stat::make('Філерні епізоди', $fillerEpisodes)
                ->description('Кількість філерів')
                ->color('warning')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Середня тривалість', number_format($averageDuration ?? 0, 1).' хв')
                ->description('Середня тривалість епізодів')
                ->color('success')
                ->icon('heroicon-o-clock'),
        ];
    }
}
