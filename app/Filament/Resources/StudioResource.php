<?php

namespace Liamtseva\Cinema\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Resources\StudioResource\Pages;
use Liamtseva\Cinema\Models\Studio;

class StudioResource extends Resource
{
    protected static ?string $model = Studio::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(128)
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $operation, string $state, Set $set, Studio $studio) {
                    if ($operation == 'edit') {
                        return;
                    }
                    $studio->slug = $state;
                    $set('slug', $studio->slug);
                }),
            TextInput::make('slug')
                ->required()
                ->maxLength(128),
            TextInput::make('description')
                ->required()
                ->maxLength(512),
            FileUpload::make('image')
                ->image(),
            TextInput::make('meta_title')
                ->maxLength(128),
            TextInput::make('meta_description')
                ->maxLength(376),
            FileUpload::make('meta_image')
                ->image(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')
                ->label('ID')
                ->searchable(),
            TextColumn::make('slug')
                ->searchable(),
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('description')
                ->searchable(),
            ImageColumn::make('image'),
            TextColumn::make('meta_title')
                ->searchable(),
            TextColumn::make('meta_description')
                ->searchable(),
            ImageColumn::make('meta_image'),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudios::route('/'),
            'create' => Pages\CreateStudio::route('/create'),
            'edit' => Pages\EditStudio::route('/{record}/edit'),
        ];
    }
}
