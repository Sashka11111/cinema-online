<div class="available-rooms">
    @auth
        <div class="available-rooms__container">
            <button wire:click="toggleDropdown" class="available-rooms__toggle">
                <i class="fas fa-users"></i>
                <span>Кімнати</span>
                @if(count($rooms) > 0)
                    <span class="available-rooms__badge">{{ count($rooms) }}</span>
                @endif
                <i class="fas fa-chevron-down available-rooms__arrow {{ $showDropdown ? 'available-rooms__arrow--open' : '' }}"></i>
            </button>

            @if($showDropdown)
                <div class="available-rooms__dropdown">
                    <div class="available-rooms__header">
                        <h4>Доступні кімнати</h4>
                        <button wire:click="loadRooms" class="available-rooms__refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>

                    @if(count($rooms) > 0)
                        <div class="available-rooms__list">
                            @foreach($rooms as $room)
                                <div class="available-rooms__item {{ $room['is_full'] ? 'available-rooms__item--full' : '' }}">
                                    <div class="available-rooms__info">
                                        <div class="available-rooms__name">
                                            {{ $room['name'] }}
                                            @if($room['is_private'])
                                                <i class="fas fa-lock available-rooms__private-icon" title="Приватна кімната"></i>
                                            @endif
                                            @if($room['is_owner'])
                                                <span class="available-rooms__owner-badge">Ваша</span>
                                            @endif
                                        </div>
                                        <div class="available-rooms__details">
                                            <span class="available-rooms__movie">{{ $room['movie_name'] }}</span>
                                            @if($room['episode_number'])
                                                <span class="available-rooms__episode">Епізод {{ $room['episode_number'] }}</span>
                                            @endif
                                        </div>
                                        <div class="available-rooms__meta">
                                            <span class="available-rooms__owner">{{ $room['owner_name'] }}</span>
                                            <span class="available-rooms__viewers">
                                                <i class="fas fa-eye"></i>
                                                {{ $room['viewers_count'] }}/{{ $room['max_viewers'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="available-rooms__actions">
                                        @if($room['is_full'] && !$room['is_owner'])
                                            <span class="available-rooms__full-text">Заповнена</span>
                                        @else
                                            <button wire:click="joinRoom('{{ $room['id'] }}')" 
                                                    class="available-rooms__join-btn">
                                                <i class="fas fa-sign-in-alt"></i>
                                                Увійти
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="available-rooms__empty">
                            <i class="fas fa-users-slash"></i>
                            <p>Немає доступних кімнат</p>
                            <small>Створіть свою кімнату під час перегляду фільму</small>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Модальне вікно для введення пароля -->
        @if($showPasswordModal)
            <div class="available-rooms__modal-overlay" wire:click="closePasswordModal">
                <div class="available-rooms__modal" wire:click.stop>
                    <div class="available-rooms__modal-header">
                        <h3>Введіть пароль</h3>
                        <button wire:click="closePasswordModal" class="available-rooms__modal-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="available-rooms__modal-body">
                        <p>Ця кімната захищена паролем</p>
                        <input type="password" 
                               wire:model="password" 
                               wire:keydown.enter="joinWithPassword"
                               placeholder="Введіть пароль"
                               class="available-rooms__password-input"
                               autofocus>
                    </div>
                    <div class="available-rooms__modal-footer">
                        <button wire:click="closePasswordModal" class="available-rooms__modal-btn available-rooms__modal-btn--cancel">
                            Скасувати
                        </button>
                        <button wire:click="joinWithPassword" class="available-rooms__modal-btn available-rooms__modal-btn--primary">
                            Увійти
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>
