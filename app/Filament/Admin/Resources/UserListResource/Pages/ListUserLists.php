<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\UserListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserLists extends ListRecords
{
    protected static string $resource = UserListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
