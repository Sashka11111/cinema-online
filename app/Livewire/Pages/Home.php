<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Liamtseva\Cinema\Models\Selection;
use Livewire\Component;

class Home extends Component
{
    public $latestSelections;

    public function mount()
    {
        $this->latestSelections = Selection::with(['movies', 'user'])->orderBy('created_at', 'desc')->take(3)->get();
    }

    public function render()
    {
        return view('livewire.pages.home');
    }
}
