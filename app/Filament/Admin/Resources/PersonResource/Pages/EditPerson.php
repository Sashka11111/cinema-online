<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\PersonResource;

class EditPerson extends EditRecord
{
    protected static string $resource = PersonResource::class;

    protected static ?string $title = 'Редагувати персону';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
