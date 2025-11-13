import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                edux: {
                    primary: '#1A73E8',
                    cta: '#FBC02D',
                    background: '#F5F5F5',
                    line: '#E0E0E0',
                    text: '#000000',
                    navy: '#0F2E73',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '0 18px 35px rgba(26, 115, 232, 0.12)',
                'cta-hover': '0 12px 30px rgba(251, 192, 45, 0.35)',
            },
            borderRadius: {
                card: '18px',
            },
        },
    },
    plugins: [forms],
};
