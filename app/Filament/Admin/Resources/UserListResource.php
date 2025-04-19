<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Pages;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\UserList;

class UserListResource extends Resource
{
    protected static ?string $model = UserList::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Списки користувача';

    protected static ?string $modelLabel = 'список користувача';

    protected static ?string $pluralModelLabel = 'Списки користувача';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Select::make('user_id')
                            ->label('Користувач')
                            ->relationship('user', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->prefixIcon('heroicon-o-user'),

                        Select::make('type')
                            ->label('Тип списку')
                            ->options(UserListType::class)
                            ->required()
                            ->prefixIcon('heroicon-o-list-bullet'),

                        Select::make('listable_type')
                            ->label('Тип елемента')
                            ->required()
                            ->default(Movie::class)
                            ->prefixIcon('heroicon-o-identification'),

                        Select::make('listable_id')
                            ->label('Елемент списку')
                            ->options(function () {
                                return Movie::pluck('name', 'id')->toArray();
                            })
                            ->required()
                            ->searchable()
                            ->prefixIcon('heroicon-o-film')
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('listable_type', Movie::class);
                            }),

                        DateTimePicker::make('created_at')
                            ->label('Дата створення')
                            ->prefixIcon('heroicon-o-calendar')
                            ->displayFormat('d.m.Y H:i')
                            ->disabled()
                            ->default(now())
                            ->hiddenOn('create'),

                        DateTimePicker::make('updated_at')
                            ->label('Дата оновлення')
                            ->prefixIcon('heroicon-o-clock')
                            ->displayFormat('d.m.Y H:i')
                            ->disabled()
                            ->default(now())
                            ->hiddenOn('create'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Тип списку')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('listable.name')
                    ->label('Елемент списку')
                    ->getStateUsing(fn ($record) => $record->listable ? $record->listable->name : '—')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('listable_type')
                    ->label('Тип елемента')
                    ->sortable()
                    ->sortable()
                    ->getStateUsing(fn (UserList $userList) => $userList->translated_type)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Користувач')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('type')
                    ->label('Тип списку')
                    ->options(UserListType::class),
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
            // Можна додати RelationManager для listable, якщо потрібно
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserLists::route('/'),
            'create' => Pages\CreateUserList::route('/create'),
            'edit' => Pages\EditUserList::route('/{record}/edit'),
        ];
    }
}
