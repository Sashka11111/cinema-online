<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;
use Liamtseva\Cinema\Models\Movie;

class MovieDetails extends Component
{
    public Movie $movie;

    public function render()
    {
        return view('livewire.components.movie-details');
    }
}