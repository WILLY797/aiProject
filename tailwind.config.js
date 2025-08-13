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
                primary: {
                    50: '#fffef0',
                    100: '#fffacc',
                    200: '#fff599',
                    300: '#ffed66',
                    400: '#ffe633',
                    500: '#ffdf00', // bright yellow
                    600: '#e6c900',
                    700: '#ccb300',
                    800: '#b39c00',
                    900: '#998500',
                },
                secondary: {
                    50: '#fffef7',
                    100: '#fffbeb',
                    200: '#fff5d6',
                    300: '#ffefb8',
                    400: '#ffe99a',
                    500: '#ffe37c', // yellow
                    600: '#e6cc70',
                    700: '#ccb563',
                    800: '#b39e56',
                    900: '#998749',
                },
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
                dark: '#1a1a1a',
                'dark-secondary': '#2d2d2d',
            },
            boxShadow: {
                card: '0 14px 34px 0 rgba(0,0,0,0.08)',
                'glow-yellow': '0 0 20px rgba(255, 223, 0, 0.3)',
                'glow-yellow-lg': '0 0 40px rgba(255, 223, 0, 0.4)',
                'inner-glow': 'inset 0 2px 4px 0 rgba(255, 223, 0, 0.1)',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.6s ease-out',
                'bounce-subtle': 'bounceSubtle 2s infinite',
                'float': 'float 3s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(30px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                bounceSubtle: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
        },
    },
    plugins: [forms],
};
