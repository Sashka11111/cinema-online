<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\UserListResource;

class CreateUserList extends CreateRecord
{
    protected static string $resource = UserListResource::class;

    protected static ?string $title = 'Створити список користувача';
}
