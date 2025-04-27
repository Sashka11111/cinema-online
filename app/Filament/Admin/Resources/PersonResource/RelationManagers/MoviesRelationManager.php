<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Scopes\PublishedScope;

class MoviesRelationManager extends RelationManager
{
    protected static string $relationship = 'movies';

    protected static ?string $title = 'Фільми';

    protected static ?string $modelLabel = 'фільм';

    protected static ?string $pluralModelLabel = 'фільми';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster')
                    ->label('Постер')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('original_name')
                    ->label('Оригінальна назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('is_published')
                    ->label('Опубліковано')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('release_date')
                    ->label('Дата виходу')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('kind')
                    ->label('Тип')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('period')
                    ->label('Період')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('restricted_rating')
                    ->label('Вікове обмеження')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('source')
                    ->label('Джерело')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('pivot.character_name')
                    ->label('Ім\'я персонажа')
                    ->toggleable(),
            ])
            ->modifyQueryUsing(fn ($query) => $query->withoutGlobalScope(PublishedScope::class))
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Опубліковано'),

                SelectFilter::make('kind')
                    ->label('Тип')
                    ->options(Kind::class),

                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(Status::class),

                SelectFilter::make('period')
                    ->label('Період')
                    ->options(Period::class),

                SelectFilter::make('restricted_rating')
                    ->label('Вікове обмеження')
                    ->options(RestrictedRating::class),

                SelectFilter::make('source')
                    ->label('Джерело')
                    ->options(Source::class),
            ])
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

                        TextInput::make('character_name')
                            ->label('Ім\'я персонажа')
                            ->required()
                            ->maxLength(128),
                    ])
                    ->action(function (array $data) {
                        // Перевіряємо чи фільм вже існує в підбірці
                        if ($this->ownerRecord->movies()->where('movies.id', $data['movie_id'])->exists()) {
                            Notification::make()
                                ->title('Помилка')
                                ->body('Ця особа вже додана до цього фільму')
                                ->danger()
                                ->send();

                            return;
                        }

                        $this->ownerRecord->movies()->attach($data['movie_id'], [
                            'character_name' => $data['character_name'],
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Вилучити'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label('Вилучити вибрані'),
            ]);
    }
}
