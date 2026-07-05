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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                paper: '#F6F4FB',
                ink: '#211C3A',
                brand: {
                    DEFAULT: '#6C5CE7',
                    dark: '#5849C4',
                    light: '#EDEAFB',
                },
                mint: '#00B88D',
                amber: '#F5A623',
                coral: '#FF6B6B',
                line: '#E7E3F5',
            },
        },
    },

    plugins: [forms],
};