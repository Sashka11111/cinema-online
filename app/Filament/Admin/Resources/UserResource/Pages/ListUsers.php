<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('Усі користувачі')
                ->query(fn($query) => $query),

            Tab::make('admins')
                ->label('Адміністратори')
                ->query(fn($query) => $query->where('role', 'admin')),

            Tab::make('users')
                ->label('Користувачі')
                ->query(fn($query) => $query->where('role', 'user')),

            Tab::make('moderators')
                ->label('Модератори')
                ->query(fn($query) => $query->where('role', 'moderator')),

            Tab::make('unverified')
                ->label('Непідтверджені')
                ->query(fn($query) => $query->whereNull('email_verified_at')),
        ];
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
