<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource\Pages;
use Liamtseva\Cinema\Models\WatchHistory;

class WatchHistoryResource extends Resource
{
    protected static ?string $model = WatchHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Історія переглядів';

    protected static ?string $modelLabel = 'історію перегляду';

    protected static ?string $pluralModelLabel = 'Історія переглядів';

    protected static ?string $navigationGroup = 'Історія';

    protected static ?int $navigationSort = 1;

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

                TextColumn::make('episode.name')
                    ->label('Епізод')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('progress_time')
                    ->label('Прогрес')
                    ->formatStateUsing(fn (int $state) => gmdate('H:i:s', $state))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Користувач')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('episode')
                    ->relationship('episode', 'name')
                    ->label('Епізод')
                    ->searchable()
                    ->preload(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Дата сторення від'),
                        DatePicker::make('created_until')
                            ->label('Дата сторення до'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query) => $query->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($query) => $query->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Group::make('user.name')
                    ->label('Користувач'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWatchHistories::route('/'),
            'view' => Pages\ViewWatchHistory::route('/{record}'),
        ];
    }
}
