<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Models\Studio;

class RecentStudios extends BaseWidget
{
    protected ?string $heading = 'Останні додані студії';

    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        $latestStudios = Studio::latest('created_at')->limit(6)->get();

        $stats = [];

        foreach ($latestStudios as $studio) {
            $stats[] = Stat::make($studio->name, '')
                ->icon('heroicon-o-building-office')
                ->description("Додано: {$studio->created_at->format('d.m.Y H:i')}")
                ->color('success');
        }

        return $stats;
    }
}
