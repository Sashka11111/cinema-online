<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\Tag;

class TagTypeStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Загальна кількість тегів', Tag::count())
                ->color('primary')
                ->icon('heroicon-o-tag'),
            Stat::make('Жанри', Tag::where('is_genre', true)->count())
                ->color('danger')
                ->icon('bx-category'),
            Stat::make('Звичайні теги', Tag::where('is_genre', false)->count())
                ->color('success')
                ->icon('fas-wand-sparkles'),
        ];
    }
}
