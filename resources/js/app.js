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

Alpine.store('toasts', {
    items: [],

    push(type, message, duration = 5000) {
        const id = Date.now() + Math.random();
        this.items.push({ id, type, message });

        if (duration > 0) {
            setTimeout(() => this.dismiss(id), duration);
        }
    },

    dismiss(id) {
        this.items = this.items.filter((item) => item.id !== id);
    },
});

window.addEventListener('toast', (event) => {
    const { type, message, duration } = event.detail;
    Alpine.store('toasts').push(type, message, duration);
});

Alpine.start();

document.addEventListener('DOMContentLoaded', () => Prism.highlightAll());
