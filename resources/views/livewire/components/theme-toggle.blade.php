<div class="theme-toggle">
    <button
        wire:click="toggleTheme"
        class="theme-toggle__button"
        aria-label="Перемкнути тему"
    >
        @if($theme === 'dark')
            <div class="theme-toggle__icon--sun"></div>
        @else
            <div class="theme-toggle__icon--moon"></div>
        @endif
    </button>
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        // Встановлюємо тему при завантаженні сторінки
        const theme = "{{ $theme }}";
        document.documentElement.setAttribute('data-theme', theme);

        // Слухаємо зміни теми
        Livewire.on('themeChanged', (data) => {
            document.documentElement.setAttribute('data-theme', data.theme);
            localStorage.setItem('theme', data.theme);
        });
    });
</script>
