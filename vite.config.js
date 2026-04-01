import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/theme/scss/style.scss'
            ],
            refresh: [`resources/views/**/*`],
        }),
        tailwindcss(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                api: 'legacy',
                quietDeps: true,
            }
        },
        // KHÔNG cho phép Vite xử lý URL trong CSS để tránh vỡ ảnh của theme
        devSourcemap: true,
    },
    build: {
        assetsInlineLimit: 0,
    },
    server: {
        cors: true,
    },
});