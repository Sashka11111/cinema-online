<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Models\Episode;

class EpisodesRelationManager extends RelationManager
{
    protected static string $relationship = 'episodes';

    protected static ?string $title = 'Епізоди';

    protected static ?string $modelLabel = 'епізод';

    protected static ?string $pluralModelLabel = 'епізоди';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('number')
                    ->label('Номер епізоду')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn (Episode $record): ?string => $record->description)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('duration')
                    ->label('Тривалість')
                    ->formatStateUsing(fn ($state) => $state ? "$state хв" : '-')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                ToggleColumn::make('is_filler')
                    ->label('Філер')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('air_date')
                    ->label('Дата виходу')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_filler')
                    ->label('Тип епізоду')
                    ->trueLabel('Філерні')
                    ->falseLabel('Не філерні'),

                Filter::make('air_date')
                    ->form([
                        DatePicker::make('air_date_from')
                            ->label('Дата виходу від'),
                        DatePicker::make('air_date_to')
                            ->label('Дата виходу до'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['air_date_from'], fn ($query, $date) => $query->where('air_date', '>=', $date))
                            ->when($data['air_date_to'], fn ($query, $date) => $query->where('air_date', '<=', $date));
                    }),

                Filter::make('duration')
                    ->form([
                        TextInput::make('duration_from')
                            ->label('Тривалість від (хв)')
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('duration_to')
                            ->label('Тривалість до (хв)')
                            ->numeric()
                            ->maxValue(65535),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['duration_from'], fn ($query, $duration) => $query->where('duration', '>=', $duration))
                            ->when($data['duration_to'], fn ($query, $duration) => $query->where('duration', '<=', $duration));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
