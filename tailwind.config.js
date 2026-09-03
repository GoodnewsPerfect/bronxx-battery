import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#0056D2',
                    dark: '#0047AB',
                    light: '#3B7DE0',
                },
                navy: '#0A1221',
                charcoal: '#4A4A4A',
                cyan: '#00D1FF',
                accent: {
                    DEFAULT: '#F9AD32',
                    dark: '#f3a11d',
                },
                gold: {
                    DEFAULT: '#FFD700',
                    dark: '#FFC400',
                },
                danger: '#FF4D4D',
            },
        },
    },

    plugins: [forms],
};
