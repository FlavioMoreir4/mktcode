import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import inertia from '@inertiajs/vite';
import { defineConfig } from 'vite';
import VueDevTools from 'vite-plugin-vue-devtools';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.ts',
                'resources/css/app.css',
                'resources/css/filament/admin/theme.css',
            ],
            // ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        inertia({
            ssr: {
                cluster: true,
            },
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
        VueDevTools({
            appendTo: 'resources/js/app.ts',
        }),
        visualizer(),
    ],
    build: {
        sourcemap: true,
    },
});
