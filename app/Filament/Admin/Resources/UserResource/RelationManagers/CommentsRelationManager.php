<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Коментарі';

    protected static ?string $modelLabel = 'коментар';

    protected static ?string $pluralModelLabel = 'коментарі';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('commentable_type')
                    ->label('Тип контенту')
                    ->options([
                        'Liamtseva\Cinema\Models\Movie' => 'Фільм',
                        'Liamtseva\Cinema\Models\Episode' => 'Епізод',
                        'Liamtseva\Cinema\Models\Selection' => 'Підбірка',
                    ])
                    ->required(),

                Select::make('commentable_id')
                    ->label('Контент')
                    ->searchable()
                    ->preload()
                    ->required(),

                Toggle::make('is_spoiler')
                    ->label('Містить спойлер')
                    ->offIcon('heroicon-o-eye-slash')
                    ->onIcon('heroicon-o-eye'),

                RichEditor::make('body')
                    ->label('Текст коментаря')
                    ->required()
                    ->columnSpanFull()
                    ->disableToolbarButtons(['attachFiles']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('commentable_type')
                    ->label('Тип')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'Liamtseva\Cinema\Models\Movie' => 'Фільм',
                        'Liamtseva\Cinema\Models\Episode' => 'Епізод',
                        'Liamtseva\Cinema\Models\Selection' => 'Підбірка',
                        default => 'Невідомий контент',
                    })
                    ->sortable(),

                TextColumn::make('body')
                    ->label('Текст')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->body)
                    ->html(),

                ToggleColumn::make('is_spoiler')
                    ->label('Спойлер')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('commentable_type')
                    ->label('Тип контенту')
                    ->options([
                        'Liamtseva\Cinema\Models\Movie' => 'Фільм',
                        'Liamtseva\Cinema\Models\Episode' => 'Епізод',
                        'Liamtseva\Cinema\Models\Selection' => 'Підбірка',
                    ]),

                TernaryFilter::make('is_spoiler')
                    ->label('Спойлер'),
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
}
