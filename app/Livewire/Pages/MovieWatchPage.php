<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;

class MovieWatchPage extends Component
{
    public Movie $movie;

    public ?Episode $episode = null;



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



    public function render()
    {
        return view('livewire.pages.movie-watch', [
            'movie' => $this->movie,
            'episode' => $this->episode,
        ]);
    }
}
