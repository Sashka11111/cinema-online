<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Models\Movie;

class MoviesRelationManager extends RelationManager
{
    protected static string $relationship = 'movies';

    protected static ?string $pluralModelLabel = 'фільми';

    protected static ?string $modelLabel = 'фільм';

    protected static ?string $title = 'Фільми в підбірці';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Додати фільм')
                    ->recordSelect(
                        fn (Select $select) => $select
                            ->label('Фільм')
                            ->options(
                                Movie::where('is_published', true)
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                    )
                    ->form(fn () => [
                        Select::make('movie_id')
                            ->label('Фільм')
                            ->options(
                                Movie::where('is_published', true)
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Перевіряємо чи фільм вже існує в підбірці
                        if ($this->ownerRecord->movies()->where('movies.id', $data['movie_id'])->exists()) {
                            Notification::make()
                                ->title('Помилка')
                                ->body('Цей фільм вже додано до підбірки')
                                ->danger()
                                ->send();

                            return;
                        }

                        $this->ownerRecord->movies()->attach($data['movie_id']);
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Видалити'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Видалити вибрані'),
                ]),
            ]);
    }
}
