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
            colors:{
                primary:"#2F4156",
                secondary:"#567C8D",
                // {1:"#567C8D",
                //   2:"#100DB1",
                //   3:"#763CEF",
                //   4:"#FECA57"
                // }
                beige:"#F5EFEB",
                sky_blue: "#C8D9E6",
                white:"#FFFFFF"
              }
        },
    },

    plugins: [forms],
};
