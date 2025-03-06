<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Pages;
use Liamtseva\Cinema\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Користувачі';

    protected static ?string $modelLabel = 'користувача';

    protected static ?string $pluralModelLabel = 'Користувачі';

    protected static ?string $navigationGroup = 'Користувацька активність';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return
            $table->columns([
                ImageColumn::make('avatar')
                    ->label('Аватар')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Ім\'я та пошта')
                    ->description(fn (User $user): string => $user->email)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Роль')
                    ->formatStateUsing(fn (Role $state) => Role::getLabels()[$state->value])
                    ->badge()
                    ->color(fn (User $user): string => match ($user->role) {
                        Role::USER => 'success',       // Звичайний користувач - зелений
                        Role::MODERATOR => 'primary',  // Модератор - синій
                        Role::ADMIN => 'danger',       // Адмін - червоний
                    })
                    ->icon(fn (User $user): string => match ($user->role) {
                        Role::USER => 'heroicon-o-user',
                        Role::MODERATOR => 'tabler-user-cog',
                        Role::ADMIN => 'ri-admin-line',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('gender')
                    ->label('Стать')
                    ->formatStateUsing(fn (Gender $state) => Gender::getLabels()[$state->value])
                    ->badge()
                    ->color(fn (User $user): string => match ($user->gender) {
                        Gender::MALE => 'info',       // Чоловіки - синій
                        Gender::FEMALE => 'warning',     // Жінки - рожевий
                        Gender::OTHER => 'gray',      // Інші - сірий
                    })
                    ->icon(fn (User $user): string => match ($user->gender) {
                        Gender::MALE => 'fas-male',  // Іконка для чоловіків
                        Gender::FEMALE => 'fas-female',       // Іконка для жінок
                        Gender::OTHER => 'bx-male-female', // Іконка для інших
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('birthday')
                    ->label('Дата народження')
                    ->sortable()
                    ->searchable()
                    ->date('d-m-Y')
                    ->toggleable(),

                TextColumn::make('last_seen_at')
                    ->label('Востаннє бачили')
                    ->badge()
                    ->color(fn (User $user): string => match (true) {
                        ! $user->last_seen_at => 'gray', // Якщо не заходив
                        now()->diffInHours(Carbon::parse($user->last_seen_at)) < 1 => 'success',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (User $user) => $user->last_seen_at
                        ? Carbon::parse($user->last_seen_at)->diffForHumans()
                        : 'Ніколи')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
                ->filters([
                    SelectFilter::make('role')
                        ->label('Роль')
                        ->options(Role::getLabels()),

                    SelectFilter::make('gender')
                        ->label('Стать')
                        ->options(Gender::getLabels()),
                    Filter::make('birthday')
                        ->form([
                            DatePicker::make('birthday_from')->label('Дата народження від')->prefixIcon('clarity-date-line')->native(false),
                            DatePicker::make('birthday_to')->label('Дата народження до')->prefixIcon('clarity-date-line')->native(false),
                        ])
                        ->query(fn ($query, $data) => $query
                            ->when($data['birthday_from'], fn ($query, $date) => $query->where('birthday', '>=', $date))
                            ->when($data['birthday_to'], fn ($query, $date) => $query->where('birthday', '<=', $date))),
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
                        ->label('Логін')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('clarity-text-line')
                        ->unique(ignoreRecord: true),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('clarity-email-outline-badged')
                        ->unique(ignoreRecord: true),

                    DatePicker::make('birthday')
                        ->label('Дата народження')
                        ->prefixIcon('clarity-date-line')
                        ->native(false)
                        ->before(now())
                        ->nullable(),

                    Select::make('gender')
                        ->label('Стать')
                        ->options(Gender::getLabels())
                        ->prefixIcon('bx-male-female')
                        ->nullable(),

                    DateTimePicker::make('created_at')
                        ->label('Дата створення')
                        ->displayFormat('d.m.Y H:i')
                        ->prefixIcon('clarity-date-line')
                        ->disabled(),

                    DateTimePicker::make('updated_at')
                        ->label('Дата оновлення')
                        ->displayFormat('d.m.Y H:i')
                        ->prefixIcon('clarity-date-line')
                        ->disabled(),

                    DateTimePicker::make('last_seen_at')
                        ->label('Остання активність')
                        ->displayFormat('d.m.Y H:i')
                        ->prefixIcon('clarity-date-line')
                        ->disabled(),

                    DateTimePicker::make('email_verified_at')
                        ->label('Дата підтвердження email')
                        ->displayFormat('d.m.Y H:i')
                        ->prefixIcon('clarity-date-line')
                        ->disabled(),
                ])
                ->columns(2),

            Section::make('Безпека')
                ->icon('heroicon-o-lock-closed')
                ->schema([
                    TextInput::make('password')
                        ->label('Пароль')
                        ->password()
                        ->maxLength(255)
                        ->dehydrated(fn ($state) => filled($state))
                        ->prefixIcon('carbon-password')
                        ->nullable(),
                    Select::make('role')
                        ->label('Роль')
                        ->options(Role::getLabels())
                        ->prefixIcon('bx-user')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Налаштування акаунту')
                ->icon('clarity-settings-line')
                ->schema([
                    Toggle::make('allow_adult')
                        ->label('Доступ до контенту для дорослих')
                        ->default(false),

                    Toggle::make('is_auto_next')
                        ->label('Автоперехід до наступного епізоду')
                        ->default(false),

                    Toggle::make('is_auto_play')
                        ->label('Автовідтворення')
                        ->default(false),

                    Toggle::make('is_auto_skip_intro')
                        ->label('Автопропуск вступу')
                        ->default(false),

                    Toggle::make('is_private_favorites')
                        ->label('Приватність улюблених фільмів')
                        ->default(false),
                ])
                ->columns(2),

            Section::make('Додатково')
                ->icon('heroicon-o-sparkles')
                ->schema([
                    FileUpload::make('avatar')
                        ->label('Аватар')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('avatars')
                        ->nullable(),

                    FileUpload::make('backdrop')
                        ->label('Фонове зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(4096)
                        ->directory('backdrops')
                        ->nullable(),

                    Textarea::make('description')
                        ->label('Опис профілю')
                        ->columnSpanFull()
                        ->rows(4)
                        ->nullable()
                        ->maxLength(500),
                ])
                ->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
