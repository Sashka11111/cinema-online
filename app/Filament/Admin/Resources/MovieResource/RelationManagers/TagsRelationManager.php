<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Models\Tag;

class TagsRelationManager extends RelationManager
{
    protected static string $relationship = 'tags';

    protected static ?string $title = 'Теги';

    protected static ?string $modelLabel = 'тег';

    protected static ?string $pluralModelLabel = 'теги';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('image')
                    ->label('Зображення')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва та slug')
                    ->description(fn (Tag $tag): string => $tag->slug)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn (Tag $record): ?string => $record->description)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('aliases')
                    ->label('Аліаси')
                    ->formatStateUsing(function ($state) {
                        $decoded = json_decode($state, true);

                        return is_array($decoded) ? implode(', ', $decoded) : $state;
                    })
                    ->searchable()
                    ->toggleable()
                    ->wrap(),

                ToggleColumn::make('is_genre')
                    ->label('Жанр')
                    ->sortable()
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
                SelectFilter::make('is_genre')
                    ->label('Жанр')
                    ->options([
                        '1' => 'Так',
                        '0' => 'Ні',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Дата створення від')
                            ->placeholder('Виберіть дату'),
                        DatePicker::make('created_until')
                            ->label('До')
                            ->placeholder('Виберіть дату'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query) => $query->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($query) => $query->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
