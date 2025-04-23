<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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

    protected static ?string $modelLabel = 'скаргу на коментар';

    protected static ?string $pluralModelLabel = 'Скарги на коментарі';

    protected static ?string $navigationGroup = 'Коментарі';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) CommentReport::where('is_viewed', false)->count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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

                ToggleColumn::make('is_viewed')
                    ->label('Переглянуто')
                    ->sortable()
                    ->toggleable()
                    ->tooltip('Натисніть щоб змінити статус'),

                TextColumn::make('body')
                    ->label('Текст скарги')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->body)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('type')
                    ->label('Тип скарги')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->options(CommentReportType::class)
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Інформація про скаргу')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Select::make('comment_id')
                            ->label('Коментар')
                            ->relationship('comment', 'body')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),

                        Select::make('type')
                            ->label('Тип скарги')
                            ->options(CommentReportType::class)
                            ->required()
                            ->enum(CommentReportType::class),

                        Select::make('user_id')
                            ->label('Користувач')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),

                        Toggle::make('is_viewed')
                            ->label('Переглянуто')
                            ->default(false)
                            ->visible(fn (string $operation): bool => $operation === 'edit'),

                        DateTimePicker::make('created_at')
                            ->label('Дата створення')
                            ->prefixIcon('heroicon-o-calendar')
                            ->displayFormat('d.m.Y H:i')
                            ->disabled()
                            ->default(now())
                            ->hiddenOn('create'),

                        DateTimePicker::make('updated_at')
                            ->label('Дата оновлення')
                            ->prefixIcon('heroicon-o-clock')
                            ->displayFormat('d.m.Y H:i')
                            ->disabled()
                            ->default(now())
                            ->hiddenOn('create'),

                        RichEditor::make('body')
                            ->label('Текст скарги')
                            ->nullable()
                            ->disableToolbarButtons(['attachFiles'])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommentReports::route('/'),
            'view' => Pages\ViewCommentReport::route('/{record}'),
            'create' => Pages\CreateCommentReport::route('/create'),
            'edit' => Pages\EditCommentReport::route('/{record}/edit'),
        ];
    }
}
