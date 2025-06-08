<div class="movie-reviews">
    <h2 class="movie-reviews__title">Відгуки користувачів</h2>
    
    @if($reviews->count() > 0)
        <div class="movie-reviews__list">
            @foreach($reviews as $review)
                <div class="movie-reviews__item">
                    <div class="movie-reviews__header">
                        <div class="movie-reviews__user">
                            <img 
                                src="{{ $review->user->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                alt="{{ $review->user->name }}" 
                                class="movie-reviews__avatar"
                            >
                            <span class="movie-reviews__username">{{ $review->user->name }}</span>
                        </div>
                        <div class="movie-reviews__rating">
                            <span class="movie-reviews__rating-value">{{ $review->number }}</span>
                            <span class="movie-reviews__rating-max">/10</span>
                        </div>
                    </div>
                    <div class="movie-reviews__content">
                        <p class="movie-reviews__text">{{ $review->review }}</p>
                    </div>
                    <div class="movie-reviews__footer">
                        <span class="movie-reviews__date">{{ $review->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="movie-reviews__pagination">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="movie-reviews__empty">
            <p>Поки що немає відгуків. Будьте першим, хто залишить відгук!</p>
        </div>
    @endif
</div>