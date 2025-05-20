<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Livewire\Component;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Comment;

class MovieComments extends Component
{
    public Movie $movie;
    public string $commentText = '';

    public function addComment()
    {
        $this->validate([
            'commentText' => 'required|min:3|max:1000',
        ]);

        Comment::create([
            'commentable_id' => $this->movie->id,
            'commentable_type' => Movie::class,
            'user_id' => auth()->id(),
            'content' => $this->commentText,
        ]);

        $this->commentText = '';
        $this->movie->refresh();
    }

    public function render()
    {
        return view('livewire.components.movie-comments');
    }
}