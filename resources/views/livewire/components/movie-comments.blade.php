<div class="movie-comments">
    <h2 class="movie-comments__title">Коментарі</h2>
    
    @auth
        <div class="movie-comments__form">
            <textarea 
                class="movie-comments__input" 
                placeholder="Напишіть ваш коментар..."
                wire:model="commentText"
            ></textarea>
            <button class="movie-comments__submit" wire:click="addComment">Відправити</button>
        </div>
    @else
        <div class="movie-comments__login-prompt">
            <p>Щоб залишити коментар, будь ласка, <a href="{{ route('login') }}">увійдіть</a> або <a href="{{ route('register') }}">зареєструйтесь</a>.</p>
        </div>
    @endauth
    
    <div class="movie-comments__list">
        @forelse($movie->comments as $comment)
            <livewire:components.comment-item :comment="$comment" :key="'comment-'.$comment->id" />
        @empty
            <div class="movie-comments__empty">
                <p>Коментарів поки немає. Будьте першим, хто залишить коментар!</p>
            </div>
        @endforelse
    </div>
</div>