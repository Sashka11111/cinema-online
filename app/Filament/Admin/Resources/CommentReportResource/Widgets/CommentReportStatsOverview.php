<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\CommentReport;

class CommentReportStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalReports = CommentReport::count();
        $unresolvedReports = CommentReport::where('is_viewed', false)->count();
        $viewedReports = CommentReport::where('is_viewed', true)->count();

        return [
            Stat::make('Всього скарг', $totalReports)
                ->description('Загальна кількість')
                ->color('primary')
                ->icon('heroicon-o-document-text'),

            Stat::make('Переглянуті скарги', $viewedReports)
                ->description('Вже переглянуто')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Невирішені скарги', $unresolvedReports)
                ->description('Очікують на вирішення')
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle'),

        ];
    }
}
