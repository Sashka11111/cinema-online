<div class="movie-comments-page">
    <livewire:components.header-component/>

    <main class="movie-comments-page__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Фільми', 'route' => 'movies'],
                ['label' => $movie->name, 'route' => 'movies.show', 'params' => ['movie' => $movie]],
                ['label' => 'Коментарі', 'active' => true]
            ]"/>

            <div class="movie-comments-page__header">
                <div class="movie-comments-page__movie-info">
                    <div class="movie-comments-page__poster">
                        <img src="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
                             alt="{{ $movie->name }}"
                             class="movie-comments-page__poster-image">
                    </div>
                    <div class="movie-comments-page__details">
                        <h1 class="movie-comments-page__title">Коментарі до фільму</h1>
                        <h2 class="movie-comments-page__movie-title">{{ $movie->name }}</h2>
                        <div class="movie-comments-page__meta">
                            {{--                            <span class="movie-comments-page__year">{{ $movie->year }}</span>--}}
                            {{--                            @if($movie->rating)--}}
                            {{--                                <span--}}
                            {{--                                    class="movie-comments-page__rating">{{ $movie->rating }}/10</span>--}}
                            {{--                            @endif--}}
                            <span class="movie-comments-page__comments-count">{{ $commentsCount }} коментарів</span>
                        </div>
                        <a href="{{ route('movies.show', $movie->slug) }}"
                           class="movie-comments-page__back-link">
                            ← Повернутися до фільму
                        </a>
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="movie-comments-page__alert movie-comments-page__alert--success">
                    {{ session('message') }}
                </div>
            @endif

            <div class="movie-comments-page__content">
                <div class="movie-comments-page__form-section">
                    @auth
                        <div class="movie-comments-page__form">
                            <h3 class="movie-comments-page__form-title">Залишити коментар</h3>
                            <div class="movie-comments-page__form-content">
                                <textarea
                                    class="movie-comments-page__textarea"
                                    placeholder="Напишіть ваш коментар про фільм..."
                                    wire:model="commentText"
                                    rows="4"
                                ></textarea>
                                @error('commentText')
                                <span class="movie-comments-page__error">{{ $message }}</span>
                                @enderror
                                <div class="movie-comments-page__form-actions">
                                    <button class="movie-comments-page__submit-btn"
                                            wire:click="addComment"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>Відправити коментар</span>
                                        <span wire:loading>Відправляємо...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="movie-comments-page__login-prompt">
                            <h3 class="movie-comments-page__login-title">Залишити коментар</h3>
                            <p class="movie-comments-page__login-text">
                                Щоб залишити коментар, будь ласка,
                                <a href="{{ route('login') }}"
                                   class="movie-comments-page__login-link">увійдіть</a>
                                або
                                <a href="{{ route('register') }}"
                                   class="movie-comments-page__login-link">зареєструйтесь</a>.
                            </p>
                        </div>
                    @endauth
                </div>

                <div class="movie-comments-page__comments-section">
                    <div class="movie-comments-page__comments-header">
                        <h3 class="movie-comments-page__comments-title">
                            Коментарі ({{ $commentsCount }})
                        </h3>

                        <div class="movie-comments-page__sort">
                            <label class="movie-comments-page__sort-label">Сортувати:</label>
                            <select class="movie-comments-page__sort-select"
                                    wire:change="setSortBy($event.target.value)">
                                <option value="newest" {{ $sortBy === 'newest' ? 'selected' : '' }}>
                                    Спочатку нові
                                </option>
                                <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>
                                    Спочатку старі
                                </option>
                                <option
                                    value="most_replies" {{ $sortBy === 'most_replies' ? 'selected' : '' }}>
                                    З найбільшою кількістю відповідей
                                </option>
                                <option
                                    value="most_liked" {{ $sortBy === 'most_liked' ? 'selected' : '' }}>
                                    Найбільш популярні
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="movie-comments-page__comments-list">
                        @forelse($comments as $comment)
                            <livewire:components.comment-item
                                :comment="$comment"
                                :key="'comment-'.$comment->id"
                                wire:key="comment-{{ $comment->id }}"
                            />
                        @empty
                            <div class="movie-comments-page__empty">
                                <div class="movie-comments-page__empty-icon">💬</div>
                                <h4 class="movie-comments-page__empty-title">Коментарів поки
                                    немає</h4>
                                <p class="movie-comments-page__empty-text">
                                    Будьте першим, хто залишить коментар про цей фільм!
                                </p>
                            </div>
                        @endforelse
                    </div>

                    @if($comments->hasPages())
                        <div class="movie-comments-page__pagination">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>
