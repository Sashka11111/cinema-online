<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\CommentLike;

class CommentLikeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalLikes = CommentLike::where('is_liked', true)->count();
        $totalDislikes = CommentLike::where('is_liked', false)->count();
        $totalReactions = $totalLikes + $totalDislikes;

        $likePercentage = $totalReactions > 0
            ? round(($totalLikes / $totalReactions) * 100, 1)
            : 0;

        $dislikePercentage = $totalReactions > 0
            ? round(($totalDislikes / $totalReactions) * 100, 1)
            : 0;

        return [
            Stat::make('Загальна кількість реакцій', $totalReactions)
                ->description('Всі реакції на коментарі')
                ->icon('heroicon-o-heart')
                ->color('primary'),

            Stat::make('Лайки', $totalLikes)
                ->description("{$likePercentage}% від загальної кількості")
                ->icon('heroicon-o-hand-thumb-up')
                ->color('success'),

            Stat::make('Дизлайки', $totalDislikes)
                ->description("{$dislikePercentage}% від загальної кількості")
                ->icon('heroicon-o-hand-thumb-down')
                ->color('danger'),
        ];
    }
}
