document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.querySelector(
        '.header__mobile-menu-button');
    const nav = document.querySelector('.header__nav');
    const authLinks = document.querySelector('.header__auth');

    if (mobileMenuButton && nav) {
        // Додаємо іконку бургер-меню з анімацією
        mobileMenuButton.innerHTML = `
            <div class="burger-icon">
                <span class="burger-icon__line"></span>
                <span class="burger-icon__line"></span>
                <span class="burger-icon__line"></span>
            </div>
        `;

        mobileMenuButton.addEventListener('click', function () {
            // Додаємо клас для анімації іконки бургера
            this.querySelector('.burger-icon').classList.toggle(
                'burger-icon--active');

            // Перемикаємо клас для мобільного меню
            nav.classList.toggle('header__nav--mobile-active');

            // Додаємо стилі для мобільного меню при активації
            if (nav.classList.contains('header__nav--mobile-active')) {
                // Анімуємо появу меню
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

                // Блокуємо прокрутку сторінки при відкритому меню
                document.body.style.overflow = 'hidden';

                // Змінюємо стилі для списку та елементів
                const navList = nav.querySelector('.header__nav-list');
                if (navList) {
                    navList.style.flexDirection = 'column';
                    navList.style.gap = 'var(--spacing-md)';
                    navList.style.padding = 'var(--spacing-md)';

                    // Анімуємо появу пунктів меню з затримкою
                    const navItems = navList.querySelectorAll(
                        '.header__nav-item');
                    navItems.forEach((item, index) => {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(10px)';
                        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        item.style.transitionDelay = `${index * 0.05}s`;
                        item.style.margin = 'var(--spacing-xs) var(--spacing-md)';
                        item.style.padding = 'var(--spacing-xs) 0';

                        // Затримка для анімації
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 50);
                    });

                    // Збільшуємо розмір посилань для зручності на мобільних
                    const navLinks = navList.querySelectorAll(
                        '.header__nav-link');
                    navLinks.forEach(link => {
                        link.style.display = 'block';
                        link.style.padding = 'var(--spacing-md)';
                        link.style.fontSize = '1.1rem';
                    });

                    // Додаємо кнопки авторизації/реєстрації для мобільних пристроїв
                    if (authLinks && window.innerWidth <= 768) {
                        // Перевіряємо, чи вже додані кнопки авторизації
                        if (!navList.querySelector('.header__mobile-auth')) {
                            // Створюємо контейнер для кнопок авторизації
                            const mobileAuthContainer = document.createElement(
                                'li');
                            mobileAuthContainer.className = 'header__nav-item header__mobile-auth';
                            mobileAuthContainer.style.borderTop = '1px solid var(--border-color)';
                            mobileAuthContainer.style.marginTop = 'var(--spacing-md)';
                            mobileAuthContainer.style.paddingTop = 'var(--spacing-md)';

                            // Клонуємо кнопки авторизації
                            const authLinksClone = authLinks.cloneNode(true);
                            authLinksClone.style.display = 'flex';
                            authLinksClone.style.flexDirection = 'column';
                            authLinksClone.style.gap = 'var(--spacing-md)';

                            // Стилізуємо кнопки
                            const authButtons = authLinksClone.querySelectorAll(
                                'a');
                            authButtons.forEach(button => {
                                button.style.display = 'block';
                                button.style.padding = 'var(--spacing-md)';
                                button.style.fontSize = '1.1rem';
                                button.style.textAlign = 'center';
                            });

                            mobileAuthContainer.appendChild(authLinksClone);
                            navList.appendChild(mobileAuthContainer);

                            // Анімуємо появу кнопок авторизації
                            mobileAuthContainer.style.opacity = '0';
                            mobileAuthContainer.style.transform = 'translateY(10px)';
                            mobileAuthContainer.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            mobileAuthContainer.style.transitionDelay = `${navItems.length
                            * 0.05 + 0.1}s`;

                            setTimeout(() => {
                                mobileAuthContainer.style.opacity = '1';
                                mobileAuthContainer.style.transform = 'translateY(0)';
                            }, 50);
                        }
                    }
                }
            } else {
                // Анімуємо зникнення меню
                nav.style.transform = 'translateY(-10px)';
                nav.style.opacity = '0';

                // Розблоковуємо прокрутку сторінки
                document.body.style.overflow = '';

                // Затримка перед скиданням стилів, щоб анімація завершилась
                setTimeout(() => {
                    nav.style = '';
                    const navList = nav.querySelector('.header__nav-list');
                    if (navList) {
                        navList.style = '';
                        const navItems = navList.querySelectorAll(
                            '.header__nav-item');
                        navItems.forEach(item => {
                            item.style = '';
                        });

                        const navLinks = navList.querySelectorAll(
                            '.header__nav-link');
                        navLinks.forEach(link => {
                            link.style = '';
                        });

                        // Видаляємо мобільні кнопки авторизації
                        const mobileAuth = navList.querySelector(
                            '.header__mobile-auth');
                        if (mobileAuth) {
                            navList.removeChild(mobileAuth);
                        }
                    }
                }, 300);
            }
        });

        // Закриваємо меню при кліку поза ним
        document.addEventListener('click', function (event) {
            if (nav.classList.contains('header__nav--mobile-active') &&
                !nav.contains(event.target) &&
                !mobileMenuButton.contains(event.target)) {
                // Закриваємо меню
                mobileMenuButton.querySelector('.burger-icon').classList.remove(
                    'burger-icon--active');
                nav.classList.remove('header__nav--mobile-active');

                // Анімуємо зникнення меню
                nav.style.transform = 'translateY(-10px)';
                nav.style.opacity = '0';

                // Розблоковуємо прокрутку сторінки
                document.body.style.overflow = '';

                // Затримка перед скиданням стилів
                setTimeout(() => {
                    nav.style = '';
                    const navList = nav.querySelector('.header__nav-list');
                    if (navList) {
                        navList.style = '';
                        const navItems = navList.querySelectorAll(
                            '.header__nav-item');
                        navItems.forEach(item => {
                            item.style = '';
                        });

                        const navLinks = navList.querySelectorAll(
                            '.header__nav-link');
                        navLinks.forEach(link => {
                            link.style = '';
                        });

                        // Видаляємо мобільні кнопки авторизації
                        const mobileAuth = navList.querySelector(
                            '.header__mobile-auth');
                        if (mobileAuth) {
                            navList.removeChild(mobileAuth);
                        }
                    }
                }, 300);
            }
        });

        // Закриваємо меню при зміні розміру вікна
        window.addEventListener('resize', function () {
            if (window.innerWidth > 1024 && nav.classList.contains(
                'header__nav--mobile-active')) {
                mobileMenuButton.querySelector('.burger-icon').classList.remove(
                    'burger-icon--active');
                nav.classList.remove('header__nav--mobile-active');
                nav.style = '';
                document.body.style.overflow = '';

                const navList = nav.querySelector('.header__nav-list');
                if (navList) {
                    navList.style = '';
                    const navItems = navList.querySelectorAll(
                        '.header__nav-item');
                    navItems.forEach(item => {
                        item.style = '';
                    });

                    const navLinks = navList.querySelectorAll(
                        '.header__nav-link');
                    navLinks.forEach(link => {
                        link.style = '';
                    });

                    // Видаляємо мобільні кнопки авторизації
                    const mobileAuth = navList.querySelector(
                        '.header__mobile-auth');
                    if (mobileAuth) {
                        navList.removeChild(mobileAuth);
                    }
                }
            }
        });
    }

    // Додаємо функціональність для випадаючого меню користувача
    const userButton = document.querySelector('.header__user-button');
    const userDropdown = document.querySelector('.header__user-dropdown');

    if (userButton && userDropdown) {
        userButton.addEventListener('click', function (event) {
            event.stopPropagation();

            // Анімуємо появу/зникнення випадаючого меню
            if (userDropdown.style.display === 'block') {
                userDropdown.style.opacity = '0';
                userDropdown.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    userDropdown.style.display = 'none';
                }, 300);
            } else {
                userDropdown.style.display = 'block';
                userDropdown.style.opacity = '0';
                userDropdown.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    userDropdown.style.opacity = '1';
                    userDropdown.style.transform = 'translateY(0)';
                }, 50);
            }
        });

        // Закриваємо випадаюче меню при кліку поза ним
        document.addEventListener('click', function (event) {
            if (userDropdown.style.display === 'block' &&
                !userDropdown.contains(event.target) &&
                !userButton.contains(event.target)) {

                userDropdown.style.opacity = '0';
                userDropdown.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    userDropdown.style.display = 'none';
                }, 300);
            }
        });
    }
});
