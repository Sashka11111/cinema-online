<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Widgets\RoomStatsOverview;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('Усі кімнати')
                ->icon('heroicon-o-video-camera')
                ->query(fn ($query) => $query),

            Tab::make('active')
                ->label('Активні')
                ->icon('heroicon-o-check-circle')
                ->query(fn ($query) => $query->where('room_status', 'active')),

            Tab::make('completed')
                ->label('Завершені')
                ->icon('heroicon-o-x-circle')
                ->query(fn ($query) => $query->where('room_status', 'completed')),

            Tab::make('not_started')
                ->label('Не розпочаті')
                ->icon('heroicon-o-clock')
                ->query(fn ($query) => $query->where('room_status', 'not_started')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoomStatsOverview::class,
        ];
    }
}
