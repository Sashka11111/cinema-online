<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource;

class CreateCommentReport extends CreateRecord
{
    protected static string $resource = CommentReportResource::class;

    protected static ?string $title = 'Додати реакцію на коментар';
}
