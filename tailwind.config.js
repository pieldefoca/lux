/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "vendor/pieldefoca/lux/resources/**/*.blade.php",
        "vendor/pieldefoca/lux/resources/**/*.js",
        "vendor/pieldefoca/lux-blog/resources/**/*.blade.php",
        "vendor/pieldefoca/lux-blog/resources/**/*.js",
    ],
    theme: {
        extend: {
            fontFamily: {
                body: ['Quicksand', 'serif'],
            }
        },
    },
    plugins: [
        require('@tailwindcss/container-queries'),
    ],
}
