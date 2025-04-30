<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\PersonType;
use Liamtseva\Cinema\Models\Person;

class PersonsRelationManager extends RelationManager
{
    protected static string $relationship = 'persons';

    protected static ?string $title = 'Персони';

    protected static ?string $modelLabel = 'персону';

    protected static ?string $pluralModelLabel = 'персони';

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
                    ->label('Ім’я')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('original_name')
                    ->label('Оригінальне ім’я')
                    ->default('Немає оригінального імені')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('character_name')
                    ->label('Персонаж')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('gender')
                    ->label('Стать')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('birthday')
                    ->label('Дата та місце народження')
                    ->formatStateUsing(fn (Person $record) => $record->birthday ? $record->birthday->format('d-m-Y').($record->birthplace ? ' ('.$record->birthplace.')' : '') : '-')
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
                    ->tooltip(fn (Person $record): ?string => $record->description)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('type')
                    ->label('Тип')
                    ->options(PersonType::class)
                    ->multiple(),

                SelectFilter::make('gender')
                    ->label('Стать')
                    ->options(Gender::class)
                    ->multiple(),

                Filter::make('birthday')
                    ->form([
                        DatePicker::make('birthday_from')->label('Дата народження від'),
                        DatePicker::make('birthday_to')->label('Дата народження до'),
                    ])
                    ->query(fn ($query, $data) => $query
                        ->when($data['birthday_from'], fn ($q, $date) => $q->where('birthday', '>=', $date))
                        ->when($data['birthday_to'], fn ($q, $date) => $q->where('birthday', '<=', $date))),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Прикріпити персону')
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

                        TextInput::make('character_name')
                            ->label('Ім\'я персонажа')
                            ->required()
                            ->maxLength(128),
                    ])
                    ->action(function (array $data) {
                        if ($this->ownerRecord->persons()->where('people.id', $data['person_id'])->exists()) {
                            Notification::make()
                                ->title('Помилка')
                                ->body('Ця особа вже додана до фільму')
                                ->danger()
                                ->send();

                            return;
                        }

                        $this->ownerRecord->persons()->attach($data['person_id'], [
                            'character_name' => $data['character_name'],
                        ]);

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
