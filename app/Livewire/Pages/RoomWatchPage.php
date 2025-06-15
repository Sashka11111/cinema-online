<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Liamtseva\Cinema\Events\VideoSyncEvent;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Room;
use Livewire\Component;

class RoomWatchPage extends Component
{
    public Movie $movie;

    public ?Episode $episode = null;

    public ?Room $room = null;

    public bool $showInviteModal = false;

    public string $inviteLink = '';

    public string $roomPassword = '';

    public bool $showPasswordModal = false;

    public string $passwordInput = '';

    public ?string $pendingRoomSlug = null;

    public function mount(Movie $movie, ?int $episodeNumber = null, ?Room $room = null)
    {
        $this->movie = $movie;
        $this->room = $room;

        // Обов'язкова перевірка авторизації для доступу до кімнат
        if (! Auth::check()) {
            session()->flash('error', 'Для перегляду в кімнаті необхідно авторизуватися');
            return $this->redirectRoute('login', navigate: true);
        }

        if (! $movie->is_published && ! Auth::user()->isAdmin()) {
            abort(404);
        }

        $this->movie->load(['episodes', 'tags']);

        $this->episode = $episodeNumber
            ? Episode::where('movie_id', $movie->id)->where('number', $episodeNumber)->first()
            : Episode::where('movie_id', $movie->id)->orderBy('number')->first();

        if (! $this->episode) {
            abort(404, 'Епізод не знайдено');
        }

        if ($this->room) {
            $this->room->load(['user', 'activeViewers']);

            if (! $this->room->isActive()) {
                session()->flash('error', 'Кімната не знайдена або вже завершена');
                return $this->redirectRoute('movies.watch.episode', [
                    'movie' => $this->movie->slug,
                    'episodeNumber' => $this->episode->number,
                ], navigate: true);
            }

            // Перевірка на приватну кімнату
            if ($this->room->is_private && $this->room->user_id !== Auth::id()) {
                // Якщо користувач не є власником кімнати і кімната приватна
                if (!session()->has("room_access_{$this->room->slug}")) {
                    // Показуємо модальне вікно для введення пароля
                    $this->pendingRoomSlug = $this->room->slug;
                    $this->showPasswordModal = true;
                    return;
                }
            }

            if (Auth::check()) {
                if ($this->room->isFull() && $this->room->user_id !== Auth::id()) {
                    session()->flash('error', 'Кімната заповнена');
                    return $this->redirectRoute('movies.watch.episode', [
                        'movie' => $this->movie->slug,
                        'episodeNumber' => $this->episode->number,
                    ], navigate: true);
                }

                $this->room->viewers()->syncWithoutDetaching([
                    Auth::id() => ['joined_at' => now()],
                ]);
            }
        }
    }

    public function joinRoom($roomId, $password = null)
    {
        if (! Auth::check()) {
            session()->flash('error', 'Для входу в кімнату необхідно авторизуватися');
            return;
        }

        $room = Room::find($roomId);

        if (! $room || ! $room->isActive()) {
            session()->flash('error', 'Кімната не знайдена або вже завершена');
            return;
        }

        if ($room->isFull() && $room->user_id !== Auth::id()) {
            session()->flash('error', 'Кімната заповнена');
            return;
        }

        if ($room->is_private && $room->user_id !== Auth::id()) {
            if (!password_verify($password, $room->password)) {
                session()->flash('error', 'Невірний пароль');
                return;
            }

            // Зберігаємо доступ до кімнати в сесії
            session()->put("room_access_{$room->slug}", true);
        }

        $room->viewers()->syncWithoutDetaching([
            Auth::id() => ['joined_at' => now()],
        ]);

        return $this->redirectRoute('movies.watch.room', [
            'movie' => $this->movie->slug,
            'episodeNumber' => $this->episode->number,
            'room' => $room->slug,
        ], navigate: true);
    }

