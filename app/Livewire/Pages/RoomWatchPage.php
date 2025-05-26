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

    public function mount(Movie $movie, ?int $episodeNumber = null, ?Room $room = null)
    {
        $this->movie = $movie;
        $this->room = $room;

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

        if ($this->room) {
            if (! $this->room->isActive()) {
                session()->flash('error', 'Кімната не знайдена або вже завершена');
                return redirect()->route('movies.watch.episode', [
                    'movie' => $this->movie->id,
                    'episodeNumber' => $this->episode->number,
                ]);
            }

            if (Auth::check()) {
                if ($this->room->isFull() && $this->room->user_id !== Auth::id()) {
                    session()->flash('error', 'Кімната заповнена');
                    return redirect()->route('movies.watch.episode', [
                        'movie' => $this->movie->id,
                        'episodeNumber' => $this->episode->number,
                    ]);
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

        if ($room->is_private && ! password_verify($password, $room->password)) {
            session()->flash('error', 'Невірний пароль');

            return;
        }

        $room->viewers()->syncWithoutDetaching([
            Auth::id() => ['joined_at' => now()],
        ]);

        return redirect()->route('movies.watch.room', [
            'movie' => $this->movie->id,
            'episodeNumber' => $this->episode->number,
            'room' => $room->slug,
        ]);
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

        return redirect()->route('movies.watch.episode', [
            'movie' => $this->movie->id,
            'episodeNumber' => $this->episode->number,
        ]);
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
