<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}
