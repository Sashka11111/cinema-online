<div class="room-watch">
    <livewire:components.header-component/>

    <main class="room-watch__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Фільми', 'route' => 'movies'],
                ['label' => $movie->name, 'active' => true],
                ['label' => 'Кімната', 'active' => true]
            ]"/>

            <h1 class="room-watch__title">Кімната: {{ $movie->name }}</h1>

            @if($room)
                <div class="room-watch__info">
                    <h2>{{ $room->name }}</h2>
                    <p>Глядачів: {{ $room->viewers()->wherePivot('left_at', null)->count() }}
                        /{{ $room->max_viewers }}</p>
                    @if($room->is_private)
                        <span class="room-watch__private-badge">🔒 Приватна кімната</span>
                    @endif
                </div>
            @endif

            @if($episode)
                <div class="room-watch__player-container">
                    @if($episode->video_players && $episode->video_players->isNotEmpty())
                        <div class="room-watch__player">
                            @php
                                $firstPlayer = $episode->video_players->first();
                                $videoUrl = $firstPlayer['file_url'] ?? '';
                            @endphp

                            <video
                                id="videoPlayer"
                                src="{{ asset('storage/' . $videoUrl) }}"
                                class="room-watch__video"
                                controls
                                controlsList="nodownload"
                                data-episode-slug="{{ $episode->slug }}"
                                data-room-slug="{{ $room->slug }}"
                            ></video>
                        </div>

                        @if($episode->video_players->count() > 1)
                            <div class="room-watch__player-selection">
                                <h3>Вибрати джерело відео:</h3>
                                <div class="room-watch__player-buttons">
                                    @foreach($episode->video_players as $index => $player)
                                        <button
                                            onclick="changeVideoSource('{{ asset('storage/' . $player['file_url']) }}', this)"
                                            class="room-watch__player-button @if($index === 0) room-watch__player-button--active @endif"
                                            data-player-index="{{ $index }}"
                                        >
                                            Джерело {{ $index + 1 }}
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

                        <div class="room-watch__player">
                            <video
                                id="videoPlayer"
                                src="{{ asset('storage/' . $videoUrl) }}"
                                class="room-watch__video"
                                controls
                                controlsList="nodownload"
                                data-episode-slug="{{ $episode->slug }}"
                                data-room-slug="{{ $room->slug }}"
                            ></video>
                        </div>
                    @else
                        <div class="room-watch__placeholder">
                            <p>Відео недоступне для перегляду</p>
                            <p class="room-watch__placeholder-hint">Спробуйте додати відео через
                                адмін-панель</p>
                        </div>
                    @endif
                </div>

                @if($movie->episodes && $movie->episodes->count() > 1)
                    <div class="room-watch__episodes">
                        <h3>Епізоди:</h3>
                        <div class="room-watch__episodes-list">
                            @foreach($movie->episodes->sortBy('number') as $ep)
                                <a href="{{ route('movies.watch.episode', ['movie' => $movie, 'episodeNumber' => $ep->number]) }}"
                                   class="room-watch__episode-link @if($ep->id === $episode->id) room-watch__episode-link--active @endif">
                                    Епізод {{ $ep->number }}
                                    @if($ep->name)
                                        - {{ $ep->name }}
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="room-watch__placeholder">
                    <p>Епізод не знайдено</p>
                </div>
            @endif

            <div class="room-watch__actions">
                @if($room)
                    <button wire:click="leaveRoom" class="room-watch__leave-button">
                        <i class="fas fa-sign-out-alt"></i> Покинути кімнату
                    </button>
                @endif
                <a href="{{ route('movies.show', ['movie' => $movie->id]) }}"
                   class="room-watch__back-button">
                    <i class="fas fa-arrow-left"></i> Повернутися до фільму
                </a>
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>

@vite(['resources/js/room-echo.js'])

@script
<script>
    const video = document.getElementById('videoPlayer');
    const roomSlug = video?.dataset.roomSlug;

    // Video source change function
    window.changeVideoSource = function(url, button) {
        if (!video) return;
        video.src = url;
        video.load();

        document.querySelectorAll('.room-watch__player-button').forEach(btn => {
            btn.classList.remove('room-watch__player-button--active');
        });
        button.classList.add('room-watch__player-button--active');
    };

    if (video && roomSlug && window.Echo) {
        let ignoreNextEvent = false;

        // Listen for sync events
        Echo.channel(`room.${roomSlug}`)
            .listen('.VideoSyncEvent', (e) => {
                console.log('🎬 Received VideoSyncEvent:', e.action, 'time:', e.data?.currentTime);

                ignoreNextEvent = true;

                if (e.action === 'play') {
                    console.log('▶️ Playing video from sync');
                    video.currentTime = e.data?.currentTime || video.currentTime;
                    video.play();
                } else if (e.action === 'pause') {
                    console.log('⏸️ Pausing video from sync');
                    video.currentTime = e.data?.currentTime || video.currentTime;
                    video.pause();
                }

                setTimeout(() => {
                    ignoreNextEvent = false;
                }, 200);
            });

        // Send sync events
        video.addEventListener('play', () => {
            if (!ignoreNextEvent) {
                console.log('📤 Sending PLAY event, time:', video.currentTime);
                $wire.dispatch('sync-video', {
                    action: 'play',
                    data: {currentTime: video.currentTime}
                });
            } else {
                console.log('🔇 Ignoring PLAY event (from sync)');
            }
        });

        video.addEventListener('pause', () => {
            if (!ignoreNextEvent) {
                console.log('📤 Sending PAUSE event, time:', video.currentTime);
                $wire.dispatch('sync-video', {
                    action: 'pause',
                    data: {currentTime: video.currentTime}
                });
            } else {
                console.log('🔇 Ignoring PAUSE event (from sync)');
            }
        });
    }
</script>
@endscript
