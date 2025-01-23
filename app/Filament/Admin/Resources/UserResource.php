<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Pages;
use Liamtseva\Cinema\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $pluralModelLabel = 'Користувачі';

    protected static ?string $navigationGroup = 'Користувачі та взаємодія з контентом';

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
            TextColumn::make('name')
                ->label('Ім’я')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('email')
                ->label('Електронна пошта')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('email_verified_at')
                ->label('Дата підтвердження пошти')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('role')
                ->label('Роль')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('gender')
                ->label('Стать')
                ->sortable()
                ->searchable()
                ->toggleable(),
            ImageColumn::make('avatar')
                ->label('Аватар')
                ->disk('public')
                ->width(50)
                ->height(50)
                ->toggleable(),
            TextColumn::make('birthday')
                ->label('Дата народження')
                ->sortable()
                ->searchable()
                ->date('d-m-Y')
                ->toggleable(),
            IconColumn::make('allow_adult')
                ->label('Дозволити дорослий контент')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable()
                ->toggleable(),
            IconColumn::make('is_auto_next')
                ->label('Автоперехід')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable()
                ->toggleable(),
            IconColumn::make('is_auto_play')
                ->label('Автовідтворення')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable()
                ->toggleable(),
            IconColumn::make('is_auto_skip_intro')
                ->label('Пропустити вступ')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable()
                ->toggleable(),
            IconColumn::make('is_private_favorites')
                ->label('Вибране')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable()
                ->toggleable(),
            TextColumn::make('last_seen_at')
                ->sortable()
                ->searchable()
                ->label('Востаннє бачили')
                ->date('d-m-Y H:i:s')
                ->toggleable(),
        ])
            ->filters([
//                TextFilter::make('name')
//                    ->label('Ім’я'),
//
//                TextFilter::make('email')
//                    ->label('Електронна пошта'),

//                SelectFilter::make('role')
//                    ->label('Роль')
//                    ->options(Role::values()) // Використовує правильні значення для порівняння
//                    ->default(Role::USER->value),
//
//                SelectFilter::make('gender')
//                    ->label('Стать')
//                    ->options(Gender::values()),
//
//                DateFilter::make('birthday')
//                    ->label('Дата народження')
//                    ->placeholder('Оберіть дату')
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
