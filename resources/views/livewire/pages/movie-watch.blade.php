<div class="movie-watch">
    <livewire:components.header-component/>

    <main class="movie-watch__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Фільми', 'route' => 'movies'],
                ['label' => $movie->name, 'route' => 'movies.show', 'params' => ['movie' => $movie]],
                ['label' => 'Перегляд', 'active' => true]
            ]"/>

            <h1 class="movie-watch__title">Перегляд: {{ $movie->name }}</h1>

            @if($episode)
                <div class="movie-watch__player-container">
                    @if($episode->video_players && $episode->video_players->isNotEmpty())
                        <div class="movie-watch__player">
                            @php
                                $firstPlayer = $episode->video_players->first();
                                $videoUrl = $firstPlayer['file_url'] ?? '';
                            @endphp

                            <video
                                id="videoPlayer"
                                src="{{ asset('storage/' . $videoUrl) }}"
                                class="movie-watch__video"
                                controls
                                autoplay
                                controlsList="nodownload"
                                data-episode-id="{{ $episode->id }}"
                            ></video>
                        </div>

                        @if(count($episode->video_players) > 1)
                            <div class="movie-watch__player-options">
                                <div class="movie-watch__player-options-title">Доступні плеєри:
                                </div>
                                <div class="movie-watch__player-buttons">
                                    @foreach($episode->video_players as $index => $player)
                                        <button
                                            class="movie-watch__player-button {{ $index === 0 ? 'movie-watch__player-button--active' : '' }}"
                                            onclick="changeVideoSource('{{ asset('storage/' . ($player['file_url'] ?? '')) }}', this)"
                                        >
                                            {{ $player['name'] ?? 'Плеєр ' . ($index + 1) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @elseif($movie->attachments && collect($movie->attachments)->firstWhere('type', 'video'))
                        @php
                            $videoAttachment = collect($movie->attachments)->firstWhere('type', 'video');
                            $videoUrl = $videoAttachment['src'] ?? '';
                        @endphp

                        <div class="movie-watch__player">
                            <video
                                id="videoPlayer"
                                src="{{ asset('storage/' . $videoUrl) }}"
                                class="movie-watch__video"
                                controls
                                autoplay
                                controlsList="nodownload"
                                data-episode-id="{{ $episode->id }}"
                            ></video>
                        </div>
                    @else
                        <div class="movie-watch__placeholder">
                            <p>Відео недоступне для перегляду</p>
                            <p class="movie-watch__placeholder-hint">Спробуйте додати відео через
                                адмін-панель</p>
                        </div>
                    @endif
                </div>

                <div class="movie-watch__episode-info">
                    <div class="movie-watch__episode-header">
                        <h2 class="movie-watch__episode-title">
                            @if($episode->number)
                                Епізод {{ $episode->number }}:
                            @endif
                            {{ $episode->name }}
                        </h2>

                        @if($movie->episodes && $movie->episodes->count() > 1)
                            <div class="movie-watch__episode-navigation">
                                @php
                                    $currentIndex = $movie->episodes->search(function($ep) use ($episode) {
                                        return $ep->id === $episode->id;
                                    });
                                    $prevEpisode = $currentIndex > 0 ? $movie->episodes[$currentIndex - 1] : null;
                                    $nextEpisode = $currentIndex < $movie->episodes->count() - 1 ? $movie->episodes[$currentIndex + 1] : null;
                                @endphp

                                @if($prevEpisode)
                                    <a href="{{ route('movies.watch.episode', ['movie' => $movie, 'episodeNumber' => $prevEpisode->number]) }}"
                                       wire:navigate
                                       class="movie-watch__nav-button movie-watch__nav-button--prev">
                                        <i class="fas fa-chevron-left"></i>
                                        <span class="movie-watch__nav-text">
                                            <small>Попередній</small>
                                            <span>Епізод {{ $prevEpisode->number }}</span>
                                        </span>
                                    </a>
                                @endif

                                <div class="movie-watch__episode-selector">
                                    <select onchange="window.location.href = this.value"
                                            class="movie-watch__episode-select">
                                        @foreach($movie->episodes as $ep)
                                            <option
                                                value="{{ route('movies.watch.episode', ['movie' => $movie, 'episodeNumber' => $ep->number]) }}"
                                                {{ $ep->id === $episode->id ? 'selected' : '' }} wire:navigate
                                            >
                                                Епізод {{ $ep->number }}: {{ $ep->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if($nextEpisode)
                                    <a href="{{ route('movies.watch.episode', ['movie' => $movie, 'episodeNumber' => $nextEpisode->number]) }}"
                                       wire:navigate
                                       class="movie-watch__nav-button movie-watch__nav-button--next">
                                        <span class="movie-watch__nav-text">
                                            <small>Наступний</small>
                                            <span>Епізод {{ $nextEpisode->number }}</span>
                                        </span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($episode->duration)
                        <div class="movie-watch__episode-duration">
                            <i class="fas fa-clock"></i> {{ $episode->duration }} хв
                        </div>
                    @endif

                    @if($episode->description)
                        <div class="movie-watch__episode-description">
                            {!! $episode->description !!}
                        </div>
                    @endif
                </div>

                <!-- Форма створення кімнати -->
                <div class="movie-watch__create-room">
                    <h3>Створити кімнату для спільного перегляду</h3>

                    @if (session()->has('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit="createRoom" class="movie-watch__room-form">
                        <div class="movie-watch__form-group">
                            <label for="maxViewers">Максимальна кількість глядачів:</label>
                            <input type="number" id="maxViewers" wire:model="maxViewers" min="1"
                                   max="50" required>
                            @error('maxViewers') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <div class="movie-watch__form-group">
                            <label>
                                <input type="checkbox" wire:model="isPrivate">
                                Приватна кімната
                            </label>
                        </div>

                        @if($isPrivate)
                            <div class="movie-watch__form-group">
                                <label for="roomPassword">Пароль кімнати:</label>
                                <input type="password" id="roomPassword" wire:model="roomPassword"
                                       required>
                                @error('roomPassword') <span
                                    class="error">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <button type="submit" class="movie-watch__create-room-btn">
                            <i class="fas fa-plus"></i> Створити кімнату
                        </button>
                    </form>
                </div>

                @if($movie->episodes && $movie->episodes->count() > 1)
                    <div class="movie-watch__episodes-list">
                        <h3 class="movie-watch__episodes-title">Всі епізоди</h3>
                        <div class="movie-watch__episodes-grid">
                            @foreach($movie->episodes as $ep)
                                <a
                                    href="{{ route('movies.watch.episode', ['movie' => $movie, 'episodeNumber' => $ep->number]) }}"
                                    wire:navigate
                                    class="movie-watch__episode-item {{ $ep->id === $episode->id ? 'movie-watch__episode-item--active' : '' }}"
                                >
                                    <span
                                        class="movie-watch__episode-number">{{ $ep->number ?: 'Без номера' }}</span>
                                    <span class="movie-watch__episode-name">{{ $ep->name }}</span>
                                    @if($ep->duration)
                                        <span class="movie-watch__episode-item-duration">{{ $ep->duration }} хв</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="movie-watch__placeholder">
                    <p>Епізод не знайдено</p>
                </div>
            @endif
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>

<script>
    function changeVideoSource(url, button) {
        const videoPlayer = document.querySelector('.movie-watch__video');
        if (videoPlayer) {
            videoPlayer.src = url;
            videoPlayer.load();
            videoPlayer.play();

            document.querySelectorAll('.movie-watch__player-button').forEach(btn => {
                btn.classList.remove('movie-watch__player-button--active');
            });
            button.classList.add('movie-watch__player-button--active');
        }
    }
</script>
