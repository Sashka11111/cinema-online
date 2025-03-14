<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RatingResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\RatingResource;
use Liamtseva\Cinema\Models\Rating;

class CreateRating extends CreateRecord
{
    protected static string $resource = RatingResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        // Переконайся, що user_id встановлено
        $userId = $data['user_id'] ?? auth()->id();

        $existingRating = Rating::where('user_id', $userId)
            ->where('movie_id', $data['movie_id'])
            ->first();

        if ($existingRating) {
            Notification::make()
                ->title('Помилка')
                ->body('Користувач уже залишив рейтинг для цього фільму.')
                ->danger()
                ->send();

            $this->halt(); // Зупиняємо створення
        }
    }
}
