<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;
use Livewire\WithPagination;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Rating;

class MovieReviews extends Component
{
    use WithPagination;
    
    public Movie $movie;
    
    protected $listeners = ['rating-updated' => '$refresh'];
    
    public function render()
    {
        $reviews = Rating::where('movie_id', $this->movie->id)
            ->whereNotNull('review')
            ->with('user')
            ->latest()
            ->paginate(5);
            
        return view('livewire.components.movie-reviews', [
            'reviews' => $reviews
        ]);
    }
}