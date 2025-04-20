<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;

    protected static ?string $title = 'Створити тег';
}
