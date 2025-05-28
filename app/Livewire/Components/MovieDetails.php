<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\UserList;
use Livewire\Component;

class MovieDetails extends Component
{
    public Movie $movie;

    public ?string $currentListType = null;

    public string $notificationMessage = '';

    public string $notificationType = 'success';

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
        $this->checkUserList();
    }

    public function checkUserList()
    {
        if (Auth::check()) {
            // Отримуємо поточний список користувача для цього фільму (може бути тільки один)
            $currentList = UserList::where('user_id', Auth::id())
                ->where('listable_id', $this->movie->id)
                ->where('listable_type', Movie::class)
                ->first();

            $this->currentListType = $currentList ? $currentList->type->value : null;
        }
    }

    public function addToList($listType)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Перетворюємо рядковий тип списку в enum
        $listTypeEnum = match ($listType) {
            'favorite' => UserListType::FAVORITE,
            'watching' => UserListType::WATCHING,
            'planned' => UserListType::PLANNED,
            'watched' => UserListType::WATCHED,
            'not_watching' => UserListType::NOT_WATCHING,
            'stopped' => UserListType::STOPPED,
            'rewatching' => UserListType::REWATCHING,
            default => null
        };

        if (! $listTypeEnum) {
            $this->showNotification('error', 'Невідомий тип списку');

            return;
        }

        try {
            // Використовуємо транзакцію для атомарності операції
            DB::transaction(function () use ($listTypeEnum) {
                // Перевіряємо, чи фільм вже є в цьому списку
                if ($this->currentListType === $listTypeEnum->value) {
                    $this->showNotification('info', 'Вже в списку "'.$this->getListTypeName($listTypeEnum).'"');
                    return;
                }

                $oldTypeName = null;
                if ($this->currentListType) {
                    // Якщо фільм є в іншому списку - запам'ятовуємо назву для повідомлення
                    $oldTypeName = $this->getListTypeName(UserListType::from($this->currentListType));
                }

                // Використовуємо updateOrCreate для безпечного створення/оновлення запису
                // Це автоматично обробить унікальне обмеження
                UserList::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'listable_id' => $this->movie->id,
                        'listable_type' => Movie::class,
                    ],
                    [
                        'type' => $listTypeEnum->value,
                    ]
                );

                // Оновлюємо локальний стан
                $this->currentListType = $listTypeEnum->value;

                // Показуємо відповідне повідомлення
                if ($oldTypeName) {
                    $this->showNotification('success', 'Фільм переміщено зі списку "'.$oldTypeName.'" до "'.$this->getListTypeName($listTypeEnum).'"');
                } else {
                    $this->showNotification('success', 'Фільм додано до списку "'.$this->getListTypeName($listTypeEnum).'"');
                }
            });

            // Оновлюємо лічильники у батьківському компоненті
            $this->dispatch('user-list-updated');

        } catch (\Illuminate\Database\QueryException $e) {
            // Обробляємо помилки бази даних
            if (str_contains($e->getMessage(), 'unique violation') || str_contains($e->getMessage(), 'Unique violation')) {
                $this->showNotification('info', 'Вже в списку "'.$this->getListTypeName($listTypeEnum).'"');
                // Оновлюємо стан компонента
                $this->checkUserList();
            } else {
                Log::error('Помилка бази даних при додаванні до списку: '.$e->getMessage(), [
                    'user_id' => Auth::id(),
                    'movie_id' => $this->movie->id,
                    'list_type' => $listType,
                ]);
                $this->showNotification('error', 'Не вдалося оновити список. Спробуйте ще раз.');
            }
        } catch (\Exception $e) {
            Log::error('Загальна помилка при додаванні до списку: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'movie_id' => $this->movie->id,
                'list_type' => $listType,
            ]);

            $this->showNotification('error', 'Сталася помилка. Спробуйте ще раз.');
        }
    }

    private function showNotification($type, $message)
    {
        // Екрануємо повідомлення для безпеки
        $escapedMessage = addslashes($message);

        // Відправляємо подію для Alpine.js через window event
        $this->dispatch('notify', type: $type, message: $message);

        // Також використовуємо JS для прямого виклику
        $this->js("
            console.log('Sending notification:', '{$type}', '{$escapedMessage}');
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    type: '{$type}',
                    message: '{$escapedMessage}'
                }
            }));
        ");

        // Зберігаємо повідомлення для відображення в компоненті
        $this->notificationType = $type;
        $this->notificationMessage = $message;
    }

    private function getListTypeName(UserListType $type): string
    {
        return $type->getLabel() ?? 'Невідомий список';
    }

    public function render()
    {
        return view('livewire.components.movie-details');
    }
}
