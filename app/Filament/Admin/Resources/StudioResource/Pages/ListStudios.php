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
                ->query(fn($query) => $query),

            Tab::make('recent')
                ->label('Недавно створені')
                ->icon('heroicon-o-clock')
                ->query(fn($query) => $query->where('created_at', '>=', now()->subDays(7))),

            Tab::make('with-image')
                ->label('З зображенням')
                ->icon('heroicon-o-photo')
                ->query(fn($query) => $query->whereNotNull('image')),

            Tab::make('without-image')
                ->label('Без зображення')
                ->icon('heroicon-o-no-symbol')
                ->query(fn($query) => $query->whereNull('image')),
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
            StudioStats::class
        ];
    }
}
