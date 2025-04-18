<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource;

class EditSelection extends EditRecord
{
    protected static string $resource = SelectionResource::class;

    protected static ?string $title = 'Редагувати підбірку';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
