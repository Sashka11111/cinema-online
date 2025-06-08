<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Room;
use Livewire\Component;

class MovieWatchPage extends Component
{
    public Movie $movie;

    public ?Episode $episode = null;

    public bool $isPrivate = false;

    public ?string $roomPassword = null;

    public int $maxViewers = 10;

    public function mount(Movie $movie, ?int $episodeNumber = null): void
    {
        $this->movie = $movie;

        if (! $movie->is_published && (! Auth::check() || ! Auth::user()->isAdmin())) {
            abort(404);
        }

        $this->movie->load('episodes');

        $this->episode = $episodeNumber
            ? Episode::where('movie_id', $movie->id)->where('number', $episodeNumber)->first()
            : Episode::where('movie_id', $movie->id)->orderBy('number')->first();

        if (! $this->episode) {
            abort(404, 'Епізод не знайдено');
        }
    }

    public function createRoom()
    {
        if (! Auth::check()) {
            session()->flash('error', 'Для створення кімнати необхідно авторизуватися');

            return;
        }

        $this->validate([
            'maxViewers' => 'required|integer|min:1|max:50',
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

        return $this->redirectRoute('movies.watch.room', [
            'movie' => $this->movie->slug,
            'episodeNumber' => $this->episode->number,
            'room' => $room->slug,
        ], navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.movie-watch', [
            'movie' => $this->movie,
            'episode' => $this->episode,
        ]);
    }
}
