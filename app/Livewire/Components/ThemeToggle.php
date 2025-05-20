<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class ThemeToggle extends Component
{
    public $theme = 'light';

    public function mount()
    {
        // Load the theme from session or user preference
        $this->theme = session('theme', 'light');
    }

    public function toggleTheme()
    {
        // Toggle between light and dark
        $this->theme = $this->theme === 'light' ? 'dark' : 'light';

        // Store the theme in session
        session(['theme' => $this->theme]);

        // Emit an event to update the frontend
        $this->dispatch('theme-changed', ['theme' => $this->theme]);
    }

    public function render()
    {
        return view('livewire.components.theme-toggle');
    }
}
