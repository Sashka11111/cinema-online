document.addEventListener('livewire:navigated', initializeMobileMenu);

function initializeMobileMenu() {
    // Видаляємо старі слухачі, щоб уникнути дублювання
    const mobileMenuButton = document.querySelector('.header__mobile-menu-button');
    const nav = document.querySelector('.header__nav');
    const authLinks = document.querySelector('.header__auth');

    if (!mobileMenuButton || !nav) return;

    // Додаємо іконку бургер-меню, якщо вона ще не додана
    if (!mobileMenuButton.querySelector('.burger-icon')) {
        mobileMenuButton.innerHTML = `
            <div class="burger-icon">
                <span class="burger-icon__line"></span>
                <span class="burger-icon__line"></span>
                <span class="burger-icon__line"></span>
            </div>
        `;
    }

    // Очищаємо попередні слухачі (використовуємо клонований елемент для скидання подій)
    const newMobileMenuButton = mobileMenuButton.cloneNode(true);
    mobileMenuButton.parentNode.replaceChild(newMobileMenuButton, mobileMenuButton);

    // Функція для скидання стилів меню
    function resetMenuStyles() {
        nav.style = '';
        document.body.style.overflow = '';
        const navList = nav.querySelector('.header__nav-list');
        if (navList) {
            navList.style = '';
            const navItems = navList.querySelectorAll('.header__nav-item');
            navItems.forEach(item => item.style = '');
            const navLinks = navList.querySelectorAll('.header__nav-link');
            navLinks.forEach(link => link.style = '');
            const mobileAuth = navList.querySelector('.header__mobile-auth');
            if (mobileAuth) navList.removeChild(mobileAuth);
        }
        newMobileMenuButton.querySelector('.burger-icon').classList.remove('burger-icon--active');
        nav.classList.remove('header__nav--mobile-active');
    }

    // Функція для активації мобільного меню
    function activateMobileMenu() {
        const burgerIcon = newMobileMenuButton.querySelector('.burger-icon');
        burgerIcon.classList.toggle('burger-icon--active');
        nav.classList.toggle('header__nav--mobile-active');

        if (nav.classList.contains('header__nav--mobile-active')) {
            // Стилі для активного меню
            nav.style.display = 'block';
            nav.style.position = 'absolute';
            nav.style.top = '100%';
            nav.style.left = '0';
            nav.style.right = '0';
            nav.style.backgroundColor = 'var(--card-background)';
            nav.style.boxShadow = '0 4px 12px var(--shadow-color)';
            nav.style.padding = 'var(--spacing-md) 0';
            nav.style.zIndex = '50';
            nav.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
            nav.style.transform = 'translateY(0)';
            nav.style.opacity = '1';
            document.body.style.overflow = 'hidden';

            const navList = nav.querySelector('.header__nav-list');
            if (navList) {
                navList.style.flexDirection = 'column';
                navList.style.gap = 'var(--spacing-md)';
                navList.style.padding = 'var(--spacing-md)';

                const navItems = navList.querySelectorAll('.header__nav-item');
                navItems.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(10px)';
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.transitionDelay = `${index * 0.05}s`;
                    item.style.margin = 'var(--spacing-xs) var(--spacing-md)';
                    item.style.padding = 'var(--spacing-xs) 0';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 50);
                });

                const navLinks = navList.querySelectorAll('.header__nav-link');
                navLinks.forEach(link => {
                    link.style.display = 'block';
                    link.style.padding = 'var(--spacing-md)';
                    link.style.fontSize = '1.1rem';
                });

                // Додаємо кнопки авторизації
                if (authLinks && window.innerWidth <= 768) {
                    if (!navList.querySelector('.header__mobile-auth')) {
                        const mobileAuthContainer = document.createElement('li');
                        mobileAuthContainer.className = 'header__nav-item header__mobile-auth';
                        mobileAuthContainer.style.borderTop = '1px solid var(--border-color)';
                        mobileAuthContainer.style.marginTop = 'var(--spacing-md)';
                        mobileAuthContainer.style.paddingTop = 'var(--spacing-md)';

                        const authLinksClone = authLinks.cloneNode(true);
                        authLinksClone.style.display = 'flex';
                        authLinksClone.style.flexDirection = 'column';
                        authLinksClone.style.gap = 'var(--spacing-md)';

                        const authButtons = authLinksClone.querySelectorAll('a');
                        authButtons.forEach(button => {
                            button.style.display = 'block';
                            button.style.padding = 'var(--spacing-md)';
                            button.style.fontSize = '1.1rem';
                            button.style.textAlign = 'center';
                        });

                        mobileAuthContainer.appendChild(authLinksClone);
                        navList.appendChild(mobileAuthContainer);

                        mobileAuthContainer.style.opacity = '0';
                        mobileAuthContainer.style.transform = 'translateY(10px)';
                        mobileAuthContainer.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        mobileAuthContainer.style.transitionDelay = `${navItems.length * 0.05 + 0.1}s`;
                        setTimeout(() => {
                            mobileAuthContainer.style.opacity = '1';
                            mobileAuthContainer.style.transform = 'translateY(0)';
                        }, 50);
                    }
                }
            }
        } else {
            // Анімація зникнення
            nav.style.transform = 'translateY(-10px)';
            nav.style.opacity = '0';
            document.body.style.overflow = '';
            setTimeout(resetMenuStyles, 300);
        }
    }

    // Додаємо слухач для кнопки меню
    newMobileMenuButton.addEventListener('click', activateMobileMenu);

    // Закриття меню при кліку поза ним (делегування)
    document.addEventListener('click', (event) => {
        if (nav.classList.contains('header__nav--mobile-active') &&
            !nav.contains(event.target) &&
            !newMobileMenuButton.contains(event.target)) {
            nav.classList.remove('header__nav--mobile-active');
            nav.style.transform = 'translateY(-10px)';
            nav.style.opacity = '0';
            document.body.style.overflow = '';
            newMobileMenuButton.querySelector('.burger-icon').classList.remove('burger-icon--active');
            setTimeout(resetMenuStyles, 300);
        }
    });

    // Закриття меню при зміні розміру вікна
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024 && nav.classList.contains('header__nav--mobile-active')) {
            resetMenuStyles();
        }
    });
}

// Ініціалізація при першому завантаженні
document.addEventListener('DOMContentLoaded', initializeMobileMenu);
