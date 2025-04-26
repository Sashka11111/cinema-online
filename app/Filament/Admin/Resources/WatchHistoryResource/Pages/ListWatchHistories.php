<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource;
use Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource\Widgets\WatchHistoryStatsOverview;

class ListWatchHistories extends ListRecords
{
    protected static string $resource = WatchHistoryResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            WatchHistoryStatsOverview::class,
        ];
    }
}
