<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\WatchHistory;

class WatchHistoryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalWatches = WatchHistory::count();
        $uniqueUsers = WatchHistory::distinct('user_id')->count();
        $uniqueEpisodes = WatchHistory::distinct('episode_id')->count();
        $averageWatchTime = WatchHistory::avg('progress_time');

        return [
            Stat::make('Всього переглядів', $totalWatches)
                ->description('Загальна кількість записів перегляду')
                ->icon('heroicon-o-play')
                ->color('primary'),

            Stat::make('Унікальних користувачів', $uniqueUsers)
                ->description('Кількість користувачів, що дивились контент')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Унікальних епізодів', $uniqueEpisodes)
                ->description('Кількість різних епізодів, що переглядались')
                ->icon('heroicon-o-film')
                ->color('warning'),

            Stat::make('Середній час перегляду', gmdate('H:i:s', (int)$averageWatchTime))
                ->description('Середня тривалість перегляду')
                ->icon('heroicon-o-clock')
                ->color('info'),
        ];
    }
}