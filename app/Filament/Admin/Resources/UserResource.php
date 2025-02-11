<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Pages;
use Liamtseva\Cinema\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Користувачі';

    protected static ?string $modelLabel = 'Користувача';

    protected static ?string $pluralModelLabel = 'Користувачі';

    protected static ?string $navigationGroup = 'Користувачі та взаємодія з контентом';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Ім’я')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('email')
                ->label('Електронна пошта')
                ->required()
                ->email()
                ->maxLength(255),
            TextInput::make('password')
                ->label('Пароль')
                ->password()
                ->required()
                ->maxLength(255)
                ->visibleOn('create'),
            DatePicker::make('email_verified_at')
                ->label('Дата підтвердження пошти')
                ->nullable(),
            TextInput::make('remember_token')
                ->label('Токен запам’ятовування')
                ->maxLength(100),
            Select::make('role')
                ->label('Роль')
                ->options(Role::values())
                ->default(Role::USER->value)
                ->required(),
            TextInput::make('avatar')
                ->label('Аватар')
                ->url()
                ->maxLength(2048),
            TextInput::make('backdrop')
                ->label('Фонове зображення')
                ->url()
                ->maxLength(2048),
            Select::make('gender')
                ->label('Стать')
                ->options(Gender::values())
                ->nullable(),
            TextInput::make('description')
                ->label('Опис')
                ->maxLength(248),
            DatePicker::make('birthday')
                ->label('Дата народження')
                ->nullable(),
            Checkbox::make('allow_adult')
                ->label('Дозволити дорослий контент')
                ->default(false),
            Checkbox::make('is_auto_next')
                ->label('Автоперехід')
                ->default(false),
            Checkbox::make('is_auto_play')
                ->label('Автовідтворення')
                ->default(false),
            Checkbox::make('is_auto_skip_intro')
                ->label('Пропуск вступу автоматично')
                ->default(false),
            Checkbox::make('is_private_favorites')
                ->label('Приватне обране')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('avatar')
                ->label('Аватар')
                ->disk('public')
                ->width(50)
                ->height(50)
                ->circular()
                ->toggleable(),

            TextColumn::make('name')
                ->label('Ім\'я та пошта')
                ->description(fn(User $user): string => $user->email)
                ->searchable()
                ->sortable(),

            TextColumn::make('role')
                ->label('Роль')
                ->badge()
                ->color(fn(User $user): string => match ($user->role) {
                    Role::USER => 'success',       // Звичайний користувач - зелений
                    Role::MODERATOR => 'primary',  // Модератор - синій
                    Role::ADMIN => 'danger',       // Адмін - червоний
                })
                ->icon(fn(User $user): string => match ($user->role) {
                    Role::USER => 'heroicon-o-user',
                    Role::MODERATOR => 'tabler-user-cog',
                    Role::ADMIN => 'ri-admin-line',
                })
                ->searchable()
                ->sortable(),

            TextColumn::make('gender')
                ->label('Стать')
                ->badge()
                ->color(fn(User $user): string => match ($user->gender) {
                    Gender::MALE => 'info',       // Чоловіки - синій
                    Gender::FEMALE => 'warning',     // Жінки - рожевий
                    Gender::OTHER => 'gray',      // Інші - сірий
                })
                ->icon(fn(User $user): string => match ($user->gender) {
                    Gender::MALE => 'fas-male',  // Іконка для чоловіків
                    Gender::FEMALE => 'fas-female',       // Іконка для жінок
                    Gender::OTHER => 'bx-male-female', // Іконка для інших
                })
                ->sortable()
                ->searchable()
                ->toggleable(),

            TextColumn::make('birthday')
                ->label('Дата народження')
                ->sortable()
                ->searchable()
                ->date('d-m-Y')
                ->toggleable(),

            TextColumn::make('last_seen_at')
                ->label('Востаннє бачили')
                ->badge()
                ->color(fn(User $user): string => match (true) {
                    !$user->last_seen_at => 'gray', // Якщо не заходив
                    now()->diffInHours(Carbon::parse($user->last_seen_at)) < 1 => 'success',
                    default => 'warning',  // Давно не заходив
                })
                ->formatStateUsing(fn(User $user) => $user->last_seen_at
                    ? Carbon::parse($user->last_seen_at)->diffForHumans()
                    : 'Ніколи')
                ->sortable()
                ->searchable()
                ->toggleable()

        ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Роль')
                    ->options([
                        Role::USER->value => 'Користувач',
                        Role::MODERATOR->value => 'Модератор',
                        Role::ADMIN->value => 'Адмін',
                    ]),
                SelectFilter::make('gender')
                    ->label('Стать')
                    ->options([
                        Gender::MALE->value => 'Чоловік',
                        Gender::FEMALE->value => 'Жінка',
                        Gender::OTHER->value => 'Інше',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            // Add relations if any
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
