<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class MovieRating extends Component
{
    public Movie $movie;
    public ?int $userRating = null;
    public string $review = '';
    public bool $showReviewForm = false;

    public function mount(Movie $movie)
    {
        $this->movie = $movie;

        if (Auth::check()) {
            $rating = Rating::where('movie_id', $movie->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($rating) {
                $this->userRating = $rating->number;
                $this->review = $rating->review ?? '';
            }
        }
    }

    public function rate($value)
    {
        if (!Auth::check()) {
            $this->dispatch('show-notification', [
                'type' => 'info',
                'message' => 'Для оцінювання фільму потрібно увійти в систему'
            ]);
            return $this->redirectRoute('login', navigate: true);
        }

        $this->userRating = $value;
        $this->saveRating();

        $this->dispatch('show-notification', [
            'type' => 'success',
            'message' => "Ви оцінили фільм на {$value}/10"
        ]);
    }

    public function saveRating()
    {
        if (!Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $rating = Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'movie_id' => $this->movie->id,
            ],
            [
                'number' => $this->userRating,
                'review' => $this->review,
            ]
        );

        $this->dispatch('rating-updated');
    }

    public function saveReview()
    {
        $this->validate([
            'review' => 'nullable|max:65535',
        ]);

        $this->saveRating();
        $this->showReviewForm = false;

        $this->dispatch('show-notification', [
            'type' => 'success',
            'message' => 'Ваш відгук збережено!'
        ]);
    }

    public function toggleReviewForm()
    {
        $this->showReviewForm = !$this->showReviewForm;
    }

    public function render()
    {
        $averageRating = Rating::where('movie_id', $this->movie->id)->avg('number');
        $ratingsCount = Rating::where('movie_id', $this->movie->id)->count();

        return view('livewire.components.movie-rating', [
            'averageRating' => $averageRating ? round($averageRating, 1) : null,
            'ratingsCount' => $ratingsCount,
        ]);
    }
}
