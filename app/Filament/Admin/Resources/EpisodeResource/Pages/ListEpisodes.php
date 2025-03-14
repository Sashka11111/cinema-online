<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Widgets\EpisodeAirStatusChart;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Widgets\EpisodeCreationChart;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Widgets\EpisodeStatsOverview;

class ListEpisodes extends ListRecords
{
    protected static string $resource = EpisodeResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Усі епізоди')
                ->icon('heroicon-o-puzzle-piece')
                ->query(fn ($query) => $query),

            'filler' => Tab::make('Філерні епізоди')
                ->icon('heroicon-o-check-circle')
                ->query(fn ($query) => $query->where('is_filler', true)),

            'non_filler' => Tab::make('Не філерні епізоди')
                ->icon('heroicon-o-x-circle')
                ->query(fn ($query) => $query->where('is_filler', false)),

            'recent' => Tab::make('Нещодавно додані')
                ->icon('heroicon-o-clock')
                ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(7))),

            'aired' => Tab::make('Вийшли в ефір')
                ->icon('heroicon-o-calendar')
                ->query(fn ($query) => $query->whereNotNull('air_date')->where('air_date', '<=', now())),
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
            EpisodeCreationChart::class,
            EpisodeAirStatusChart::class,
            EpisodeStatsOverview::class,
        ];
    }
}
