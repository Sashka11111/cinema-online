<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;

class MovieComments extends Component
{
    public Movie $movie;

    public string $commentText = '';

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function addComment()
    {
        $this->validate([
            'commentText' => 'required|min:3|max:1000',
        ]);

        Comment::create([
            'commentable_id' => $this->movie->id,
            'commentable_type' => Movie::class,
            'user_id' => auth()->id(),
            'body' => $this->commentText,
        ]);

        $this->commentText = '';
        $this->movie->refresh();
    }

    public function getCommentsProperty()
    {
        // Завантажуємо коментарі з eager loading для зв'язку user
        return Comment::where('commentable_id', $this->movie->id)
            ->where('commentable_type', Movie::class)
            ->with('user') // Eager loading зв'язку user
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.components.movie-comments', [
            'comments' => $this->getCommentsProperty(),
        ]);
    }
}
