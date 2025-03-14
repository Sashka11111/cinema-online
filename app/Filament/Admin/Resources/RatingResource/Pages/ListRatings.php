<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RatingResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\RatingResource;
use Liamtseva\Cinema\Filament\Admin\Resources\RatingResource\Widgets\RatingStatsOverview;

class ListRatings extends ListRecords
{
    protected static string $resource = RatingResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('Усі оцінки')
                ->icon('heroicon-o-star')
                ->query(fn ($query) => $query),

            Tab::make('high')
                ->label('Високі оцінки')
                ->icon('heroicon-o-arrow-up-circle')
                ->query(fn ($query) => $query->where('number', '>=', 8)),

            Tab::make('medium')
                ->label('Середні оцінки')
                ->icon('heroicon-o-arrow-right-circle')
                ->query(fn ($query) => $query->whereBetween('number', [5, 7])),

            Tab::make('low')
                ->label('Низькі оцінки')
                ->icon('heroicon-o-arrow-down-circle')
                ->query(fn ($query) => $query->where('number', '<=', 4)),

            Tab::make('with_reviews')
                ->label('З відгуками')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->query(fn ($query) => $query->whereNotNull('review')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RatingStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
