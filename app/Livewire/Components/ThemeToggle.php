<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class ThemeToggle extends Component
{
    public $theme = 'light';

    public function mount()
    {
        // Load the theme from session or default to dark
        $this->theme = session('theme', 'dark');
    }

    public function toggleTheme()
    {
        $this->theme = $this->theme === 'light' ? 'dark' : 'light';
        session(['theme' => $this->theme]);
        $this->dispatch('theme-changed', theme: $this->theme); // Іменований аргумент
    }

    public function render()
    {
        return view('livewire.components.theme-toggle');
    }
}
