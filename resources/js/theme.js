// Функція для застосування теми
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    document.body.classList.toggle('dark-theme', theme === 'dark');
    localStorage.setItem('theme', theme);
    console.log('Theme applied:', theme);
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing theme');
    // Пріоритет: localStorage > data-theme атрибут > 'dark' (за замовчуванням)
    const theme = localStorage.getItem('theme')
        || document.documentElement.getAttribute('data-theme')
        || 'dark';

    applyTheme(theme);
});

document.addEventListener('livewire:init', () => {
    console.log('Livewire initialized');
    Livewire.on('theme-changed', (event) => {
        console.log('Theme changed event received:', event);
        applyTheme(event.theme);

        // Додаємо подію для інших компонентів
        document.dispatchEvent(new CustomEvent('theme-update', {
            detail: { theme: event.theme }
        }));
    });
});
