<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RatingsRelationManager extends RelationManager
{
    protected static string $relationship = 'ratings';

    protected static ?string $title = 'Оцінки';

    protected static ?string $modelLabel = 'оцінку';

    protected static ?string $pluralModelLabel = 'оцінки';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('movie.name')
                    ->label('Фільм')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('number')
                    ->label('Оцінка')
                    ->sortable(),

                TextColumn::make('review')
                    ->label('Відгук')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->review)
                    ->html(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('movie')
                    ->label('Фільм')
                    ->relationship('movie', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('movie_id')
                    ->label('Фільм')
                    ->relationship('movie', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->prefixIcon('heroicon-o-film'),

                Select::make('number')
                    ->label('Оцінка')
                    ->options(range(1, 10))
                    ->required()
                    ->prefixIcon('heroicon-o-star'),

                RichEditor::make('review')
                    ->label('Відгук')
                    ->columnSpanFull()
                    ->disableToolbarButtons(['attachFiles']),
            ]);
    }
}
