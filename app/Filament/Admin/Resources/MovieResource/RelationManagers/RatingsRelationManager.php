<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RatingsRelationManager extends RelationManager
{
    protected static string $relationship = 'ratings';

    protected static ?string $title = 'Оцінки';

    protected static ?string $modelLabel = 'оцінку';

    protected static ?string $pluralModelLabel = 'оцінки';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('score')
                    ->label('Оцінка')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->required(),

                RichEditor::make('review')
                    ->label('Відгук')
                    ->columnSpanFull()
                    ->disableToolbarButtons(['attachFiles']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable(),

                TextColumn::make('score')
                    ->label('Оцінка')
                    ->sortable(),

                TextColumn::make('review')
                    ->label('Відгук')
                    ->limit(50)
                    ->html(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}