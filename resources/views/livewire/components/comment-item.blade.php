<div class="comment-item {{ $comment->parent_id ? 'comment-item--reply' : '' }}">
    <div class="comment-item__avatar">
        @if($comment->user && $comment->user->avatar)
            <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                 alt="{{ $comment->user->name }}"
                 class="comment-item__avatar-image">
        @else
            <div class="comment-item__avatar-placeholder">
                {{ $comment->user ? substr($comment->user->name, 0, 1) : '?' }}
            </div>
        @endif
    </div>

    <div class="comment-item__content">
        <div class="comment-item__header">
            <div class="comment-item__meta">
                <span class="comment-item__author">
                    {{ $comment->user ? $comment->user->name : 'Користувач видалений' }}
                </span>
                <span class="comment-item__date">{{ $comment->created_at->diffForHumans() }}</span>
                @if($comment->is_spoiler)
                    <span class="comment-item__spoiler-badge">Спойлер</span>
                @endif
            </div>
        </div>

        <div class="comment-item__body">
            @if($comment->is_spoiler && !$isSpoilerRevealed)
                <div class="comment-item__spoiler">
                    <div class="comment-item__spoiler-warning">
                        <span
                            class="comment-item__spoiler-text">Цей коментар містить спойлери</span>
                        <button class="comment-item__spoiler-reveal" wire:click="revealSpoiler">
                            Показати
                        </button>
                    </div>
                </div>
            @else
                <div class="comment-item__text">{{ $comment->body }}</div>
            @endif
        </div>

        <div class="comment-item__actions">
            <div class="comment-item__reactions">
                <button
                    class="comment-item__reaction comment-item__reaction--like {{ $userLikeStatus === 'liked' ? 'comment-item__reaction--active' : '' }}"
                    wire:click="toggleLike"
                    @guest onclick="window.location.href='{{ route('login') }}'" @endguest>
                    <span class="comment-item__reaction-icon">👍</span>
                    @if($likesCount > 0)
                        <span class="comment-item__reaction-count">{{ $likesCount }}</span>
                    @endif
                </button>

                <button
                    class="comment-item__reaction comment-item__reaction--dislike {{ $userLikeStatus === 'disliked' ? 'comment-item__reaction--active' : '' }}"
                    wire:click="toggleDislike"
                    @guest onclick="window.location.href='{{ route('login') }}'" @endguest>
                    <span class="comment-item__reaction-icon">👎</span>
                    @if($dislikesCount > 0)
                        <span class="comment-item__reaction-count">{{ $dislikesCount }}</span>
                    @endif
                </button>
            </div>

            <div class="comment-item__controls">
                @auth
                    @if(!$comment->parent_id)
                        <button class="comment-item__action comment-item__action--reply"
                                wire:click="toggleReplyForm">
                            {{ $showReplyForm ? 'Скасувати' : 'Відповісти' }}
                        </button>
                    @endif
                @endauth

                @if(!$comment->parent_id && $childrenCount > 0)
                    <button class="comment-item__action comment-item__action--replies"
                            wire:click="toggleReplies">
                        {{ $showReplies ? 'Сховати відповіді' : 'Показати відповіді' }}
                        ({{ $childrenCount }})
                    </button>
                @endif
            </div>
        </div>

        @if($showReplyForm && !$comment->parent_id)
            <div class="comment-item__reply-form">
                <div class="comment-item__reply-form-content">
                    <textarea
                        class="comment-item__reply-input"
                        placeholder="Напишіть вашу відповідь..."
                        wire:model="replyText"
                        rows="3"
                    ></textarea>
                    @error('replyText')
                    <span class="comment-item__error">{{ $message }}</span>
                    @enderror
                    <div class="comment-item__reply-actions">
                        <button class="comment-item__reply-submit"
                                wire:click="addReply"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Відповісти</span>
                            <span wire:loading>Відправляємо...</span>
                        </button>
                        <button class="comment-item__reply-cancel"
                                wire:click="toggleReplyForm">
                            Скасувати
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if($showReplies && !$comment->parent_id && $childrenCount > 0)
            <div class="comment-item__replies">
                @foreach($comment->children as $reply)
                    <livewire:components.comment-item
                        :comment="$reply"
                        :key="'reply-'.$reply->id"
                        wire:key="reply-{{ $reply->id }}"
                    />
                @endforeach
            </div>
        @endif
    </div>
</div>
