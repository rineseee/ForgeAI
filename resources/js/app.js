import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import Prism from 'prismjs';
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-diff';

window.Alpine = Alpine;
window.Chart = Chart;
window.Prism = Prism;

Alpine.store('theme', {
    dark: document.documentElement.classList.contains('dark'),

    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
    },
});

Alpine.start();

document.addEventListener('DOMContentLoaded', () => Prism.highlightAll());
