document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        dark: false,

        init() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.dark = saved ? saved === 'dark' : prefersDark;
            this.apply();
        },

        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            this.apply();
        },

        apply() {
            document.documentElement.classList.toggle('dark', this.dark);
        },
    });
});
