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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  '#f0f7ff',
                    100: '#e0efff',
                    200: '#b9dfff',
                    300: '#7cc4ff',
                    400: '#36a5ff',
                    500: '#0077b6',
                    600: '#005f94',
                    700: '#004a75',
                    800: '#003a5c',
                    900: '#002f4d',
                    950: '#001d33',
                },
                accent: {
                    50:  '#fff8f3',
                    100: '#fff0e6',
                    200: '#ffd9bf',
                    300: '#ffba8a',
                    400: '#ff8c4a',
                    500: '#FF6B35',
                    600: '#e55a2b',
                    700: '#bf4520',
                    800: '#993718',
                    900: '#7a2d14',
                },
                success: {
                    50:  '#eefff7',
                    100: '#d6ffec',
                    200: '#a8ffda',
                    300: '#64ffc0',
                    400: '#00e89d',
                    500: '#00C48C',
                    600: '#009e6f',
                    700: '#007d5a',
                    800: '#006349',
                    900: '#00513c',
                },
                warning: {
                    50:  '#fffbeb',
                    100: '#fff3c6',
                    200: '#ffe588',
                    300: '#ffd14a',
                    400: '#ffbe20',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                danger: {
                    50:  '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
                surface: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                },
            },
            borderRadius: {
                'card': '16px',
                'btn': '12px',
                'pill': '9999px',
            },
            boxShadow: {
                'card': '0 2px 12px rgba(0, 47, 92, 0.06)',
                'card-hover': '0 8px 24px rgba(0, 47, 92, 0.1)',
                'btn': '0 4px 12px rgba(0, 119, 182, 0.25)',
                'nav': '0 -2px 16px rgba(0, 47, 92, 0.08)',
            },
        },
    },

    plugins: [forms],
};
