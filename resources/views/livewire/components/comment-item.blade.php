<div class="movie-comment">
    <div class="movie-comment__avatar">
        <img 
            src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('images/default-avatar.jpg') }}" 
            alt="{{ $comment->user->name }}" 
            class="movie-comment__avatar-img"
        >
    </div>
    <div class="movie-comment__content">
        <div class="movie-comment__header">
            <div class="movie-comment__author">{{ $comment->user->name }}</div>
            <div class="movie-comment__date">{{ $comment->created_at->diffForHumans() }}</div>
        </div>
        <div class="movie-comment__text">{{ $comment->content }}</div>
        <div class="movie-comment__actions">
            <button class="movie-comment__action movie-comment__action--reply" wire:click="showReplyForm">Відповісти</button>
            <button class="movie-comment__action movie-comment__action--like" wire:click="likeComment">
                <i class="fas fa-thumbs-up"></i> 
                <span class="movie-comment__likes-count">{{ $comment->likes_count ?? 0 }}</span>
            </button>
        </div>
        
        @if($showReplyForm)
            <div class="movie-comment__reply-form">
                <textarea 
                    class="movie-comment__reply-input" 
                    placeholder="Напишіть вашу відповідь..."
                    wire:model="replyText"
                ></textarea>
                <button class="movie-comment__reply-submit" wire:click="addReply">Відповісти</button>
                <button class="movie-comment__reply-cancel" wire:click="hideReplyForm">Скасувати</button>
            </div>
        @endif
        
        @if($comment->replies && $comment->replies->isNotEmpty())
            <div class="movie-comment__replies">
                @foreach($comment->replies as $reply)
                    <livewire:components.comment-item :comment="$reply" :key="'reply-'.$reply->id" />
                @endforeach
            </div>
        @endif
    </div>
</div>