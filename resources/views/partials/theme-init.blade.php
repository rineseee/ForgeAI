<script>
    (function () {
        const stored = localStorage.getItem('theme');
        const isDark = stored ? stored === 'dark' : true;
        if (isDark) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
