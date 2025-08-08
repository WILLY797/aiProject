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
            colors: {

                brand: {
                    50: '#fffff0',
                    100: '#ffffb3',
                    200: '#ffff80',
                    300: '#ffff4d',
                    400: '#ffff26',
                    500: '#ffff07',
                    600: '#e6e600',
                    700: '#cccc00',
                    800: '#999900',
                    900: '#666600',
                },
                accent: '#ff7a00',
            },
            boxShadow: {
                card: '0 14px 34px 0 rgba(0,0,0,0.08)',
            },
        },
    },
    plugins: [forms],
};
