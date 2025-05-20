<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class Breadcrumbs extends Component
{
    public array $items = [];

    public function render()
    {
        return view('livewire.components.breadcrumbs');
    }
}