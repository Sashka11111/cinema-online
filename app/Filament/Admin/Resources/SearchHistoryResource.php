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
use Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource\Pages;
use Liamtseva\Cinema\Models\SearchHistory;

class SearchHistoryResource extends Resource
{
    protected static ?string $model = SearchHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Історія пошуку';

    protected static ?string $modelLabel = 'історію пошуку';

    protected static ?string $pluralModelLabel = 'історії пошуку';

    protected static ?string $navigationGroup = 'Історія';

    protected static ?int $navigationSort = 2;

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

                TextColumn::make('query')
                    ->label('Пошуковий запит')
                    ->searchable()
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
            'index' => Pages\ListSearchHistories::route('/'),
            'view' => Pages\ViewSearchHistory::route('/{record}'),
        ];
    }
}
