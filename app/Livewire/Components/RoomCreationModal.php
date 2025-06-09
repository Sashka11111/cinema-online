<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Room;
use Livewire\Component;

class RoomCreationModal extends Component
{
    public Movie $movie;
    public Episode $episode;

    public bool $showModal = false;
    public bool $isPrivate = false;
    public ?string $roomPassword = null;
    public int $maxViewers = 10;

    public function mount(Movie $movie, Episode $episode)
    {
        $this->movie = $movie;
        $this->episode = $episode;
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['isPrivate', 'roomPassword', 'maxViewers']);
        $this->maxViewers = 10;

        // Dispatch browser event to reset form state
        $this->dispatch('resetModalForm');
    }

    public function createRoom()
    {
        if (! Auth::check()) {
            session()->flash('error', 'Для створення кімнати необхідно авторизуватися');
            return;
        }

        $this->validate([
            'maxViewers' => 'required|integer|min:1|max:10',
            'roomPassword' => $this->isPrivate ? 'required|min:4' : '',
        ]);

        $room = Room::create([
            'name' => 'Кімната '.Auth::user()->name.' - '.$this->movie->name,
            'slug' => 'room-'.Str::random(8),
            'user_id' => Auth::id(),
            'episode_id' => $this->episode->id,
            'is_private' => $this->isPrivate,
            'password' => $this->isPrivate ? bcrypt($this->roomPassword) : null,
            'max_viewers' => $this->maxViewers,
            'room_status' => RoomStatus::ACTIVE,
            'started_at' => now(),
        ]);

        $room->viewers()->attach(Auth::id(), ['joined_at' => now()]);

        // Зберігаємо пароль в сесії для показу в модальному вікні запрошення
        if ($this->isPrivate && $this->roomPassword) {
            session()->put("room_password_{$room->slug}", $this->roomPassword);
        }

        $this->closeModal();

        return $this->redirectRoute('movies.watch.room', [
            'movie' => $this->movie->slug,
            'episodeNumber' => $this->episode->number,
            'room' => $room->slug,
        ], navigate: true);
    }

    public function render()
    {
        return view('livewire.components.room-creation-modal');
    }
}
