import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
            colors: {
                raza: {
                    yellow: '#FFBD27',
                    'yellow-light': '#FFF8E1',
                    pink: '#F35B9B',
                    'pink-light': '#FCE7F1',
                    blue: '#279FE8',
                    'blue-light': '#E3F2FD',
                    green: '#79C942',
                    'green-light': '#EDF7E7',
                    purple: '#8C4EDB',
                    'purple-light': '#F3E8FF',
                    cream: '#FFF7EA',
                    'cream-dark': '#F5ECE0',
                    black: '#17191F',
                    slate: '#2B2E38',
                    muted: '#686D7A',
                    border: '#EADFCF',
                },
            },
        },
    },

    plugins: [forms],
};
