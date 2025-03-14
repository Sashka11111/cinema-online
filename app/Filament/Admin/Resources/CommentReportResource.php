<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\CommentReportType;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Pages;
use Liamtseva\Cinema\Models\CommentReport;

class CommentReportResource extends Resource
{
    protected static ?string $model = CommentReport::class;

    protected static ?string $navigationIcon = 'bx-comment-error';

    protected static ?string $navigationLabel = 'Скарги на коментарі';

    protected static ?string $modelLabel = 'скарга на коментар';

    protected static ?string $pluralModelLabel = 'Скарги на коментарі';

    protected static ?string $navigationGroup = 'Користувацька активність';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return (string) CommentReport::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Інформація про скаргу')
                    ->icon('heroicon-o-information-circle')
                    ->collapsed()
                    ->schema([
                        Select::make('comment_id')
                            ->label('Коментар')
                            ->relationship('comment', 'content')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn (string $operation) => $operation === 'edit'), // Не редагувати після створення

                        Select::make('user_id')
                            ->label('Користувач')
                            ->relationship('user', 'name') // Припускаю відношення до моделі User
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn (string $operation) => $operation === 'edit'), // Не редагувати після створення
                    ])
                    ->columns(2),

                Section::make('Статус обробки')
                    ->icon('heroicon-o-check-circle')
                    ->collapsed()
                    ->schema([
                        Toggle::make('is_resolved')
                            ->label('Вирішено')
                            ->default(false),

                        Textarea::make('resolution_note')
                            ->label('Примітка до вирішення')
                            ->maxLength(512)
                            ->rows(3)
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('comment.body')
                    ->label('Коментар')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->comment?->body ?? 'Коментар видалено')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_viewed')
                    ->label('Переглянуто')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('body')
                    ->label('Текст скарги')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->body)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Тип скарги')
                    ->formatStateUsing(fn (CommentReportType $state) => CommentReportType::getLabels()[$state->value])
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                TernaryFilter::make('is_viewed')
                    ->label('Переглянуто')
                    ->trueLabel('Так')
                    ->falseLabel('Ні'),

                SelectFilter::make('type')
                    ->label('Тип скарги')
                    ->options(CommentReportType::getLabels())
                    ->multiple(),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_at_from')
                            ->label('Дата створення від'),
                        DatePicker::make('created_at_to')
                            ->label('Дата створення до'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_at_from'], fn ($query, $date) => $query->where('created_at', '>=', $date))
                            ->when($data['created_at_to'], fn ($query, $date) => $query->where('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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

    public static function getRelations(): array
    {
        return [
            // Можна додати RelationManager для коментарів чи користувачів, якщо потрібно
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommentReports::route('/'),
            'create' => Pages\CreateCommentReport::route('/create'),
            'edit' => Pages\EditCommentReport::route('/{record}/edit'),
        ];
    }
}
