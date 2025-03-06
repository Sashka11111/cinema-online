<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Models\User;

class LatestUsers extends BaseWidget
{
    protected ?string $heading = 'Останні активні користувачі';

    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        $latestUsers = User::latest()->limit(6)->get();

        $stats = [];

        foreach ($latestUsers as $user) {
            $role = $user->role;
            $stats[] = Stat::make($user->name, '')
                ->icon($this->getRoleIcon($role))
                ->description("Роль: {$role->value} | Зареєстровано: {$user->created_at->format('d.m.Y')}")
                ->color('primary');
        }

        return $stats;
    }

    protected function getRoleIcon(Role $role): string
    {
        return match ($role) {
            Role::USER => 'heroicon-o-user',
            Role::MODERATOR => 'tabler-user-cog',
            Role::ADMIN => 'ri-admin-line',
        };
    }

}
