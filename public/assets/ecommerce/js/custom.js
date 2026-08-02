class Custom {

    highlightSecondWord() {
        const h2Elements = document.querySelectorAll('h2');

        h2Elements.forEach(h2 => {
            const text = h2.textContent.trim();
            const words = text.split(' ');

            if (words.length === 2) {
                h2.innerHTML = `${words[0]} <span class="text-primary"> ${words[1]}</span>`;
            }

            if (words.length === 3) {
                h2.innerHTML = `${words[0]} ${words[1]} <span class="text-primary"> ${words[2]}</span>`;
            }
        });
    }

    cookie() {
        const accept = document.getElementById('btn-accept-cookies');
        const decline = document.getElementById('btn-decline-cookies');
        const banner = document.getElementById('cookie-banner');

        if (!banner || !accept || !decline) return;

        // Si ya tomó una decisión, no hacer nada
        if (localStorage.getItem('cookies-accepted') || localStorage.getItem('cookies-declined')) {
            return;
        }

        // Muestra el banner con una transición suave después de 1 segundo
        setTimeout(() => {
            banner.classList.add('active');
        }, 1000);

        accept.addEventListener('click', () => {
            banner.classList.remove('active');
            localStorage.setItem('cookies-accepted', 'true');
        });

        decline.addEventListener('click', () => {
            banner.classList.remove('active');
            localStorage.setItem('cookies-declined', 'true');
        });
    }

    theme() {
        const button = document.getElementById('theme-toggle');
        if (!button) return;
        let theme = localStorage.getItem('data-bs-theme');

        if (!theme) {
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches
                ? 'dark'
                : 'light';
        }

        this.setTheme(theme);

        button.addEventListener('click', (e) => {

            e.preventDefault();

            const currentTheme = document.documentElement.getAttribute('data-bs-theme');

            this.setTheme(
                currentTheme === 'dark'
                    ? 'light'
                    : 'dark'
            );

        });

    }

    setTheme(theme) {

        document.documentElement.setAttribute('data-bs-theme', theme);

        localStorage.setItem('data-bs-theme', theme);

        const sun = document.querySelector('.theme-icon-light');
        const moon = document.querySelector('.theme-icon-dark');

        if (sun && moon) {
            sun.classList.toggle('d-none', theme === 'dark');
            moon.classList.toggle('d-none', theme === 'light');
        }

    }

    init() {
        this.highlightSecondWord();
        this.cookie();
        this.theme();
    }

}

document.addEventListener('DOMContentLoaded', () => {
    new Custom().init();
});