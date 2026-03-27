import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import VueDevTools from 'vite-plugin-vue-devtools';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.ts',
                'resources/css/filament/admin/theme.css',
            ],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                compilerOptions: {
                    isCustomElement: (tag) => tag.startsWith('swiper-'),
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
        rollupOptions: {
            external: [],
            output: {
                // manualChunks: {
                //     // 'vendor-vue': ['vue', '@inertiajs/vue3'],
                //     'vendor-editor': ['highlight.js'],
                // },
                // manualChunks(id) {
                //     if (id.includes('highlight.js')) {
                //         return 'vendor-editor';
                //     }
                //     if (id.includes('vue') || id.includes('@inertiajs/vue3')) {
                //         return 'vendor-vue';
                //     }
                // },
            },
        },
        sourcemap: true,
    },
});
