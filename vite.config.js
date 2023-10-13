import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            hotFile: 'public/lux.hot',
            buildDirectory: 'public/build',
            input: [
                'resources/css/lux.css',
                'resources/js/lux.js',
            ],
            refresh: true,
        }),
    ],
});
