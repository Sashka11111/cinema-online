<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class MovieCast extends Component
{
    public Collection $persons;

    public function render()
    {
        return view('livewire.components.movie-cast');
    }
}