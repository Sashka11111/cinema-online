<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource\Widgets\CommentLikeStatsOverview;

class ListCommentLikes extends ListRecords
{
    protected static string $resource = CommentLikeResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('Усі реакції')
                ->icon('heroicon-o-heart')
                ->query(fn ($query) => $query),

            Tab::make('Лайки')
                ->icon('heroicon-o-hand-thumb-up')
                ->query(fn ($query) => $query->where('is_liked', true)),

            Tab::make('Дизлайки')
                ->icon('heroicon-o-hand-thumb-down')
                ->query(fn ($query) => $query->where('is_liked', false)),

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
            CommentLikeStatsOverview::class,
        ];
    }
}
