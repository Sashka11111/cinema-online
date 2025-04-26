<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWatchHistory extends EditRecord
{
    protected static string $resource = WatchHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
