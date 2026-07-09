import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                shimmer: {
                    '100%': { transform: 'translateX(100%)' },
                },
                'fade-in': {
                    from: { opacity: 0, transform: 'translateY(4px)' },
                    to: { opacity: 1, transform: 'translateY(0)' },
                },
            },
            animation: {
                shimmer: 'shimmer 1.6s infinite',
                'fade-in': 'fade-in 0.25s ease-out',
            },
        },
    },

    plugins: [forms, typography],
};
