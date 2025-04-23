<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\Widgets\SelectionsStats;

class ListSelections extends ListRecords
{
    protected static string $resource = SelectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('all')
                ->label('Усі підбірки')
                ->icon('heroicon-o-list-bullet')
                ->badge(fn () => $this->getModel()::count()),

            'only_movies' => Tab::make('only_movies')
                ->label('Тільки з фільмами')
                ->icon('heroicon-o-film')
                ->query(fn ($query) => $query
                    ->whereHas('movies')
                    ->whereDoesntHave('persons')
                    ->whereDoesntHave('episodes'))
                ->badge(fn () => $this->getModel()::whereHas('movies')
                    ->whereDoesntHave('persons')
                    ->whereDoesntHave('episodes')
                    ->count()),

            'only_persons' => Tab::make('only_persons')
                ->label('Тільки з персонами')
                ->icon('heroicon-o-users')
                ->query(fn ($query) => $query
                    ->whereHas('persons')
                    ->whereDoesntHave('movies')
                    ->whereDoesntHave('episodes'))
                ->badge(fn () => $this->getModel()::whereHas('persons')
                    ->whereDoesntHave('movies')
                    ->whereDoesntHave('episodes')
                    ->count()),

            'only_episodes' => Tab::make('only_episodes')
                ->label('Тільки з епізодами')
                ->icon('heroicon-o-tv')
                ->query(fn ($query) => $query
                    ->whereHas('episodes')
                    ->whereDoesntHave('movies')
                    ->whereDoesntHave('persons'))
                ->badge(fn () => $this->getModel()::whereHas('episodes')
                    ->whereDoesntHave('movies')
                    ->whereDoesntHave('persons')
                    ->count()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SelectionsStats::class,
        ];
    }
}
