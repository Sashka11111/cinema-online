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
                <!-- Video Source Selection - above player -->
                @if($episode->video_players && $episode->video_players->isNotEmpty() && $episode->video_players->count() > 1)
                    <div class="movie-watch__source-selector">
                        <div class="movie-watch__source-buttons">
                            @foreach($episode->video_players as $index => $player)
                                <button
                                    class="movie-watch__source-button {{ $loop->first ? 'movie-watch__source-button--active' : '' }}"
                                    onclick="changeVideoSource('{{ asset('storage/' . ($player['file_url'] ?? '')) }}', this, {{ $index }})"
                                    data-player-index="{{ $index }}"
                                    data-player-name="{{ isset($player['name']) ? __('video_player_name.' . $player['name']) : __('movie_watch.player.default_player_name', ['number' => $index + 1]) }}"
                                    title="{{ isset($player['name']) ? __('video_player_name.' . $player['name']) : __('movie_watch.player.default_player_name', ['number' => $index + 1]) }}{{ isset($player['quality']) ? ' - ' . __('video_quality.' . $player['quality']) : '' }}"
                                >
                                    @if(isset($player['name']))
                                        {{ __('video_player_name.' . $player['name']) }}
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                    @if(isset($player['quality']))
                                        <span class="movie-watch__source-quality">{{ __('video_quality.' . $player['quality']) }}</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

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

                <!-- Компонент створення кімнати -->
                <livewire:components.room-creation-modal :movie="$movie" :episode="$episode" />

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
    function changeVideoSource(url, button, playerIndex) {
        const videoPlayer = document.querySelector('.movie-watch__video');
        if (videoPlayer) {
            // Store current time
            const currentTime = videoPlayer.currentTime;

            // Change source
            videoPlayer.src = url;
            videoPlayer.load();

            // Restore time and play
            videoPlayer.addEventListener('loadedmetadata', function() {
                if (currentTime > 0) {
                    videoPlayer.currentTime = currentTime;
                }
                videoPlayer.play();
            }, { once: true });

            // Update active button
            document.querySelectorAll('.movie-watch__source-button').forEach(btn => {
                btn.classList.remove('movie-watch__source-button--active');
            });
            button.classList.add('movie-watch__source-button--active');
        }
    }

    // Initialize video player when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const videoPlayer = document.getElementById('videoPlayer');
        if (videoPlayer) {
            const firstButton = document.querySelector('.movie-watch__source-button');
            if (firstButton && !firstButton.classList.contains('movie-watch__source-button--active')) {
                firstButton.classList.add('movie-watch__source-button--active');
            }
        }
    });


</script>
