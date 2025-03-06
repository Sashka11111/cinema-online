<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\Studio;

class StudioStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Усі студії', Studio::count())
                ->description('Загальна кількість студій')
                ->icon('heroicon-o-building-office')
                ->color('primary'),

            Stat::make('З зображеннями', Studio::whereNotNull('image')->count())
                ->description('Студії з головним зображенням')
                ->icon('heroicon-o-photo')
                ->color('success'),

            Stat::make('Недавно створені', Studio::where('created_at', '>=', now()->subDays(7))->count())
                ->description('За останні 7 днів')
                ->icon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
