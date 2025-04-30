<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\RelationManagers;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $modelLabel = 'відповідь';

    protected static ?string $pluralModelLabel = 'відповіді';

    protected static ?string $title = 'Відповіді';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->description(fn ($record) => ($record->user->email ?? 'Не відомо'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('body')
                    ->label('Текст')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->body)
                    ->searchable(),

                ToggleColumn::make('is_spoiler')
                    ->label('Спойлер')
                    ->offIcon('heroicon-o-eye-slash')
                    ->onIcon('heroicon-o-eye')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_spoiler')
                    ->label('Спойлер')
                    ->placeholder('Всі')
                    ->trueLabel('Зі спойлерами')
                    ->falseLabel('Без спойлерів')
                    ->indicator('Спойлер'),

                SelectFilter::make('user_id')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->indicator('Користувач'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Отримуємо батьківський коментар
                        $parentComment = $this->getOwnerRecord();

                        // Додаємо дані про commentable з батьківського коментаря
                        return array_merge($data, [
                            'commentable_type' => $parentComment->commentable_type,
                            'commentable_id' => $parentComment->commentable_id,
                        ]);
                    }),
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
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Toggle::make('is_spoiler')
                    ->label('Містить спойлер')
                    ->offIcon('heroicon-o-eye-slash')
                    ->onIcon('heroicon-o-eye')
                    ->helperText('Позначте, якщо коментар містить спойлери'),

                RichEditor::make('body')
                    ->label('Текст коментаря')
                    ->required()
                    ->columnSpanFull()
                    ->disableToolbarButtons(['attachFiles']),
            ]);
    }
}
