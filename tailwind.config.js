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
        screens:{
            'sm':'810px',
            'md': '1080px',
            'lg': '1280px'
        },
        extend: {
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'jungleGreen' : '#06A77D',
                'indigoDye' : '#005377',
                'berkeleyBlue': '#052F5F',
                'gamboge':'#F1A208',
                'citron': '#D5C67A'
            }
        },
    },

    plugins: [forms],
};
