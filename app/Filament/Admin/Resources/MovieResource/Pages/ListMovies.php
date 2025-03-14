<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Widgets\MovieCreationChart;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Widgets\MovieRatingDistribution;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Widgets\MovieStatsOverview;

class ListMovies extends ListRecords
{
    protected static string $resource = MovieResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('Усі фільми')
                ->icon('heroicon-o-film')
                ->query(fn ($query) => $query),

            Tab::make('Фільми')
                ->icon('heroicon-o-video-camera')
                ->query(fn ($query) => $query->where('kind', Kind::MOVIE)),

            Tab::make('Серіали')
                ->icon('heroicon-o-tv')
                ->query(fn ($query) => $query->where('kind', Kind::TV_SERIES)),

            Tab::make('Аніме')
                ->icon('heroicon-o-sparkles')
                ->query(fn ($query) => $query->where('kind', Kind::ANIME)),

            Tab::make('У процесі')
                ->icon('heroicon-o-play')
                ->query(fn ($query) => $query->where('status', Status::ONGOING)),

            Tab::make('Випущені')
                ->icon('heroicon-o-check-circle')
                ->query(fn ($query) => $query->where('status', Status::RELEASED)),

            Tab::make('Анонсовані')
                ->icon('heroicon-o-clock')
                ->query(fn ($query) => $query->where('status', Status::ANONS)),

            Tab::make('Нещодавно додані')
                ->icon('heroicon-o-calendar')
                ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(7))),
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
            MovieCreationChart::class,
            MovieRatingDistribution::class,
            MovieStatsOverview::class,
        ];
    }
}
