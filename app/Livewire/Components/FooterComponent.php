<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class FooterComponent extends Component
{
    public $route;

    public $text;

    public $linkText;

    public function mount($route, $text, $linkText)
    {
        $this->route = $route;
        $this->text = $text;
        $this->linkText = $linkText;
    }

    public function render()
    {
        return view('livewire.components.footer-component');
    }
}
