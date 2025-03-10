<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Widgets\TagCreationChart;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Widgets\TagTypeStats;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Widgets\TagUsageChart;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('Усі теги')
                ->icon('heroicon-o-tag')
                ->query(fn ($query) => $query),

            Tab::make('genres')
                ->label('Жанри')
                ->icon('bx-category')
                ->query(fn ($query) => $query->where('is_genre', true)),

            Tab::make('non-genres')
                ->label('Звичайні теги')
                ->icon('fas-wand-sparkles')
                ->query(fn ($query) => $query->where('is_genre', false)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TagCreationChart::class,
            TagUsageChart::class,
            TagTypeStats::class,
        ];
    }
}
