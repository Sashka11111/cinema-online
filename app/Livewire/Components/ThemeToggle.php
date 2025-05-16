<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class ThemeToggle extends Component
{
    public $theme = 'light';

    public function mount()
    {
        $this->theme = session('theme', 'light');
    }

    public function toggleTheme()
    {
        $this->theme = $this->theme === 'light' ? 'dark' : 'light';
        session(['theme' => $this->theme]);
        $this->dispatch('themeChanged', theme: $this->theme);
    }

    public function render()
    {
        return view('livewire.components.theme-toggle');
    }
}
