<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Enums\PersonType;
use Liamtseva\Cinema\Models\Person;

class PersonStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPeople = Person::count();
        $actors = Person::where('type', PersonType::ACTOR)->count();
        $directors = Person::where('type', PersonType::DIRECTOR)->count();

        return [
            Stat::make('Всього персон', $totalPeople)
                ->description('Загальна кількість')
                ->color('info')
                ->icon('heroicon-o-user-group'),

            Stat::make('Актори', $actors)
                ->description('Кількість акторів')
                ->color('success')
                ->icon('heroicon-o-film'),

            Stat::make('Режисери', $directors)
                ->description('Кількість режисерів')
                ->color('warning')
                ->icon('heroicon-o-video-camera'),
        ];
    }
}
