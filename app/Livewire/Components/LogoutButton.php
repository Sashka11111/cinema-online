<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Liamtseva\Cinema\Livewire\Actions\Logout;
use Livewire\Component;

class LogoutButton extends Component
{
    public function logout()
    {
        app(Logout::class)();

        return $this->redirectRoute('home', navigate: true);
    }

    public function render()
    {
        return view('livewire.components.logout-button');
    }
}
