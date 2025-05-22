function initPromoSlider() {
    const track = document.getElementById('promoTrack');
    const prevBtn = document.getElementById('promoPrev');
    const nextBtn = document.getElementById('promoNext');
    const promoSection = document.querySelector('.home-promo');

    if (!track || !prevBtn || !nextBtn || !promoSection) {
        return;
    }

    const items = track.querySelectorAll('.home-promo__featured-card');
    if (items.length === 0) {
        return;
    }

    let itemWidth = items[0].offsetWidth + 16;
    const carousel = track.parentElement;
    let visibleItems = 1;
    let maxScroll = Math.max(0, items.length - visibleItems);
    let currentPosition = 0;

    function updateBackground() {
        const activeItem = items[currentPosition];
        const backgroundUrl = activeItem.getAttribute('data-poster'); // Changed to data-poster
        if (backgroundUrl) {
            promoSection.style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('${backgroundUrl}')`;
            promoSection.style.backgroundSize = 'cover';
            promoSection.style.backgroundPosition = 'center';
        }
    }

    function updateNavButtons() {
        prevBtn.disabled = currentPosition <= 0;
        nextBtn.disabled = currentPosition >= maxScroll;
    }

    function scrollTrack(direction) {
        if (direction === 'prev' && currentPosition > 0) {
            currentPosition--;
        } else if (direction === 'next' && currentPosition < maxScroll) {
            currentPosition++;
        }

        const offset = -currentPosition * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        updateNavButtons();
        updateBackground();
    }

    prevBtn.removeEventListener('click', () => scrollTrack('prev'));
    nextBtn.removeEventListener('click', () => scrollTrack('next'));

    prevBtn.addEventListener('click', () => scrollTrack('prev'));
    nextBtn.addEventListener('click', () => scrollTrack('next'));

    updateNavButtons();
    updateBackground();

    return function updateSlider() {
        itemWidth = items[0].offsetWidth + 16;
        visibleItems = 1;
        maxScroll = Math.max(0, items.length - visibleItems);

        if (currentPosition > maxScroll) {
            currentPosition = maxScroll;
        }

        const offset = -currentPosition * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        updateNavButtons();
        updateBackground();
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

document.addEventListener('livewire:initialized', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

document.addEventListener('livewire:navigated', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});
