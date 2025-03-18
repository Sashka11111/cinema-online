<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\RatingResource\Pages;
use Liamtseva\Cinema\Models\Rating;

class RatingResource extends Resource
{
    protected static ?string $model = Rating::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Рейтинги';

    protected static ?string $modelLabel = 'рейтинг';

    protected static ?string $pluralModelLabel = 'Рейтинг';

    protected static ?string $navigationGroup = 'Користувацька активність';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->description(fn (Rating $rating): string => $rating->user->email)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('movie.name')
                    ->label('Фільм')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('number')
                    ->label('Оцінка')
                    ->badge()
                    ->color(fn (Rating $rating): string => match (true) {
                        $rating->number >= 8 => 'success',
                        $rating->number >= 5 => 'warning',
                        default => 'danger',
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('review')
                    ->label('Відгук')
                    ->limit(50)
                    ->tooltip(fn (Rating $rating): ?string => $rating->review)
                    ->sortable()
                    ->searchable()
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
                SelectFilter::make('number')
                    ->label('Оцінка')
                    ->options(array_combine(range(1, 10), range(1, 10))),

                SelectFilter::make('user')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('movie')
                    ->label('Фільм')
                    ->relationship('movie', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Дата створення від')
                            ->placeholder('Виберіть дату'),
                        DatePicker::make('created_until')
                            ->label('До')
                            ->placeholder('Виберіть дату'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query) => $query->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($query) => $query->whereDate('created_at', '<=', $data['created_until']));
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
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
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

                        RichEditor::make('review')
                            ->label('Відгук')
                            ->columnSpanFull()
                            ->disableToolbarButtons(['attachFiles'])
                            ->columnSpanFull()
                            ->maxLength(65535),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRatings::route('/'),
            'create' => Pages\CreateRating::route('/create'),
            'edit' => Pages\EditRating::route('/{record}/edit'),
        ];
    }
}
