<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Models\Person;

class PersonsRelationManager extends RelationManager
{
    protected static string $relationship = 'persons';

    protected static ?string $title = 'Особи';

    protected static ?string $modelLabel = 'особу';

    protected static ?string $pluralModelLabel = 'особи';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('character_name')
                    ->label('Ім\'я персонажа')
                    ->required()
                    ->maxLength(128),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Фото')
                    ->circular(),

                TextColumn::make('name')
                    ->label('Ім\'я')
                    ->searchable(),

                TextColumn::make('character_name')
                    ->label('Персонаж')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelect(fn (Select $select) => $select->placeholder('Оберіть особу'))
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('character_name')
                            ->label('Ім\'я персонажа')
                            ->required()
                            ->maxLength(128),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}