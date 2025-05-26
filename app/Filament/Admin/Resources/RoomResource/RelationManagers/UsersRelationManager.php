<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\RelationManagers;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'viewers';

    protected static ?string $title = 'Глядачі';

    protected static ?string $modelLabel = 'глядач';

    protected static ?string $pluralModelLabel = 'глядачі';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('joined_at')
                    ->label('Час приєднання')
                    ->seconds(false)
                    ->default(now())
                    ->required(),
                DateTimePicker::make('left_at')
                    ->label('Час виходу')
                    ->seconds(false)
                    ->nullable(),
            ]);
    }

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
                    ->label('Ім\'я')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('pivot.joined_at')
                    ->label('Приєднався')
                    ->dateTime('d.m.Y H:i')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('pivot.left_at')
                    ->label('Вийшов')
                    ->dateTime('d.m.Y H:i')
                    ->toggleable()
                    ->placeholder('Активний')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('active')
                    ->label('Активні')
                    ->query(fn (Builder $query): Builder => $query->whereNull('room_user.left_at')),
                Filter::make('left')
                    ->label('Вийшли')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('room_user.left_at')),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Додати глядача')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Користувач'),
                        DateTimePicker::make('joined_at')
                            ->label('Час приєднання')
                            ->default(now())
                            ->seconds(false)
                            ->required(),
                        DateTimePicker::make('left_at')
                            ->label('Час виходу')
                            ->seconds(false)
                            ->nullable(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Редагувати')
                    ->form(fn (Tables\Actions\EditAction $action): array => [
                        DateTimePicker::make('joined_at')
                            ->label('Час приєднання')
                            ->seconds(false)
                            ->required(),
                        DateTimePicker::make('left_at')
                            ->label('Час виходу')
                            ->seconds(false)
                            ->nullable(),
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label('Видалити'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Видалити вибраних'),
                ]),
            ]);
    }
}