    public function leaveRoom()
    {
        if (! $this->room || ! Auth::check()) {
            return;
        }

        $this->room->viewers()->updateExistingPivot(Auth::id(), ['left_at' => now()]);

        if ($this->room->user_id === Auth::id()) {
            $this->room->end();
        }

        return $this->redirectRoute('movies.watch.episode', [
            'movie' => $this->movie->slug,
            'episodeNumber' => $this->episode->number,
        ], navigate: true);
    }

    public function showInviteModal()
    {
        if (!$this->room) {
            return;
        }

        $this->showInviteModal = true;

        // Генеруємо посилання для запрошення
        $this->inviteLink = route('movies.watch.room', [
            'movie' => $this->movie->slug,
            'episodeNumber' => $this->episode->number,
            'room' => $this->room->slug,
        ]);

        // Якщо кімната приватна, отримуємо пароль з сесії
        if ($this->room->is_private) {
            $this->roomPassword = session()->get("room_password_{$this->room->slug}", 'Пароль встановлений власником кімнати');
        }
    }

    public function closeInviteModal()
    {
        $this->showInviteModal = false;
        $this->inviteLink = '';
        $this->roomPassword = '';
    }

    public function submitPassword()
    {
        if (!$this->passwordInput) {
            session()->flash('error', 'Введіть пароль');
            return;
        }

        if (!$this->pendingRoomSlug) {
            session()->flash('error', 'Помилка: кімната не знайдена');
            $this->showPasswordModal = false;
            $this->passwordInput = '';
            $this->pendingRoomSlug = null;
            return;
        }

        $room = Room::where('slug', $this->pendingRoomSlug)->first();

        if (!$room || !$room->isActive()) {
            session()->flash('error', 'Кімната не знайдена або вже завершена');
            $this->showPasswordModal = false;
            $this->passwordInput = '';
            $this->pendingRoomSlug = null;
            return;
        }

        if (!password_verify($this->passwordInput, $room->password)) {
            session()->flash('error', 'Невірний пароль');
            $this->passwordInput = ''; // Очищуємо поле пароля
            return;
        }

        // Зберігаємо доступ до кімнати в сесії
        session()->put("room_access_{$room->slug}", true);

        // Додаємо користувача до кімнати
        if ($room->isFull() && $room->user_id !== Auth::id()) {
            session()->flash('error', 'Кімната заповнена');
            $this->showPasswordModal = false;
            $this->passwordInput = '';
            $this->pendingRoomSlug = null;
            return;
        }

        $room->viewers()->syncWithoutDetaching([
            Auth::id() => ['joined_at' => now()],
        ]);

        // Закриваємо модальне вікно після успішного входу
        $this->showPasswordModal = false;
        $this->passwordInput = '';
        $this->pendingRoomSlug = null;

        // Перезавантажуємо сторінку для відображення кімнати
        return $this->redirectRoute('movies.watch.room', [
            'movie' => $this->movie->slug,
            'episodeNumber' => $this->episode->number,
            'room' => $room->slug,
        ], navigate: true);
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->passwordInput = '';
        $this->pendingRoomSlug = null;
    }

    #[\Livewire\Attributes\On('sync-video')]
    public function syncVideo($action, $data = null)
    {
        if (! $this->room) {
            \Log::warning('Sync video called without room', ['action' => $action]);
            return;
        }

        if (! Auth::check()) {
            \Log::warning('Sync video called without authentication', ['room' => $this->room->slug]);
            return;
        }

        // Verify user is still in the room
        if (! $this->room->viewers()->wherePivot('user_id', Auth::id())->wherePivot('left_at', null)->exists()) {
            \Log::warning('Sync video called by user not in room', [
                'user' => Auth::id(),
                'room' => $this->room->slug
            ]);
            return;
        }

        try {
            \Log::info('Broadcasting video sync event', [
                'room' => $this->room->slug,
                'action' => $action,
                'user' => Auth::id()
            ]);

            broadcast(new VideoSyncEvent($this->room->slug, $action, $data))->toOthers();
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast video sync event', [
                'room' => $this->room->slug,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pages.room-watch', [
            'movie' => $this->movie,
            'episode' => $this->episode,
            'room' => $this->room,
        ]);
    }
}
