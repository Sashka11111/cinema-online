<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;
use Liamtseva\Cinema\Models\Person;

class PersonCard extends Component
{
    public Person $person;
    public ?string $characterName = null;
    public bool $showCharacter = false;

    public function mount(Person $person, ?string $characterName = null, bool $showCharacter = false): void
    {
        $this->person = $person;
        $this->characterName = $characterName;
        $this->showCharacter = $showCharacter;
    }

    public function render()
    {
        return view('livewire.components.person-card');
    }
}
