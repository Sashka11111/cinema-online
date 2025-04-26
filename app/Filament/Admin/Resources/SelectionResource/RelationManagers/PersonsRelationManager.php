<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Models\Person;

class PersonsRelationManager extends RelationManager
{
    protected static string $relationship = 'persons';

    protected static ?string $modelLabel = 'особу';

    protected static ?string $pluralModelLabel = 'особи';

    protected static ?string $title = 'Особи';

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
                    ->label("Ім'я")
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->toggleable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Додати особу')
                    ->recordSelect(
                        fn (Select $select) => $select
                            ->label('Особа')
                            ->options(
                                Person::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                    )
                    ->form(fn () => [
                        Select::make('person_id')
                            ->label('Особа')
                            ->options(
                                Person::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Перевіряємо, чи особа вже додана до підбірки
                        if ($this->ownerRecord->persons()->where('people.id', $data['person_id'])->exists()) {
                            Notification::make()
                                ->title('Помилка')
                                ->body('Ця особа вже додана до підбірки')
                                ->danger()
                                ->send();

                            return;
                        }

                        $this->ownerRecord->persons()->attach($data['person_id']);
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
