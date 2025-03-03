<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Widgets;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Models\User;

class LatestUsers extends TableWidget
{
    protected static ?string $heading = 'Останні зареєстровані користувачі';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->latest()->limit(5))
            ->columns([
                ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl('/images/default-avatar.png')
                    ->width(40)
                    ->height(40),
                TextColumn::make('name')
                    ->label('Ім’я')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Роль')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        Role::USER => 'success',
                        Role::MODERATOR => 'primary',
                        Role::ADMIN => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Дата реєстрації')
                    ->dateTime('d-m-Y H:i'),
            ])
            ->paginated(false); // Вимикаємо пагінацію
    }
}
