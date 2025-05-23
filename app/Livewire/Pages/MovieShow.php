<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;

class MovieShow extends Component
{
    public Movie $movie;

    public function mount(Movie $movie)
    {
        $this->movie = $movie;

        // Перевіряємо, чи фільм опублікований або користувач є адміністратором
        if (! $movie->is_published && (! Auth::check() || ! Auth::user()->isAdmin())) {
            abort(404);
        }

        // Можна додати логіку для збільшення лічильника переглядів
        // $movie->increment('views_count');
    }

    public function render()
    {
        return view('livewire.pages.movie-show', [
            'movie' => $this->movie,
            'relatedMovies' => $this->getRelatedMovies(),
            'similarMovies' => $this->getSimilarMovies(),
            'meta' => $this->getMetaData(),
        ]);
    }

    protected function getMetaData()
    {
        return [
            'title' => $this->movie->meta_title ?? Movie::makeMetaTitle($this->movie->name),
            'description' => $this->movie->meta_description ?? Movie::makeMetaDescription(strip_tags($this->movie->description)),
            'image' => $this->movie->meta_image ?? $this->movie->poster_url,
            'type' => 'video.movie',
            'url' => url()->current(),
        ];
    }

    protected function getRelatedMovies()
    {
        // Отримуємо пов'язані фільми з колекції related
        $relatedIds = collect($this->movie->related ?? [])
            ->pluck('movie_id')
            ->toArray();

        if (empty($relatedIds)) {
            return collect();
        }

        return Movie::query()
            ->whereIn('id', $relatedIds)
            ->where('is_published', true)
            ->limit(6)
            ->get();
    }

    protected function getSimilarMovies()
    {
        // Отримуємо схожі фільми з колекції similars або за тегами
        $similarIds = collect($this->movie->similars ?? [])
            ->pluck('movie_id')
            ->toArray();

        if (! empty($similarIds)) {
            return Movie::query()
                ->whereIn('id', $similarIds)
                ->where('is_published', true)
                ->limit(6)
                ->get();
        }

        // Якщо немає явно вказаних схожих фільмів, шукаємо за тегами
        $tagIds = $this->movie->tags->pluck('id')->toArray();

        if (empty($tagIds)) {
            return collect();
        }

        return Movie::query()
            ->where('id', '!=', $this->movie->id)
            ->where('is_published', true)
            ->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })
            ->limit(6)
            ->get();
    }
}
