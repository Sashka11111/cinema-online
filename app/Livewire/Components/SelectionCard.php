<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Liamtseva\Cinema\Models\Selection;
use Livewire\Component;

class SelectionCard extends Component
{
    public Selection $selection;

    public function mount(Selection $selection)
    {
        $this->selection = $selection->loadCount([
            'movies as movies_count' => fn ($q) => $q->where('is_published', true),
            'persons',
            'episodes',
        ]);
    }

    public function render()
    {
        $posters = $this->selection->movies->whereNotNull('poster_url')->take(3);

        return view('livewire.components.selection-card', [
            'posters' => $posters,
        ]);
    }
}
