<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudio extends EditRecord
{
    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
