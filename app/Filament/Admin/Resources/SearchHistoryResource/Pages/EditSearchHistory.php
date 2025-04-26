<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSearchHistory extends EditRecord
{
    protected static string $resource = SearchHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
