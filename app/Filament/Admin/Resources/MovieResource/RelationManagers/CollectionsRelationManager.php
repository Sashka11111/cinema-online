<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CollectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'collections';

    protected static ?string $title = 'Підбірки';

    protected static ?string $modelLabel = 'підбірку';

    protected static ?string $pluralModelLabel = 'підбірки';

    public function form(Form $form): Form
    {
        return $form->schema([

        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
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
