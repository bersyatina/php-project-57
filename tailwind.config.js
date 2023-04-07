const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        fontFamily: {
            // 'sans': ['-apple-system', 'BlinkMacSystemFont'],
            'serif': ['Georgia', 'Cambria'],
            'mono': ['SFMono-Regular', 'Menlo'],
            'nunito': ['Nunito', 'sans-serif']
        }
    },

    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('tailwindcss-children'),
    ],
};
