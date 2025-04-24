<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource;

class EditStudio extends EditRecord
{
    protected static string $resource = StudioResource::class;

    protected static ?string $title = 'Редагувати студію';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
