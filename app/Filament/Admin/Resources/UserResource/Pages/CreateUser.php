<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
