import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        port: 5174,
        proxy: {
            '^/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: false,
                rewrite: (path) => path
            },
            '/sanctum': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: false
            }
        }
    }

});
