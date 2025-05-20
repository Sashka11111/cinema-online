<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;

class SectionTitleComponent extends Component
{
    public string $title;
    public ?string $subtitle = null;
    public ?string $showAllLink = null;
    
    public function mount($title, $subtitle = null, $showAllLink = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->showAllLink = $showAllLink;
    }
    
    public function render()
    {
        return view('livewire.components.section-title-component');
    }
}