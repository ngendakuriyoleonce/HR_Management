import Alpine from 'alpinejs';

Alpine.store('theme', {
    dark: false,

    init() {
        var saved = localStorage.getItem('theme');
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        this.dark = saved ? saved === 'dark' : prefersDark;
        this._apply();
    },

    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        this._apply();
    },

    _apply() {
        if (this.dark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
});

Alpine.start();
