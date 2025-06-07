<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Liamtseva\Cinema\Models\Selection;
use Livewire\Component;

class SelectionShowPage extends Component
{
    public Selection $selection;

    public function mount($slug)
    {
        $this->selection = Selection::where('slug', $slug)
            ->with([
                'user',
                'movies' => function($query) {
                    $query->with(['studio', 'tags']);
                },
                'persons',
                'episodes' => function($query) {
                    $query->with(['movie']);
                }
            ])
            ->withCount(['movies', 'persons', 'episodes'])
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.pages.selection-show', [
            'selection' => $this->selection,
        ]);
    }
}
