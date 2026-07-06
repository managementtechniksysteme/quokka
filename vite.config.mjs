import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: false,
            },
        }),
    ],
    server: {
        // Bind inside the container so the dev server is reachable from the host
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        // HMR websocket connects via the port published to the host
        hmr: { host: 'localhost' },
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
