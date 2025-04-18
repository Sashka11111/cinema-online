<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\UserResource;

class ViewUser extends ViewRecord
{
    protected static ?string $title = 'Перегляд користувача';

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Ім\'я'),

                        TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope'),

                        TextEntry::make('email_verified_at')
                            ->label('Дата підтвердження Email')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-check-circle'),

                        TextEntry::make('last_seen_at')
                            ->label('Остання активність')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('created_at')
                            ->label('Дата реєстрації')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('updated_at')
                            ->label('Дата оновлення')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('role')
                            ->label('Роль')
                            ->badge()
                            ->icon('heroicon-o-user-group'),

                        TextEntry::make('gender')
                            ->label('Стать')
                            ->badge()
                            ->icon('heroicon-o-user-group'),

                        TextEntry::make('birthday')
                            ->label('Дата народження')
                            ->date('d.m.Y')
                            ->icon('heroicon-o-calendar'),

                        IconEntry::make('allow_adult')
                            ->label('Дорослий контент')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle'),

                        IconEntry::make('is_auto_next')
                            ->label('Автоматичний перехід до наступного')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle'),

                        IconEntry::make('is_auto_play')
                            ->label('Автовідтворення')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle'),

                        IconEntry::make('is_auto_skip_intro')
                            ->label('Пропуск вступу')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle'),

                        IconEntry::make('is_private_favorites')
                            ->label('Приватність улюблених фільмів')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle'),
                    ])
                    ->columns(2),

                Section::make('Додатково')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        ImageEntry::make('avatar')
                            ->label('Аватар')
                            ->disk('public'),

                        ImageEntry::make('backdrop')
                            ->label('Фонове зображення')
                            ->disk('public'),

                        TextEntry::make('description')
                            ->label('Опис')
                            ->html()
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                Section::make('Статистика активності користувача')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        TextEntry::make('comments_count')
                            ->label('Кількість коментарів')
                            ->getStateUsing(fn ($record) => $record->comments()->count())
                            ->icon('heroicon-o-chat-bubble-left-ellipsis'),

                        TextEntry::make('ratings_count')
                            ->label('Кількість оцінок')
                            ->getStateUsing(fn ($record) => $record->ratings()->count())
                            ->icon('heroicon-o-star'),

                        TextEntry::make('selections_count')
                            ->label('Кількість підбірок')
                            ->getStateUsing(fn ($record) => $record->selections()->count())
                            ->icon('heroicon-o-rectangle-stack'),

                        TextEntry::make('watch_histories_count')
                            ->label('Кількість переглядів')
                            ->getStateUsing(fn ($record) => $record->watchHistories()->count())
                            ->icon('heroicon-o-eye'),

                        TextEntry::make('search_histories_count')
                            ->label('Кількість пошуків')
                            ->getStateUsing(fn ($record) => $record->searchHistories()->count())
                            ->icon('heroicon-o-magnifying-glass'),

                        TextEntry::make('movie_notifications_count')
                            ->label('Кількість сповіщень')
                            ->getStateUsing(fn ($record) => $record->movieNotifications()->count())
                            ->icon('heroicon-o-bell'),

                        TextEntry::make('comment_likes_count')
                            ->label('Кількість лайків')
                            ->getStateUsing(fn ($record) => $record->commentLikes()->count())
                            ->icon('heroicon-o-heart'),

                        TextEntry::make('comment_reports_count')
                            ->label('Кількість скарг')
                            ->getStateUsing(fn ($record) => $record->commentReports()->count())
                            ->icon('heroicon-o-flag'),

                        TextEntry::make('user_lists_count')
                            ->label('Кількість списків')
                            ->getStateUsing(fn ($record) => $record->userLists()->count())
                            ->icon('heroicon-o-queue-list'),
                    ])
                    ->columns(3),
            ]);
    }
}
