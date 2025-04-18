<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource;

class CreateSelection extends CreateRecord
{
    protected static string $resource = SelectionResource::class;

    protected static ?string $title = 'Створити підбірку';
}
