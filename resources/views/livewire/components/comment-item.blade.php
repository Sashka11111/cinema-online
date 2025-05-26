<div class="comment-item {{ $comment->parent_id ? 'comment-item--reply' : '' }}">
    <div class="comment-item__avatar">
        @if($comment->user && $comment->user->avatar)
            <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                 alt="{{ $comment->user->name }}">
        @else
            <div class="comment-item__avatar-placeholder">
                {{ $comment->user ? substr($comment->user->name, 0, 1) : '?' }}
            </div>
        @endif
    </div>

    <div class="comment-item__content">
        <div class="comment-item__header">
            <span
                class="comment-item__author">{{ $comment->user ? $comment->user->name : 'Користувач видалений' }}</span>
            <span class="comment-item__date">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        <div class="comment-item__body">
            {{ $comment->body }}
        </div>

        <div class="comment-item__actions">
            @auth
                <button class="comment-item__action comment-item__action--reply"
                        wire:click="toggleReplies">
                    {{ $showReplies ? 'Сховати відповіді' : 'Відповісти' }}
                </button>
            @endauth

            @if(!$comment->parent_id)
                <button class="comment-item__action comment-item__action--replies"
                        wire:click="toggleReplies">
                    {{ $comment->children_count ?? 0 }} відповідей
                </button>
            @endif
        </div>

        @if($showReplies)
            <div class="comment-item__replies">
                @foreach($replies as $reply)
                    <livewire:components.comment-item :comment="$reply" :key="'reply-'.$reply->id"/>
                @endforeach

                @auth
                    <div class="comment-item__reply-form">
                        <textarea
                            class="comment-item__reply-input"
                            placeholder="Напишіть вашу відповідь..."
                            wire:model="replyText"
                        ></textarea>
                        <button class="comment-item__reply-submit" wire:click="addReply">
                            Відповісти
                        </button>
                    </div>
                @endauth
            </div>
        @endif
    </div>
</div>
