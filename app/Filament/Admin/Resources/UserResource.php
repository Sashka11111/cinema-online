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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Select::make('role')
                ->options(Role::values())
                ->default(Role::USER->value)
                ->required(),
            TextInput::make('avatar')
                ->url()
                ->maxLength(2048),
            TextInput::make('backdrop')
                ->url()
                ->maxLength(2048),
            Select::make('gender')
                ->options(Gender::values())
                ->nullable(),
            TextInput::make('description')
                ->maxLength(248),
            DatePicker::make('birthday')
                ->nullable(),
            Checkbox::make('allow_adult')
                ->label('Allow Adult Content')
                ->default(false),
            Checkbox::make('is_auto_next')
                ->label('Auto Next')
                ->default(false),
            Checkbox::make('is_auto_play')
                ->label('Auto Play')
                ->default(false),
            Checkbox::make('is_auto_skip_intro')
                ->label('Skip Intro Automatically')
                ->default(false),
            Checkbox::make('is_private_favorites')
                ->label('Private Favorites')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('role')->sortable()->searchable(),
            TextColumn::make('gender')->sortable()->searchable(),
            ImageColumn::make('avatar') // Відображає зображення аватара
            ->label('Avatar')
                ->disk('public') // Вказуємо диск (наприклад, 'public')
                ->width(50) // Ширина зображення
                ->height(50), // Висота зображення
            TextColumn::make('birthday')
                ->sortable()
                ->label('Birthday')
                ->date('d-m-Y'),
            IconColumn::make('allow_adult') // Використовуємо іконку для булевого значення
            ->label('Allow Adult')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable(),
            IconColumn::make('is_auto_next')
                ->label('Auto Next')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable(),
            IconColumn::make('is_auto_play')
                ->label('Auto Play')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable(),
            IconColumn::make('is_auto_skip_intro')
                ->label('Skip Intro')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable(),
            IconColumn::make('is_private_favorites')
                ->label('Private Favorites')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->sortable(),
            TextColumn::make('last_seen_at')
                ->sortable()
                ->label('Last Seen')
                ->date('d-m-Y H:i:s'),
        ])
            ->filters([
                // Add necessary filters here
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
