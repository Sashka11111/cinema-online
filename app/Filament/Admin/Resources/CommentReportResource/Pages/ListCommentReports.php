<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Widgets\CommentReportStatsOverview;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Widgets\CommentReportTrendChart;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Widgets\CommentReportTypeDistributionChart;

class ListCommentReports extends ListRecords
{
    protected static string $resource = CommentReportResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('Усі скарги')
                ->icon('heroicon-o-document-text')
                ->query(fn ($query) => $query),

            Tab::make('Переглянуті')
                ->icon('heroicon-o-check-circle')
                ->query(fn ($query) => $query->where('is_viewed', true)),

            Tab::make('Не переглянуті')
                ->icon('heroicon-o-x-circle')
                ->query(fn ($query) => $query->where('is_viewed', false)),

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
            CommentReportTrendChart::class,
            CommentReportTypeDistributionChart::class,
            CommentReportStatsOverview::class,
        ];
    }
}
