<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\CommentLike;
use Livewire\Component;

class CommentItem extends Component
{
    public Comment $comment;
    public bool $showReplies = false;
    public bool $showReplyForm = false;
    public string $replyText = '';
    public bool $isSpoilerRevealed = false;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
        // Завантажуємо необхідні зв'язки
        $this->comment->load(['user', 'likes', 'children.user']);
    }

    public function toggleReplies()
    {
        $this->showReplies = !$this->showReplies;

        if ($this->showReplies) {
            // Перезавантажуємо дочірні коментарі з користувачами
            $this->comment->load(['children.user', 'children.likes']);
        }
    }

    public function toggleReplyForm()
    {
        if (!auth()->check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $this->showReplyForm = !$this->showReplyForm;

        if (!$this->showReplyForm) {
            $this->replyText = '';
        }
    }

    public function addReply()
    {
        if (!auth()->check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $this->validate([
            'replyText' => 'required|min:3|max:1000',
        ], [
            'replyText.required' => 'Відповідь не може бути порожньою',
            'replyText.min' => 'Відповідь повинна містити принаймні 3 символи',
            'replyText.max' => 'Відповідь не може перевищувати 1000 символів',
        ]);

        $reply = new Comment();
        $reply->commentable_id = $this->comment->commentable_id;
        $reply->commentable_type = $this->comment->commentable_type;
        $reply->user_id = auth()->id();
        $reply->body = $this->replyText;
        $reply->parent_id = $this->comment->id;
        $reply->is_spoiler = false;
        $reply->save();

        $this->replyText = '';
        $this->showReplyForm = false;

        // Перезавантажуємо коментар з дочірніми коментарями
        $this->comment->load(['children.user', 'children.likes']);

        // Показуємо відповіді після додавання нової
        $this->showReplies = true;

        session()->flash('message', 'Відповідь успішно додана!');
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $existingLike = CommentLike::where('comment_id', $this->comment->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingLike) {
            if ($existingLike->is_liked) {
                // Якщо вже лайкнуто, видаляємо лайк
                $existingLike->delete();
            } else {
                // Якщо дизлайкнуто, змінюємо на лайк
                $existingLike->update(['is_liked' => true]);
            }
        } else {
            // Створюємо новий лайк
            $like = new CommentLike();
            $like->comment_id = $this->comment->id;
            $like->user_id = auth()->id();
            $like->is_liked = true;
            $like->save();
        }

        // Перезавантажуємо лайки
        $this->comment->load('likes');
    }

    public function toggleDislike()
    {
        if (!auth()->check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $existingLike = CommentLike::where('comment_id', $this->comment->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingLike) {
            if (!$existingLike->is_liked) {
                // Якщо вже дизлайкнуто, видаляємо дизлайк
                $existingLike->delete();
            } else {
                // Якщо лайкнуто, змінюємо на дизлайк
                $existingLike->update(['is_liked' => false]);
            }
        } else {
            // Створюємо новий дизлайк
            $dislike = new CommentLike();
            $dislike->comment_id = $this->comment->id;
            $dislike->user_id = auth()->id();
            $dislike->is_liked = false;
            $dislike->save();
        }

        // Перезавантажуємо лайки
        $this->comment->load('likes');
    }

    public function revealSpoiler()
    {
        $this->isSpoilerRevealed = true;
    }

    public function getLikesCountProperty()
    {
        return $this->comment->likes->where('is_liked', true)->count();
    }

    public function getDislikesCountProperty()
    {
        return $this->comment->likes->where('is_liked', false)->count();
    }

    public function getUserLikeStatusProperty()
    {
        if (!auth()->check()) {
            return null;
        }

        $userLike = $this->comment->likes->where('user_id', auth()->id())->first();

        if (!$userLike) {
            return null;
        }

        return $userLike->is_liked ? 'liked' : 'disliked';
    }

    public function getChildrenCountProperty()
    {
        return $this->comment->children->count();
    }

    public function render()
    {
        return view('livewire.components.comment-item', [
            'likesCount' => $this->getLikesCountProperty(),
            'dislikesCount' => $this->getDislikesCountProperty(),
            'userLikeStatus' => $this->getUserLikeStatusProperty(),
            'childrenCount' => $this->getChildrenCountProperty(),
        ]);
    }
}
