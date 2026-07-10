<script>
    (function () {
        const stored = localStorage.getItem('theme');
        const accountPreference = @json(auth()->check() ? auth()->user()->theme_preference : null);
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

        let isDark;
        if (stored) {
            isDark = stored === 'dark';
        } else if (accountPreference === 'light') {
            isDark = false;
        } else if (accountPreference === 'dark') {
            isDark = true;
        } else if (accountPreference === 'system') {
            isDark = prefersDark;
        } else {
            isDark = true;
        }

        if (isDark) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
