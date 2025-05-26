<?php

namespace Liamtseva\Cinema\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Widgets\CommentReportStatsOverview;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Widgets\EpisodeAirStatusChart;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Widgets\MovieRatingDistribution;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Widgets\RoomStatsOverview;
use Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Widgets\UserStatsOverview;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Головна';

    protected static ?string $navigationLabel = 'Головна';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            UserStatsOverview::class,
            CommentReportStatsOverview::class,
            RoomStatsOverview::class,
            MovieRatingDistribution::class,
            EpisodeAirStatusChart::class,
        ];
    }
}
