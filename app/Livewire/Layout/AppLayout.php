<?php

namespace Liamtseva\Cinema\Livewire\Layout;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function render(): View
    {
        return view('livewire.layout.app');
    }
}
