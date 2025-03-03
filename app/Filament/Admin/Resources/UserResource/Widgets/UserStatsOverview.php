<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Models\User;

class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $admins = User::where('role', Role::ADMIN)->count();
        $activeUsers = User::where('last_seen_at', '>=', now()->subDays(7))->count();

        return [
            Stat::make('Всього користувачів', $totalUsers)
                ->description('Загальна кількість')
                ->color('primary')
                ->icon('heroicon-o-users'),
            Stat::make('Адміністратори', $admins)
                ->description('Кількість адмінів')
                ->color('danger')
                ->icon('ri-admin-line'),
            Stat::make('Активні за тиждень', $activeUsers)
                ->description('Останні 7 днів')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
