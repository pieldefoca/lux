import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`
            }
        }
    },
    plugins: [
        laravel({
            buildDirectory: 'dist',
            input: [
                'resources/css/lux.css',
                'resources/js/lux.js',
            ],
            refresh: true,
        }),
    ],
});
