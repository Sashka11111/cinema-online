<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Pages;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\RelationManagers\UsersRelationManager;
use Liamtseva\Cinema\Models\Room;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Користувацька активність';

    protected static ?string $navigationLabel = 'Кімнати';

    protected static ?string $modelLabel = 'кімнату';

    protected static ?string $pluralModelLabel = 'кімнати';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Власник')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('episode.name')
                    ->label('Епізод')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('room_status')
                    ->label('Статус')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('max_viewers')
                    ->label('Максимальна кількість глядачів')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('started_at')
                    ->label('Початок')
                    ->dateTime('d.m.Y H:i')
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('ended_at')
                    ->label('Завершення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Активна'),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->label('Власник')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('episode')
                    ->label('Епізод')
                    ->relationship('episode', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('room_status')
                    ->label('Статус')
                    ->options(RoomStatus::class),

                TernaryFilter::make('is_private')
                    ->label('Приватність'),
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Назва')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                                if ($operation == 'edit' || empty($state)) {
                                    return;
                                }
                                $set('slug', Room::generateSlug($state));
                            })
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(128)
                            ->unique(Room::class, 'slug', ignoreRecord: true)
                            ->helperText('Автоматично генерується з назви'),

                        Select::make('user_id')
                            ->label('Власник')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('episode_id')
                            ->label('Епізод')
                            ->relationship('episode', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Налаштування кімнати')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        Select::make('room_status')
                            ->label('Статус')
                            ->options(RoomStatus::class)
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload(),

                        Toggle::make('is_private')
                            ->label('Приватна кімната')
                            ->default(false)
                            ->reactive(),

                        TextInput::make('password')
                            ->label('Пароль')
                            ->password()
                            ->visible(fn (callable $get) => $get('is_private'))
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->maxLength(255),

                        TextInput::make('max_viewers')
                            ->label('Максимальна кількість глядачів')
                            ->numeric()
                            ->default(10)
                            ->minValue(1)
                            ->maxValue(100)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Статус кімнати')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        DateTimePicker::make('started_at')
                            ->label('Час початку')
                            ->nullable(),

                        DateTimePicker::make('ended_at')
                            ->label('Час завершення')
                            ->nullable()
                            ->after('started_at'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'view' => Pages\ViewRoom::route('/{record}'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
