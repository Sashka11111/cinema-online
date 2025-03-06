<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Pages;
use Liamtseva\Cinema\Models\Studio;

class StudioResource extends Resource
{
    protected static ?string $model = Studio::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Студії';

    protected static ?string $modelLabel = 'студію';

    protected static ?string $pluralModelLabel = 'Студії';
    protected static ?string $navigationGroup = 'Персони та студії';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $search = request()->input('tableSearch');
                if ($search) {
                    $query
                        ->select('*')
                        ->addSelect(DB::raw("ts_rank(searchable, websearch_to_tsquery('ukrainian', ?)) AS rank"))
                        ->addSelect(DB::raw("ts_headline('ukrainian', name, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS name_highlight"))
                        ->addSelect(DB::raw("ts_headline('ukrainian', description, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS description_highlight"))
                        ->addSelect(DB::raw('similarity(name, ?) AS similarity'))
                        ->whereRaw("searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search, $search, $search, $search, $search])
                        ->orWhereRaw('name % ?', [$search])
                        ->orderByDesc('rank')
                        ->orderByDesc('similarity');
                }

                return $query;
            })
            ->columns([
                ImageColumn::make('image')
                    ->label('Зображення')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->description(fn(Studio $studio): string => $studio->slug)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('name')
                    ->form([
                        TextInput::make('name')->label('Пошук за назвою')->prefixIcon('heroicon-o-information-circle'),
                    ])
                    ->query(fn($query, $data) => $query->when(
                        $data['name'],
                        fn($query) => $query->where('name', 'ilike', '%' . $data['name'] . '%')
                    )),

                Filter::make('created_at')
                    ->label('Дата створення')
                    ->form([
                        DatePicker::make('created_at')
                            ->label('Виберіть дату створення')
                            ->prefixIcon('heroicon-o-calendar')
                    ])
                    ->query(function ($query, $data) {
                        return $query
                            ->when($data['created_at'], fn($query, $date) => $query->whereDate('created_at', '=', Carbon::createFromFormat('Y-m-d', $date)->startOfDay()));
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
        return $form->schema([
            Section::make('Основна інформація')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->maxLength(128)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('slug', str()->slug($state));
                        })
                        ->prefixIcon('clarity-text-line'),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(128)
                        ->unique(ignoreRecord: true)
                        ->prefixIcon('heroicon-o-link'),

                    Textarea::make('description')
                        ->label('Опис')
                        ->required()
                        ->maxLength(512)
                        ->rows(4)
                        ->columnSpanFull(),

                    DateTimePicker::make('created_at')
                        ->label('Дата створення')
                        ->prefixIcon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y H:i')
                        ->disabled()
                        ->default(now()),

                    DateTimePicker::make('updated_at')
                        ->label('Дата оновлення')
                        ->prefixIcon('heroicon-o-clock')
                        ->displayFormat('d.m.Y H:i')
                        ->disabled()
                        ->default(now()),
                ])
                ->columns(2),

            Section::make('Медіа')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('image')
                        ->label('Головне зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('studios')
                        ->nullable(),
                ]),

            Section::make('SEO')
                ->icon('heroicon-o-globe-alt')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta назва')
                        ->maxLength(128)
                        ->nullable()
                        ->prefixIcon('heroicon-o-tag'),

                    FileUpload::make('meta_image')
                        ->label('Meta зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('studios/meta')
                        ->nullable(),

                    Textarea::make('meta_description')
                        ->label('Meta опис')
                        ->maxLength(376)
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(true)
                ->columns(2),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            // Додайте зв'язки якщо потрібно, наприклад:
            // RelationManagers\MoviesRelationManager::class,
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
