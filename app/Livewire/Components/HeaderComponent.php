<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class HeaderComponent extends Component
{
    public $currentRoute = 'home';

    public $routes = [
        ['name' => 'home', 'label' => 'Головна'],
        ['name' => 'movies', 'label' => 'Фільми'],
        ['name' => 'series', 'label' => 'Серіали'],
        ['name' => 'cartoons', 'label' => 'Мультфільми'],
        ['name' => 'cartoon-series', 'label' => 'Мультсеріали'],
        ['name' => 'anime', 'label' => 'Аніме'],
        ['name' => 'selections', 'label' => 'Підбірки'],
    ];

    public function navigate($route)
    {
        $this->currentRoute = $route;
        $this->dispatch('navigate-to', route: route($route));
    }

    public function render()
    {
        return view('livewire.components.header-component');
    }
}
