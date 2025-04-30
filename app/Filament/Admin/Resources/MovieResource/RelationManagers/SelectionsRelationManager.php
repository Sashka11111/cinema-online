<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Models\Selection;

class SelectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'selections';

    protected static ?string $title = 'Підбірки';

    protected static ?string $modelLabel = 'підбірку';

    protected static ?string $pluralModelLabel = 'підбірки';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->slug)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Автор')
                    ->searchable()
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
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Прикріпити до підбірки')
                    ->form(fn () => [
                        Select::make('selection_id')
                            ->label('Підбірка')
                            ->options(
                                Selection::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        if ($this->ownerRecord->selections()->where('selections.id', $data['selection_id'])->exists()) {
                            Notification::make()
                                ->title('Помилка')
                                ->body('Цей фільм вже доданий до підбірки')
                                ->danger()
                                ->send();

                            return;
                        }

                        $this->ownerRecord->selections()->attach($data['selection_id']);
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Видалити вибрані з підбірки'),
                ]),
            ]);
    }
}
