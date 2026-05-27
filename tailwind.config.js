import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
import colors from 'tailwindcss/colors';
export default {
    darkMode: ['class', '.never-active-class'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Google Sans"', ...defaultTheme.fontFamily.sans],
                outfit: ['"Google Sans"', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#f5f3ff',
                    100: '#ede9fe',
                    200: '#ddd6fe',
                    300: '#c084fc',
                    400: '#a855f7',
                    500: '#8b5cf6',
                    600: '#4f46e5', // indigo-600 primary
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                }
            }
        },
    },

    plugins: [forms],
};


