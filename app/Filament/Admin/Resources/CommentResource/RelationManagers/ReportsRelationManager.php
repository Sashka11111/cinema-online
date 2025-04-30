<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\RelationManagers;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\CommentReportType;

class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';

    protected static ?string $title = 'Скарги';

    protected static ?string $modelLabel = 'скаргу';

    protected static ?string $pluralModelLabel = 'скарги';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('body')
                    ->label('Текст скарги')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->body)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('type')
                    ->label('Тип')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('is_viewed')
                    ->label('Переглянуто')
                    ->sortable()
                    ->toggleable()
                    ->tooltip('Натисніть щоб змінити статус'),

                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
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
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Користувач'),

                Select::make('type')
                    ->label('Тип скарги')
                    ->options(CommentReportType::class)
                    ->required()
                    ->enum(CommentReportType::class),

                RichEditor::make('body')
                    ->label('Текст скарги')
                    ->nullable()
                    ->disableToolbarButtons(['attachFiles'])
                    ->columnSpanFull(),
            ]);
    }
}
