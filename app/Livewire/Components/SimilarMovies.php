<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class SimilarMovies extends Component
{
    public Collection $movies;

    public function render()
    {
        return view('livewire.components.similar-movies');
    }
}