<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Enums\PersonType;
use Liamtseva\Cinema\Filament\Admin\Resources\PersonResource;

class ListPeople extends ListRecords
{
    protected static string $resource = PersonResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('Усі персони')
                ->icon('heroicon-o-user-group')
                ->query(fn ($query) => $query),

            Tab::make('Актори')
                ->icon('heroicon-o-film')
                ->query(fn ($query) => $query->where('type', PersonType::ACTOR)),

            Tab::make('Режисери')
                ->icon('heroicon-o-video-camera')
                ->query(fn ($query) => $query->where('type', PersonType::DIRECTOR)),

            Tab::make('Сценаристи')
                ->icon('heroicon-o-pencil-square')
                ->query(fn ($query) => $query->where('type', PersonType::WRITER)),

            Tab::make('Нещодавно додані')
                ->icon('heroicon-o-clock')
                ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(30))),
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
            PersonResource\Widgets\PersonCreationChart::class,
            PersonResource\Widgets\PersonTypeChart::class,
            PersonResource\Widgets\PersonStatsOverview::class,
        ];
    }
}
