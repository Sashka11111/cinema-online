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
            ->where('is_published', true)
            ->firstOrFail();
    }
    
    public function render()
    {
        return view('livewire.pages.selection-show', [
            'selection' => $this->selection,
            'movies' => $this->selection->movies()->paginate(12),
        ]);
    }
}