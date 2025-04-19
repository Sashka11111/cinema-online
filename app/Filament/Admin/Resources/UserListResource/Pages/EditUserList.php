<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\UserListResource;

class EditUserList extends EditRecord
{
    protected static string $resource = UserListResource::class;

    protected static ?string $title = 'Редагувати список користувача';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
