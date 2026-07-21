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
        // HMR websocket + injected asset URLs use this host; defaults to
        // localhost for same-machine dev, override with VITE_HOST (e.g. your
        // LAN IP) to reach the dev server from another device like a phone
        hmr: { host: process.env.VITE_HOST || 'localhost' },
        // Vite's default CORS allowlist only matches localhost/127.0.0.1/[::1]
        // (see defaultAllowedOrigins in vite's source) — the page itself is
        // loaded from VITE_HOST too (e.g. the phone hitting the LAN IP), so
        // <script type="module"> requests back to this dev server send that
        // same origin and get silently CORS-blocked without this: the app's
        // CSS still loads (stylesheets aren't CORS-gated) but every JS module
        // fails, so nothing interactive (Bootstrap JS, sheets, Vue) works.
        cors: true,
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
