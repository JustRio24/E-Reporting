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
            colors: {
                primary: {
                    DEFAULT: '#a63b00', // Safety Orange
                    container: '#f26522',
                    dark: '#7f2b00',
                },
                secondary: {
                    DEFAULT: '#1961a1', // Industrial Blue
                    container: '#81b9ff',
                    dark: '#004880',
                },
                tertiary: {
                    DEFAULT: '#545f72',
                    container: '#8792a7',
                },
                customBg: '#f9f9fc',
                success: '#059669',
                warning: '#d97706',
                error: '#ba1a1a',
            },
            fontFamily: {
                sans: ['IBM Plex Sans', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                sm: '2px',
                DEFAULT: '4px',
                lg: '8px',
            },
        },
    },

    plugins: [forms],
};
