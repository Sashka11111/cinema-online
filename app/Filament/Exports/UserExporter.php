<?php

namespace Liamtseva\Cinema\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Models\User;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Ім\'я'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('role')
                ->label('Роль')
                ->formatStateUsing(fn ($state) => Role::getLabels()[$state] ?? $state),
            ExportColumn::make('gender')
                ->label('Стать')
                ->formatStateUsing(fn ($state) => Gender::getLabels()[$state] ?? $state),
            ExportColumn::make('birthday')
                ->label('Дата народження')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d-m-Y') : null),
            ExportColumn::make('last_seen_at')
                ->label('Остання активність')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d-m-Y H:i') : 'Ніколи'),
            ExportColumn::make('email_verified_at')
                ->label('Дата підтвердження email')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d-m-Y H:i') : null),
            ExportColumn::make('allow_adult')
                ->label('Доступ до контенту для дорослих')
                ->formatStateUsing(fn ($state) => $state ? 'Так' : 'Ні'),
            ExportColumn::make('is_auto_next')
                ->label('Автоперехід до наступного епізоду')
                ->formatStateUsing(fn ($state) => $state ? 'Так' : 'Ні'),
            ExportColumn::make('is_auto_play')
                ->label('Автовідтворення')
                ->formatStateUsing(fn ($state) => $state ? 'Так' : 'Ні'),
            ExportColumn::make('is_auto_skip_intro')
                ->label('Автопропуск вступу')
                ->formatStateUsing(fn ($state) => $state ? 'Так' : 'Ні'),
            ExportColumn::make('is_private_favorites')
                ->label('Приватність улюблених фільмів')
                ->formatStateUsing(fn ($state) => $state ? 'Так' : 'Ні'),
            ExportColumn::make('avatar')
                ->label('Аватар')
                ->formatStateUsing(fn ($state) => $state ? asset('storage/'.$state) : null),
            ExportColumn::make('backdrop')
                ->label('Фонове зображення')
                ->formatStateUsing(fn ($state) => $state ? asset('storage/'.$state) : null),
            ExportColumn::make('description')
                ->label('Опис профілю'),
            ExportColumn::make('created_at')
                ->label('Дата створення'),
            ExportColumn::make('updated_at')
                ->label('Дата оновлення'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Експорт користувачів завершено, і '.number_format($export->successful_rows).' '.str('рядок')->plural($export->successful_rows).' експортовано.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('рядок')->plural($failedRowsCount).' не вдалося експортувати.';
        }

        return $body;
    }
}
