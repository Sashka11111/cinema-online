<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Movie;

class CommentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalComments = Comment::count();
        $spoilerComments = Comment::where('is_spoiler', true)->count();
        $movieComments = Comment::where('commentable_type', Movie::class)->count();

        return [
            Stat::make('Всього коментарів', $totalComments)
                ->description('Загальна кількість')
                ->color('primary')
                ->icon('heroicon-o-chat-bubble-left-ellipsis'),

            Stat::make('Спойлери', $spoilerComments)
                ->description('Коментарі зі спойлерами')
                ->color('warning')
                ->icon('heroicon-o-eye-slash'),

            Stat::make('До фільмів', $movieComments)
                ->description('Коментарі до фільмів')
                ->color('info')
                ->icon('heroicon-o-film'),
        ];
    }
}
