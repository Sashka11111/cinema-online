<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
