<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Models\Room;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class RoomsPage extends Component
{
    use WithPagination;

    // Додаємо властивість page для пагінації
    #[Url]
    public $page = 1;

    public $selectedRoom = null;
    public $password = '';
    public $showPasswordModal = false;

    #[Url(except: 'all')]
    public $filterType = 'all'; // all, public, private, my

    public function mount()
    {
        if (!Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function joinRoom($roomId)
    {
        $room = Room::find($roomId);

        if (!$room || !$room->isActive()) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Кімната не знайдена або вже завершена'
            ]);
            return;
        }

        if ($room->isFull() && $room->user_id !== Auth::id()) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Кімната заповнена'
            ]);
            return;
        }

        if ($room->is_private && $room->user_id !== Auth::id()) {
            $this->selectedRoom = $roomId;
            $this->showPasswordModal = true;
            return;
        }

        $this->enterRoom($room);
    }

    public function joinWithPassword()
    {
        if (!$this->selectedRoom || !$this->password) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Введіть пароль'
            ]);
            return;
        }

        $room = Room::find($this->selectedRoom);

        if (!$room) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Кімната не знайдена'
            ]);
            $this->closePasswordModal();
            return;
        }

        if (!password_verify($this->password, $room->password)) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Невірний пароль'
            ]);
            return;
        }

        $this->closePasswordModal();
        $this->enterRoom($room);
    }

    private function enterRoom($room)
    {
        // Додаємо користувача до кімнати
        $room->viewers()->syncWithoutDetaching([
            Auth::id() => ['joined_at' => now()],
        ]);

        // Перенаправляємо до кімнати
        return $this->redirectRoute('movies.watch.room', [
            'movie' => $room->episode->movie->slug,
            'episodeNumber' => $room->episode->number,
            'room' => $room->slug,
        ], navigate: true);
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->selectedRoom = null;
        $this->password = '';
    }

    public function render()
    {
        $query = Room::with(['user', 'episode.movie', 'episode.movie.tags', 'activeViewers'])
            ->where('room_status', RoomStatus::ACTIVE);

        // Фільтрація за типом
        switch ($this->filterType) {
            case 'public':
                $query->where('is_private', false);
                break;
            case 'private':
                $query->where('is_private', true)
                      ->where('user_id', Auth::id());
                break;
            case 'my':
                $query->where('user_id', Auth::id());
                break;
            case 'all':
            default:
                $query->where(function($q) {
                    $q->where('is_private', false)
                      ->orWhere('user_id', Auth::id());
                });
                break;
        }


        $rooms = $query->orderBy('created_at', 'desc')
                      ->paginate(12, ['*'], 'page', $this->page);

        return view('livewire.pages.rooms-page', [
            'rooms' => $rooms,
        ]);
    }

    public function resetPage($pageName = 'page')
    {
        $this->page = 1;
    }

    // Метод для переходу на конкретну сторінку
    public function gotoPage($page, $pageName = 'page')
    {
        $this->page = $page;
    }
}
