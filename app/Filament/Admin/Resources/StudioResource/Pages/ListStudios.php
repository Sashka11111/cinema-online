<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Widgets\RecentStudios;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Widgets\StudioActivityChart;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Widgets\StudioStats;

class ListStudios extends ListRecords
{
    protected static string $resource = StudioResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('Усі студії')
                ->icon('heroicon-o-list-bullet')
                ->query(fn ($query) => $query),

            Tab::make('recent')
                ->label('Недавно створені')
                ->icon('heroicon-o-clock')
                ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(7))),

            Tab::make('with_movies')
                ->label('З фільмами')
                ->icon('heroicon-o-film')
                ->query(fn ($query) => $query->has('movies'))
                ->badge(fn () => $this->getModel()::has('movies')->count()),

            Tab::make('without_movies')
                ->label('Без фільмів')
                ->icon('heroicon-o-x-circle')
                ->query(fn ($query) => $query->doesntHave('movies'))
                ->badge(fn () => $this->getModel()::doesntHave('movies')->count()),
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
            StudioActivityChart::class,
            RecentStudios::class,
            StudioStats::class,
        ];
    }
}
