<div>
    <!-- Кнопка створення кімнати -->
    <div class="room-creation-modal__trigger">
        <button wire:click="openModal" class="room-creation-modal__btn">
            Створити кімнату для спільного перегляду
        </button>
    </div>

    <!-- Модальне вікно -->
    @if($showModal)
        <div class="room-creation-modal__overlay" wire:click="closeModal">
            <div class="room-creation-modal__modal" wire:click.stop>
                <div class="room-creation-modal__header">
                    <h3 class="room-creation-modal__title">
                        <i class="fas fa-users"></i>
                        Створити кімнату для спільного перегляду
                    </h3>
                    <button wire:click="closeModal" class="room-creation-modal__close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="room-creation-modal__body">
                    @if (session()->has('error'))
                        <div class="room-creation-modal__alert room-creation-modal__alert--error">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit="createRoom" class="room-creation-modal__form">
                        <div class="room-creation-modal__form-group">
                            <label for="maxViewers" class="room-creation-modal__form-label">
                                <i class="fas fa-eye"></i>
                                Максимальна кількість глядачів
                            </label>
                            <input
                                type="number"
                                id="maxViewers"
                                wire:model="maxViewers"
                                min="1"
                                max="10"
                                required
                                class="room-creation-modal__form-input"
                            >
                            @error('maxViewers')
                                <span class="room-creation-modal__form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="room-creation-modal__form-group">
                            <label class="room-creation-modal__form-checkbox">
                                <input
                                    type="checkbox"
                                    wire:model="isPrivate"
                                    class="room-creation-modal__checkbox-input"
                                    id="isPrivateCheckbox"
                                    onchange="togglePasswordField(this.checked)"
                                >
                                <span class="room-creation-modal__checkbox-custom"></span>
                                <span class="room-creation-modal__checkbox-label">
                                    <i class="fas fa-lock"></i>
                                    Приватна кімната
                                </span>
                            </label>
                        </div>

                        <div class="room-creation-modal__form-group room-creation-modal__form-group--password" id="passwordField" style="display: {{ $isPrivate ? 'flex' : 'none' }};">
                            <label for="roomPassword" class="room-creation-modal__form-label">
                                <i class="fas fa-key"></i>
                                Пароль кімнати
                            </label>
                            <input
                                type="password"
                                id="roomPassword"
                                wire:model="roomPassword"
                                {{ $isPrivate ? 'required' : '' }}
                                class="room-creation-modal__form-input"
                                placeholder="Введіть пароль для кімнати"
                            >
                            @error('roomPassword')
                                <span class="room-creation-modal__form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="room-creation-modal__actions">
                            <button type="button" wire:click="closeModal" class="room-creation-modal__btn-secondary">
                                <i class="fas fa-times"></i>
                                Скасувати
                            </button>
                            <button type="submit" class="room-creation-modal__btn-primary">
                                <i class="fas fa-plus"></i>
                                Створити кімнату
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Toggle password field instantly
    function togglePasswordField(isChecked) {
        const passwordField = document.getElementById('passwordField');
        const passwordInput = document.getElementById('roomPassword');

        if (isChecked) {
            // Show field with animation
            passwordField.style.display = 'flex';
            passwordInput.setAttribute('required', 'required');

            // Trigger reflow to ensure display change is applied
            passwordField.offsetHeight;

            // Add animation class and focus
            passwordField.classList.add('room-creation-modal__form-group--animated');
            setTimeout(() => {
                passwordInput.focus();
            }, 150);
        } else {
            // Hide field
            passwordField.classList.remove('room-creation-modal__form-group--animated');
            passwordInput.removeAttribute('required');
            passwordInput.value = '';

            // Clear Livewire model
            @this.set('roomPassword', '');

            // Hide after animation
            setTimeout(() => {
                passwordField.style.display = 'none';
            }, 300);
        }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.querySelector('.room-creation-modal__overlay');
            if (modal) {
                @this.call('closeModal');
            }
        }
    });

    // Listen for modal reset event
    document.addEventListener('livewire:init', function() {
        Livewire.on('resetModalForm', function() {
            const passwordField = document.getElementById('passwordField');
            const passwordInput = document.getElementById('roomPassword');
            const checkbox = document.getElementById('isPrivateCheckbox');

            if (passwordField && passwordInput && checkbox) {
                passwordField.style.display = 'none';
                passwordInput.removeAttribute('required');
                passwordInput.value = '';
                checkbox.checked = false;
                passwordField.classList.remove('room-creation-modal__form-group--animated');
            }
        });
    });
</script>
