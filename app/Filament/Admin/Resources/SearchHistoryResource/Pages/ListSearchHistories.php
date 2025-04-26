<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource;
use Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource\Widgets\SearchHistoryStatsOverview;

class ListSearchHistories extends ListRecords
{
    protected static string $resource = SearchHistoryResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SearchHistoryStatsOverview::class,
        ];
    }
}
