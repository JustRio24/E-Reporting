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
                    DEFAULT: '#1961a1', // Industrial Blue
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#1961a1',
                    600: '#155e8f',
                    700: '#11507a',
                    800: '#0d3f61',
                    900: '#0a3050',
                    container: '#81b9ff',
                    dark: '#004880',
                },
                accent: {
                    DEFAULT: '#f26522', // Safety Orange
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f26522',
                    600: '#d9580e',
                    700: '#b8480a',
                    800: '#923a0c',
                    900: '#78310d',
                },
                surface: {
                    DEFAULT: '#ffffff',
                    50: '#f8f9fc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                },
                secondary: {
                    DEFAULT: '#eab308', // Amber/Yellow
                    dark: '#ca8a04',
                    container: '#fef08a',
                },
                success: '#059669',
                warning: '#d97706',
                error: '#dc2626',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                sm: '4px',
                DEFAULT: '8px',
                lg: '12px',
                xl: '16px',
                '2xl': '20px',
            },
            boxShadow: {
                'soft': '0 2px 8px rgba(0, 0, 0, 0.04)',
                'card': '0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04)',
                'card-hover': '0 10px 25px rgba(0, 0, 0, 0.08), 0 4px 10px rgba(0, 0, 0, 0.04)',
                'sidebar': '2px 0 8px rgba(0, 0, 0, 0.04)',
                'header': '0 1px 3px rgba(0, 0, 0, 0.05)',
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                'fade-in-left': 'fadeInLeft 0.5s ease-out forwards',
                'fade-in-right': 'fadeInRight 0.5s ease-out forwards',
                'scale-in': 'scaleIn 0.4s ease-out forwards',
                'slide-down': 'slideDown 0.3s ease-out forwards',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-24px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                fadeInRight: {
                    '0%': { opacity: '0', transform: 'translateX(24px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [forms],
};
