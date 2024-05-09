import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app1.css', 'resources/js/app1.js'],
            refresh: true,
        }),
    ],
});
