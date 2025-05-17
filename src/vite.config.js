import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import i18n from 'laravel-vue-i18n/vite';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                path.resolve(__dirname, 'resources/css/app.css'),
                path.resolve(__dirname, 'resources/js/app.js'),
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
        i18n(),
        visualizer({
            filename: './build-stats.html',
            open: true,
        }),

    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
    build: {
        chunkSizeWarningLimit: 300,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('vue')) return 'vendor_vue';
                        if (id.includes('lodash')) return 'vendor_lodash';
                        if (id.includes('moment')) return 'vendor_moment';
                        return 'vendor';
                    }
                },
            },
        },
    },
});
