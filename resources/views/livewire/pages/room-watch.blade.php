<div class="room-watch">
    <livewire:components.header-component/>

    <main class="room-watch__main">


        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Фільми', 'route' => 'movies'],
                ['label' => $movie->name, 'route' => 'movies.show', 'params' => ['movie' => $movie]],
                ['label' => 'Кімната', 'active' => true]
            ]"/>



            @if($room)
                <div class="room-watch__info">
                    <div class="room-watch__info-header">
                        <h2 class="room-watch__info-title">{{ $room->name }}</h2>
                        <div class="room-watch__info-badges">
                            @if($room->is_private)
                                <span class="room-watch__badge room-watch__badge--private">
                                    <i class="fas fa-lock"></i> Приватна кімната
                                </span>
                            @endif
                            <span class="room-watch__badge room-watch__badge--active">
                                <i class="fas fa-play"></i> Активна
                            </span>
                        </div>
                    </div>

                    <div class="room-watch__details">
                        <div class="room-watch__detail">
                            <i class="fas fa-user"></i>
                            <span>Власник: {{ $room->user->name }}</span>
                        </div>
                        <div class="room-watch__detail">
                            <i class="fas fa-eye"></i>
                            <span>Глядачів: {{ $room->getActiveViewersCount() }}/{{ $room->max_viewers }}</span>
                        </div>
                        <div class="room-watch__detail">
                            <i class="fas fa-clock"></i>
                            <span>Створено: {{ $room->created_at->diffForHumans() }}</span>
                        </div>
                        @if($room->started_at)
                            <div class="room-watch__detail">
                                <i class="fas fa-play-circle"></i>
                                <span>Розпочато: {{ $room->started_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>

                    @if($room->activeViewers && $room->activeViewers->count() > 0)
                        <div class="room-watch__viewers">
                            <h4>
                                <i class="fas fa-users"></i>
                                Зараз дивляться ({{ $room->activeViewers->count() }}):
                            </h4>
                            <div class="room-watch__viewers-list">
                                @foreach($room->activeViewers as $viewer)
                                    <span class="room-watch__viewer {{ $viewer->id === auth()->id() ? 'room-watch__viewer--you' : '' }}">
                                        @if($viewer->id === $room->user_id)
                                            <i class="fas fa-crown" title="Власник кімнати"></i>
                                        @endif
                                        {{ $viewer->name }}
                                        @if($viewer->id === auth()->id())
                                            (Ви)
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Video Player Section - moved after room info -->
                @if($episode)
                    <!-- Video Source Selection - above player -->
                    @if($episode->video_players && $episode->video_players->isNotEmpty() && $episode->video_players->count() > 1)
                        <div class="room-watch__source-selector">
                            <div class="room-watch__source-buttons">
                                @foreach($episode->video_players as $index => $player)
                                    <button
                                        class="room-watch__source-button {{ $loop->first ? 'room-watch__source-button--active' : '' }}"
                                        onclick="changeVideoSource('{{ asset('storage/' . ($player['file_url'] ?? '')) }}', this, {{ $index }})"
                                        data-player-index="{{ $index }}"
                                        data-player-name="{{ isset($player['name']) ? __('video_player_name.' . $player['name']) : __('room_watch.player.default_player_name', ['number' => $index + 1]) }}"
                                        title="{{ isset($player['name']) ? __('video_player_name.' . $player['name']) : __('room_watch.player.default_player_name', ['number' => $index + 1]) }}{{ isset($player['quality']) ? ' - ' . __('video_quality.' . $player['quality']) : '' }}"
                                    >
                                        @if(isset($player['name']))
                                            {{ __('video_player_name.' . $player['name']) }}
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                        @if(isset($player['quality']))
                                            <span class="room-watch__source-quality">{{ __('video_quality.' . $player['quality']) }}</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

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

                            <!-- Action buttons - separate from player selection -->
                            <div class="room-watch__actions">
                                @if($room)
                                    <button onclick="openInviteModal()" class="room-watch__player-button room-watch__action-button room-watch__action-button--invite">
                                        <div class="room-watch__player-button-content">
                                            <span class="room-watch__player-button-name">
                                                <i class="fas fa-user-plus"></i> Запросити друзів
                                            </span>
                                        </div>
                                    </button>

                                    <button wire:click="leaveRoom" class="room-watch__player-button room-watch__action-button room-watch__action-button--leave">
                                        <div class="room-watch__player-button-content">
                                            <span class="room-watch__player-button-name">
                                                <i class="fas fa-sign-out-alt"></i> Покинути кімнату
                                            </span>
                                        </div>
                                    </button>
                                @endif

                                <a href="{{ route('movies.show', ['movie' => $movie->slug]) }}"
                                   wire:navigate
                                   class="room-watch__player-button room-watch__action-button room-watch__action-button--back">
                                    <div class="room-watch__player-button-content">
                                        <span class="room-watch__player-button-name">
                                            <i class="fas fa-arrow-left"></i> Перейти до фільма
                                        </span>
                                    </div>
                                </a>
                            </div>
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
                                       wire:navigate
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
            @endif



            <div class="room-watch__movie-info">
                <div class="room-watch__movie-header">
                    <h3>{{ $movie->name }}</h3>
                    <div class="room-watch__movie-meta">
                        @if($movie->duration)
                            <span class="room-watch__meta-item">
                                    <i class="fas fa-clock"></i>
                                    {{ $movie->duration }} хв
                                </span>
                        @endif
                    </div>
                </div>

                @if($movie->tags && $movie->tags->count() > 0)
                    <div class="room-watch__genres">
                        <strong>Теги:</strong>
                        @foreach($movie->tags as $tag)
                            <span class="room-watch__genre">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                @if($episode && $episode->name)
                    <div class="room-watch__episode-info">
                        <strong>Епізод {{ $episode->number }}:</strong> {{ $episode->name }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>

    <!-- Модальне вікно запрошення -->
    @if($room)
    <div id="inviteModal" class="room-watch__invite-overlay" style="display: none;" onclick="closeInviteModal()">
        <div class="room-watch__invite-modal" wire:click.stop>
            <div class="room-watch__invite-header">
                <h3 class="room-watch__invite-title">
                    <i class="fas fa-user-plus"></i>
                    Запросити друзів до кімнати
                </h3>
                <button onclick="closeInviteModal()" class="room-watch__invite-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="room-watch__invite-body">
                <!-- Інформація про кімнату -->
                <div class="room-watch__invite-section">
                    <div class="room-watch__room-info">
                        <h4 class="room-watch__room-title">{{ $room->name }}</h4>
                        <div class="room-watch__room-details">
                            <span class="room-watch__room-detail">
                                <i class="fas fa-film"></i>
                                {{ $movie->name }}
                                @if($episode->name)
                                    - Епізод {{ $episode->number }}: {{ $episode->name }}
                                @else
                                    - Епізод {{ $episode->number }}
                                @endif
                            </span>
                            <span class="room-watch__room-detail">
                                <i class="fas fa-users"></i>
                                {{ $room->activeViewers->count() }}/{{ $room->max_viewers }} глядачів
                            </span>
                            @if($room->is_private)
                                <span class="room-watch__room-detail room-watch__room-detail--private">
                                    <i class="fas fa-lock"></i>
                                    Приватна кімната
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="room-watch__invite-section">
                    <label class="room-watch__invite-label">
                        <i class="fas fa-link"></i>
                        Посилання для запрошення
                    </label>
                    <div class="room-watch__invite-link-container">
                        <input
                            type="text"
                            id="inviteLink"
                            value="{{ $inviteLink }}"
                            readonly
                            class="room-watch__invite-input"
                        >
                        <button onclick="copyInviteLink()" class="room-watch__invite-copy-btn">
                            <i class="fas fa-copy"></i>
                            Копіювати
                        </button>
                        <button onclick="generateQRCode()" class="room-watch__invite-qr-btn">
                            <i class="fas fa-qrcode"></i>
                            QR-код
                        </button>
                    </div>
                    <p class="room-watch__invite-hint">
                        Поділіться цим посиланням з друзями, щоб вони могли приєднатися до кімнати
                    </p>

                    <!-- QR код контейнер -->
                    <div id="qrCodeContainer" class="room-watch__qr-container" style="display: none;">
                        <div class="room-watch__qr-code" id="qrCode"></div>
                        <p class="room-watch__qr-hint">Відскануйте QR-код для швидкого переходу</p>
                    </div>
                </div>

                @if($room->is_private)
                    <div class="room-watch__invite-section">
                        <label class="room-watch__invite-label">
                            <i class="fas fa-key"></i>
                            Пароль кімнати
                        </label>
                        <div class="room-watch__invite-password-container">
                            <input
                                type="text"
                                id="roomPassword"
                                value="{{ $roomPassword }}"
                                readonly
                                class="room-watch__invite-input"
                            >
                            <button onclick="copyRoomPassword()" class="room-watch__invite-copy-btn">
                                <i class="fas fa-copy"></i>
                                Копіювати
                            </button>
                        </div>
                        <p class="room-watch__invite-hint">
                            Друзі повинні ввести цей пароль для входу в приватну кімнату
                        </p>
                    </div>
                @endif

                <div class="room-watch__invite-section">
                    <label class="room-watch__invite-label">
                        <i class="fas fa-share-alt"></i>
                        Поділитися через соціальні мережі
                    </label>
                    <div class="room-watch__invite-social">
                        <button onclick="shareToTelegram()" class="room-watch__invite-social-btn room-watch__invite-social-btn--telegram">
                            <i class="fab fa-telegram"></i>
                            Telegram
                        </button>
                        <button onclick="shareToViber()" class="room-watch__invite-social-btn room-watch__invite-social-btn--viber">
                            <i class="fab fa-viber"></i>
                            Viber
                        </button>
                        <button onclick="shareToWhatsApp()" class="room-watch__invite-social-btn room-watch__invite-social-btn--whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </button>
                    </div>
                </div>
            </div>

            <div class="room-watch__invite-actions">
                <button onclick="copyAllInviteInfo()" class="room-watch__invite-btn room-watch__invite-btn--copy-all">
                    <i class="fas fa-clipboard"></i>
                    Копіювати все
                </button>
                <button onclick="closeInviteModal()" class="room-watch__invite-btn room-watch__invite-btn--close">
                    <i class="fas fa-times"></i>
                    Закрити
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Модальне вікно для введення пароля приватної кімнати -->
    @if($showPasswordModal)
    <div class="room-watch__password-overlay">
        <div class="room-watch__password-modal" wire:click.stop>
            <div class="room-watch__password-header">
                <h3 class="room-watch__password-title">
                    <i class="fas fa-lock"></i>
                    Приватна кімната
                </h3>
            </div>

            <div class="room-watch__password-body">
                <p class="room-watch__password-description">
                    Ця кімната захищена паролем. Введіть пароль для входу.
                </p>

                @if (session()->has('error'))
                    <div class="room-watch__password-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="room-watch__password-input-container">
                    <label for="passwordInput" class="room-watch__password-label">
                        <i class="fas fa-key"></i>
                        Пароль кімнати
                    </label>
                    <input
                        type="password"
                        id="passwordInput"
                        wire:model="passwordInput"
                        wire:keydown.enter="submitPassword"
                        placeholder="Введіть пароль"
                        class="room-watch__password-input"
                        autofocus
                    >
                </div>
            </div>

            <div class="room-watch__password-actions">
                <button wire:click="submitPassword" class="room-watch__password-btn room-watch__password-btn--submit">
                    <i class="fas fa-sign-in-alt"></i>
                    Увійти
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($room)
        <script>
            // Передача даних для JavaScript модуля
            window.roomData = {
                movieSlug: '{{ $movie->slug }}',
                episodeNumber: '{{ $episode->number }}',
                roomSlug: '{{ $room->slug }}',
                movieName: '{{ addslashes($movie->name) }}',
                roomName: '{{ addslashes($room->name) }}'
            };
        </script>
    @endif
</div>

@vite(['resources/js/room-echo.js', 'resources/js/room-video-sync.js', 'resources/js/room-video-player.js'])
