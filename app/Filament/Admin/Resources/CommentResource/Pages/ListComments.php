<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Widgets\CommentCreationChart;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Widgets\CommentStatsOverview;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Widgets\CommentTypeDistributionChart;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Selection;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('Усі коментарі')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->query(fn ($query) => $query),

            Tab::make('Коментарі до фільмів')
                ->icon('heroicon-o-film')
                ->query(fn ($query) => $query->where('commentable_type', Movie::class)),

            Tab::make('Коментарі до епізодів')
                ->icon('heroicon-o-puzzle-piece')
                ->query(fn ($query) => $query->where('commentable_type', Episode::class)),

            Tab::make('Коментарі до підбірок')
                ->icon('bx-collection')
                ->query(fn ($query) => $query->where('commentable_type', Selection::class)),

            Tab::make('Спойлери')
                ->icon('heroicon-o-eye-slash')
                ->query(fn ($query) => $query->where('is_spoiler', true)),

            Tab::make('Нещодавні')
                ->icon('heroicon-o-clock')
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
            CommentCreationChart::class,
            CommentTypeDistributionChart::class,
            CommentStatsOverview::class,
        ];
    }
}
