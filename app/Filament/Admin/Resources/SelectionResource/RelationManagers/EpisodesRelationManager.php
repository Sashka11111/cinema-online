<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Models\Episode;

class EpisodesRelationManager extends RelationManager
{
    protected static string $relationship = 'episodes';

    protected static ?string $modelLabel = 'епізод';

    protected static ?string $pluralModelLabel = 'епізоди';

    protected static ?string $title = 'Епізоди';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('movie.name')
                    ->label('Фільм')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('number')
                    ->label('Номер')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->toggleable(),

                TextColumn::make('air_date')
                    ->label('Дата виходу')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn () => [
                        Select::make('episode_id')
                            ->label('Епізод')
                            ->options(
                                Episode::query()
                                    ->join('movies', 'episodes.movie_id', '=', 'movies.id')
                                    ->where('movies.is_published', true)
                                    ->selectRaw("episodes.id, CONCAT(movies.name, ' - Епізод ', episodes.number, ': ', episodes.name) as full_name")
                                    ->pluck('full_name', 'episodes.id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        if ($this->ownerRecord->episodes()->where('episodes.id', $data['episode_id'])->exists()) {
                            Notification::make()
                                ->title('Помилка')
                                ->body('Цей епізод вже додано до підбірки')
                                ->danger()
                                ->send();

                            return;
                        }

                        $this->ownerRecord->episodes()->attach($data['episode_id']);
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
