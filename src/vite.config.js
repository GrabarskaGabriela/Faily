import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                path.resolve(__dirname, 'resources/css/app.css'), // pełna ścieżka do pliku
                path.resolve(__dirname, 'resources/js/app.js')   // pełna ścieżka do pliku
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'), // Poprawiona pełna ścieżka
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
});
