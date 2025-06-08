<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;

class MovieCommentsPage extends Component
{
    use WithPagination;

    public Movie $movie;
    public string $commentText = '';
    public string $sortBy = 'newest';

    protected $queryString = [
        'sortBy' => ['except' => 'newest'],
    ];

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function addComment()
    {
        if (!auth()->check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $this->validate([
            'commentText' => 'required|min:3|max:1000',
        ], [
            'commentText.required' => 'Коментар не може бути порожнім',
            'commentText.min' => 'Коментар повинен містити принаймні 3 символи',
            'commentText.max' => 'Коментар не може перевищувати 1000 символів',
        ]);

        $comment = new Comment();
        $comment->commentable_id = $this->movie->id;
        $comment->commentable_type = Movie::class;
        $comment->user_id = auth()->id();
        $comment->body = $this->commentText;
        $comment->is_spoiler = false;
        $comment->save();

        $this->commentText = '';
        $this->resetPage();

        session()->flash('message', 'Коментар успішно додано!');
    }

    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function getCommentsProperty()
    {
        $query = Comment::where('commentable_id', $this->movie->id)
            ->where('commentable_type', Movie::class)
            ->whereNull('parent_id') // Тільки кореневі коментарі
            ->with(['user', 'children.user', 'likes']);

        return match ($this->sortBy) {
            'oldest' => $query->orderBy('created_at', 'asc')->paginate(10),
            'most_replies' => $query->withCount('children')
                ->orderBy('children_count', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'most_liked' => $query->withCount(['likes as likes_count' => function ($query) {
                    $query->where('is_liked', true);
                }])
                ->orderBy('likes_count', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            default => $query->orderBy('created_at', 'desc')->paginate(10),
        };
    }

    public function getCommentsCountProperty()
    {
        return Comment::where('commentable_id', $this->movie->id)
            ->where('commentable_type', Movie::class)
            ->count();
    }

    public function render()
    {
        return view('livewire.pages.movie-comments', [
            'comments' => $this->getCommentsProperty(),
            'commentsCount' => $this->getCommentsCountProperty(),
        ])->title("Коментарі до фільму \"{$this->movie->name}\"");
    }
}
