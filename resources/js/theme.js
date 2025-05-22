document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing theme');
    const theme = localStorage.getItem('theme')
        || document.documentElement.getAttribute('data-theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
    console.log('Initial theme set:', theme);
});

document.addEventListener('livewire:init', () => {
    console.log('Livewire initialized');
    Livewire.on('theme-changed', (event) => {
        console.log('Theme changed event received:', event);
        document.documentElement.setAttribute('data-theme', event.theme);
        localStorage.setItem('theme', event.theme);
        // Примусово оновлюємо DOM
        document.body.classList.toggle('dark-theme', event.theme === 'dark');
    });
});
