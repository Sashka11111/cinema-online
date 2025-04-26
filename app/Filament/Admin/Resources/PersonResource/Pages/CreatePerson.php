<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\PersonResource;

class CreatePerson extends CreateRecord
{
    protected static string $resource = PersonResource::class;

    protected static ?string $title = 'Додати персону';
}
