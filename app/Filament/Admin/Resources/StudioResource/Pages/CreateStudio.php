<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource;

class CreateStudio extends CreateRecord
{
    protected static string $resource = StudioResource::class;

    protected static ?string $title = 'Створити студію';
}
