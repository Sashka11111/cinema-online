<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\SearchHistory;

class SearchHistoryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSearches = SearchHistory::count();
        $uniqueUsers = SearchHistory::distinct('user_id')->count();
        $todaySearches = SearchHistory::whereDate('created_at', today())->count();
        $averageLength = SearchHistory::avg(\DB::raw('LENGTH(query)'));

        return [
            Stat::make('Всього пошуків', $totalSearches)
                ->description('Загальна кількість пошукових запитів')
                ->icon('heroicon-o-magnifying-glass')
                ->color('primary'),

            Stat::make('Унікальних користувачів', $uniqueUsers)
                ->description('Кількість користувачів, що здійснювали пошук')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Пошуків сьогодні', $todaySearches)
                ->description('Кількість пошуків за сьогодні')
                ->icon('heroicon-o-calendar')
                ->color('warning'),

            Stat::make('Середня довжина запиту', number_format($averageLength, 1))
                ->description('Середня кількість символів у запиті')
                ->icon('heroicon-o-document-text')
                ->color('info'),
        ];
    }
}