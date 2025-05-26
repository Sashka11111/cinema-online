<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Liamtseva\Cinema\Models\Comment;
use Livewire\Component;

class CommentItem extends Component
{
    public Comment $comment;

    public bool $showReplies = false;

    public string $replyText = '';

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function toggleReplies()
    {
        $this->showReplies = ! $this->showReplies;
    }

    public function addReply()
    {
        $this->validate([
            'replyText' => 'required|min:3|max:1000',
        ]);

        Comment::create([
            'commentable_id' => $this->comment->commentable_id,
            'commentable_type' => $this->comment->commentable_type,
            'user_id' => auth()->id(),
            'body' => $this->replyText,
            'parent_id' => $this->comment->id,
        ]);

        $this->replyText = '';
        $this->comment->refresh();
    }

    public function getRepliesProperty()
    {
        // Завантажуємо відповіді з eager loading для зв'язку user
        return Comment::where('parent_id', $this->comment->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.components.comment-item', [
            'replies' => $this->showReplies ? $this->getRepliesProperty() : collect(),
        ]);
    }
}
