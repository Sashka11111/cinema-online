<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
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
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\Tag;
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
        $dummyModel = new UserList;

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
                        MorphToSelect::make('listable')
                            ->types([
                                MorphToSelect\Type::make(Movie::class)
                                    ->titleAttribute('name')
                                    ->label('Фільм'),
                                MorphToSelect\Type::make(Episode::class)
                                    ->titleAttribute('name'),
                                MorphToSelect\Type::make(Selection::class)
                                    ->titleAttribute('name'),
                                MorphToSelect\Type::make(Person::class)
                                    ->titleAttribute('name'),
                                MorphToSelect\Type::make(Tag::class)
                                    ->titleAttribute('name'),
                            ]),
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
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewUserList::route('/{record}'),
            'edit' => Pages\EditUserList::route('/{record}/edit'),
        ];
    }
}
