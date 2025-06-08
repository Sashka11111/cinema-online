<div class="movie-rating">
    <div class="movie-rating__summary">
        @if($averageRating)
            <div class="movie-rating__average">
                <span class="movie-rating__average-value">{{ $averageRating }}</span>
                <span class="movie-rating__average-max">/10</span>
            </div>
            <div class="movie-rating__count">
                {{ $ratingsCount }} {{ trans_choice('оцінка|оцінки|оцінок', $ratingsCount) }}
            </div>
        @else
            <div class="movie-rating__no-ratings">Ще немає оцінок</div>
        @endif
    </div>

    <div class="movie-rating__user">
        <h3 class="movie-rating__title">
            <i class="fas fa-star"></i>
            Ваша оцінка
        </h3>

        <div class="movie-rating__stars">
            @for($i = 1; $i <= 10; $i++)
                <button
                    wire:click="rate({{ $i }})"
                    class="movie-rating__star {{ $userRating && $userRating >= $i ? 'movie-rating__star--active' : '' }}"
                    title="Оцінити на {{ $i }}/10"
                    @guest onclick="window.location.href='{{ route('login') }}'" wire:navigate @endguest
                >
                    <svg class="movie-rating__star-icon" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="movie-rating__star-number">{{ $i }}</span>
                </button>
            @endfor
        </div>

        @if($userRating)
            <div class="movie-rating__user-score">
                <span class="movie-rating__user-score-text">Ваша оцінка:</span>
                <span class="movie-rating__user-score-value">{{ $userRating }}/10</span>
                @if(empty($review))
                    <span class="movie-rating__review-hint">
                        <i class="fas fa-lightbulb"></i>
                        Додайте відгук (необов'язково)
                    </span>
                @endif
            </div>
        @endif

        @if($showReviewForm)
            <div class="movie-rating__review-form">
                <h4 class="movie-rating__review-title">
                    <i class="fas fa-edit"></i>
                    Ваш відгук
                </h4>
                <textarea
                    wire:model="review"
                    class="movie-rating__review-input"
                    placeholder="Поділіться своїми враженнями про фільм..."
                    rows="4"
                ></textarea>
                <div class="movie-rating__review-actions">
                    <button wire:click="saveReview" class="movie-rating__submit">
                        <i class="fas fa-save"></i>
                        Зберегти відгук
                    </button>
                    <button wire:click="toggleReviewForm" class="movie-rating__cancel">
                        <i class="fas fa-times"></i>
                        Скасувати
                    </button>
                </div>
            </div>
        @elseif($userRating && !empty($review))
            <div class="movie-rating__user-review">
                <h4>
                    <i class="fas fa-quote-left"></i>
                    Ваш відгук:
                </h4>
                <p>{{ $review }}</p>
                <button wire:click="toggleReviewForm" class="movie-rating__edit-review">
                    <i class="fas fa-edit"></i>
                    Редагувати відгук
                </button>
            </div>
        @elseif($userRating)
            <button wire:click="toggleReviewForm" class="movie-rating__add-review">
                <i class="fas fa-plus"></i>
                Додати відгук
            </button>
        @endif

        @guest
            <div class="movie-rating__login-prompt">
                <p class="movie-rating__login-text">
                    <i class="fas fa-info-circle"></i>
                    Щоб оцінити фільм, потрібно
                    <a href="{{ route('login') }}" wire:navigate class="movie-rating__login-link">увійти в систему</a>
                </p>
            </div>
        @endguest
    </div>
</div>
