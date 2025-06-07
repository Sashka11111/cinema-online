<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Liamtseva\Cinema\Models\Person;
use Livewire\Component;

class PersonDetail extends Component
{
    public $person;

    public $pageTitle;

    public function mount($person)
    {
        // Assuming $person is an ID or slug
        $this->person = Person::where('slug', $person)
            ->orWhere('id', $person)
            ->firstOrFail();
    }

    public function render()
    {
        $movies = $this->person->movies()->paginate(12);

        return view('livewire.pages.person-detail', [
            'movies' => $movies,
            'pageTitle' => $this->pageTitle,
        ]);
    }
}
