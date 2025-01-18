<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePerson extends CreateRecord
{
    protected static string $resource = PersonResource::class;
}
