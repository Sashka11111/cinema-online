<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Liamtseva\Cinema\Models\Selection;
use Livewire\Component;

class SelectionCard extends Component
{
    public Selection $selection;

    public function render()
    {
        $posters = $this->selection->movies->whereNotNull('poster_url')->take(3);

        return view('livewire.components.selection-card', [
            'posters' => $posters,
        ]);
    }
}
