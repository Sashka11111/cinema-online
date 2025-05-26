<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Models\Room;

class RoomStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $activeCount = Room::whereNotNull('started_at')->whereNull('ended_at')->count();
        $completedCount = Room::whereNotNull('started_at')->whereNotNull('ended_at')->count();
        $notStartedCount = Room::whereNull('started_at')->count();
        $privateCount = Room::where('is_private', true)->count();
        $publicCount = Room::where('is_private', false)->count();
        $totalCount = Room::count();

        return [
            Stat::make('Загальна кількість кімнат', $totalCount)
                ->description('Усі кімнати в системі')
                ->icon('heroicon-o-video-camera')
                ->color('primary'),

            Stat::make(RoomStatus::ACTIVE->getLabel(), $activeCount)
                ->description('Кімнати, які зараз активні')
                ->icon(RoomStatus::ACTIVE->getIcon())
                ->color(RoomStatus::ACTIVE->getColor()),

            Stat::make(RoomStatus::COMPLETED->getLabel(), $completedCount)
                ->description('Кімнати, які вже завершені')
                ->icon(RoomStatus::COMPLETED->getIcon())
                ->color(RoomStatus::COMPLETED->getColor()),

            Stat::make(RoomStatus::NOT_STARTED->getLabel(), $notStartedCount)
                ->description('Кімнати, які ще не розпочаті')
                ->icon(RoomStatus::NOT_STARTED->getIcon())
                ->color(RoomStatus::NOT_STARTED->getColor()),

            Stat::make('Приватні кімнати', $privateCount)
                ->description('Кімнати з паролем')
                ->icon('heroicon-o-lock-closed')
                ->color('danger'),

            Stat::make('Публічні кімнати', $publicCount)
                ->description('Кімнати без пароля')
                ->icon('heroicon-o-globe-alt')
                ->color('info'),
        ];
    }
}
