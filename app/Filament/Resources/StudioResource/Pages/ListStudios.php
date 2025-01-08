<?php

namespace Liamtseva\Cinema\Filament\Resources\StudioResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Resources\StudioResource;

class ListStudios extends ListRecords
{
    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
