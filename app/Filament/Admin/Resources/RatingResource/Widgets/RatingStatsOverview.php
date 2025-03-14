<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RatingResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\Rating;

class RatingStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $averageRating = Rating::avg('number');
        $totalRatings = Rating::count();
        $highRatings = Rating::where('number', '>=', 8)->count();

        return [
            Stat::make('Загальна кількість оцінок', $totalRatings)
                ->description('Всього оцінок у системі')
                ->icon('heroicon-m-star')
                ->color('primary'),

            Stat::make('Середня оцінка', number_format($averageRating, 1))
                ->description('Середній бал по всім оцінкам')
                ->icon('heroicon-m-calculator')
                ->color('warning'),

            Stat::make('Високі оцінки', $highRatings)
                ->description('Оцінки 8 і вище')
                ->icon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
