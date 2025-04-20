<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\UserList;

class UserListStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Статистика за статусами
            Stat::make('Дивляться', UserList::where('type', UserListType::WATCHING->value)->count())
                ->description('Активні перегляди')
                ->icon('heroicon-m-play')
                ->color('success'),

            Stat::make('Заплановано', UserList::where('type', UserListType::PLANNED->value)->count())
                ->description('Заплановані перегляди')
                ->icon('heroicon-m-clock')
                ->color('info'),

            Stat::make('Завершено', UserList::where('type', UserListType::WATCHED->value)->count())
                ->description('Завершені перегляди')
                ->icon('heroicon-m-check-circle')
                ->color('warning'),

            Stat::make('Кинуто', UserList::where('type', UserListType::STOPPED->value)->count())
                ->description('Кинуті перегляди')
                ->icon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
