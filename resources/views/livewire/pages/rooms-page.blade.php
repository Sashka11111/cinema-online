<div class="rooms-page">
    <livewire:components.header-component/>

    <main class="rooms-page__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Кімнати', 'active' => true]
            ]"/>

            <div class="rooms-page__header">
                <h1 class="rooms-page__title">Кімнати для спільного перегляду</h1>
                <p class="rooms-page__subtitle">Приєднуйтесь до активних кімнат або створіть свою під час перегляду фільму</p>
            </div>

            <div class="rooms-page__filters">
                <div class="rooms-page__filter-tabs">
                    <button wire:click="$set('filterType', 'all')"
                            class="rooms-page__filter-tab {{ $filterType === 'all' ? 'rooms-page__filter-tab--active' : '' }}">
                        Всі кімнати
                    </button>
                    <button wire:click="$set('filterType', 'public')"
                            class="rooms-page__filter-tab {{ $filterType === 'public' ? 'rooms-page__filter-tab--active' : '' }}">
                        Публічні
                    </button>
                    <button wire:click="$set('filterType', 'private')"
                            class="rooms-page__filter-tab {{ $filterType === 'private' ? 'rooms-page__filter-tab--active' : '' }}">
                        Мої приватні
                    </button>
                    <button wire:click="$set('filterType', 'my')"
                            class="rooms-page__filter-tab {{ $filterType === 'my' ? 'rooms-page__filter-tab--active' : '' }}">
                        Мої кімнати
                    </button>
                </div>
            </div>

            @if($rooms->count() > 0)
                <div class="rooms-page__grid">
                    @foreach($rooms as $room)
                        <div class="room-card {{ $room->isFull() ? 'room-card--full' : '' }}">
                            <div class="room-card__header">
                                <h3 class="room-card__name">
                                    {{ $room->name }}
                                    @if($room->is_private)
                                        <i class="fas fa-lock room-card__private-icon" title="Приватна кімната"></i>
                                    @endif
                                    @if($room->user_id === auth()->id())
                                        <span class="room-card__owner-badge">Ваша</span>
                                    @endif
                                </h3>
                                <div class="room-card__status">
                                    @if($room->isFull())
                                        <span class="room-card__status-badge room-card__status-badge--full">Заповнена</span>
                                    @else
                                        <span class="room-card__status-badge room-card__status-badge--active">Активна</span>
                                    @endif
                                </div>
                            </div>

                            <div class="room-card__content">
                                <div class="room-card__movie">
                                    <h4 class="room-card__movie-title">{{ $room->episode->movie->name ?? 'Невідомий фільм' }}</h4>
                                    @if($room->episode->number)
                                        <span class="room-card__episode">Епізод {{ $room->episode->number }}</span>
                                    @endif
                                </div>

                                <div class="room-card__meta">
                                    <div class="room-card__owner">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $room->user->name }}</span>
                                    </div>
                                    <div class="room-card__viewers">
                                        <i class="fas fa-eye"></i>
                                        <span>{{ $room->getActiveViewersCount() }}/{{ $room->max_viewers }}</span>
                                    </div>
                                    <div class="room-card__time">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $room->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="room-card__actions">
                                @if($room->isFull() && $room->user_id !== auth()->id())
                                    <button class="room-card__btn room-card__btn--disabled" disabled>
                                        <i class="fas fa-users"></i>
                                        Заповнена
                                    </button>
                                @else
                                    <button wire:click="joinRoom('{{ $room->id }}')"
                                            class="room-card__btn room-card__btn--primary">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Увійти в кімнату
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="content-page__pagination">
                    {{ $rooms->links('livewire.components.pagination') }}
                </div>
            @else
                <div class="rooms-page__empty">
                    <div class="rooms-page__empty-icon">
                        <i class="fas fa-users-slash"></i>
                    </div>
                    <h3 class="rooms-page__empty-title">
                        Немає доступних кімнат
                    </h3>
                    <p class="rooms-page__empty-text">
                        Створіть свою кімнату під час перегляду фільму або серіалу
                    </p>

                </div>
            @endif
        </div>
    </main>

    <!-- Модальне вікно для введення пароля -->
    @if($showPasswordModal)
        <div class="rooms-page__modal-overlay" wire:click="closePasswordModal">
            <div class="rooms-page__modal" wire:click.stop>
                <div class="rooms-page__modal-header">
                    <h3>Введіть пароль</h3>
                    <button wire:click="closePasswordModal" class="rooms-page__modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="rooms-page__modal-body">
                    <p>Ця кімната захищена паролем</p>
                    <input type="password"
                           wire:model="password"
                           wire:keydown.enter="joinWithPassword"
                           placeholder="Введіть пароль"
                           class="rooms-page__password-input"
                           autofocus>
                </div>
                <div class="rooms-page__modal-footer">
                    <button wire:click="closePasswordModal" class="rooms-page__modal-btn rooms-page__modal-btn--cancel">
                        Скасувати
                    </button>
                    <button wire:click="joinWithPassword" class="rooms-page__modal-btn rooms-page__modal-btn--primary">
                        Увійти
                    </button>
                </div>
            </div>
        </div>
    @endif

    <livewire:components.main-footer-component/>
</div>
