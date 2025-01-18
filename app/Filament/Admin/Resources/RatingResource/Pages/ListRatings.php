<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RatingResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\RatingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRatings extends ListRecords
{
    protected static string $resource = RatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
