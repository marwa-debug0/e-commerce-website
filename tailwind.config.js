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
            spacing: {
                'unit-1': '8px',
                'unit-2': '16px',
            },
            width: {
                '155-units': '1240px',
            },
            maxWidth: {
                '155-units': '1240px',
            },
            colors: {
                'bauhaus-black': '#111111',
                'bauhaus-gray': '#e5e5e5',
                'bauhaus-white': '#ffffff',
            }
        },
    },

    plugins: [forms],
};
