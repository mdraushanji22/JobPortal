import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('themeSwitcher', () => ({
        isDark: document.documentElement.classList.contains('dark'),
        init() {
            this.$watch('isDark', val => {
                document.documentElement.classList.toggle('dark', val);
                localStorage.setItem('theme', val ? 'dark' : 'light');
            });
        },
        toggle() {
            this.isDark = !this.isDark;
        },
    }));
});

Alpine.start();
