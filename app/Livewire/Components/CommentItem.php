<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Liamtseva\Cinema\Models\Comment;
use Livewire\Component;

class CommentItem extends Component
{
    public Comment $comment;

    public bool $showReplyForm = false;

    public string $replyText = '';

    public function showReplyForm()
    {
        $this->showReplyForm = true;
    }

    public function hideReplyForm()
    {
        $this->showReplyForm = false;
        $this->replyText = '';
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
            'parent_id' => $this->comment->id,
            'content' => $this->replyText,
        ]);

        $this->replyText = '';
        $this->showReplyForm = false;
        $this->comment->refresh();
    }

    public function render()
    {
        return view('livewire.components.comment-item');
    }
}
