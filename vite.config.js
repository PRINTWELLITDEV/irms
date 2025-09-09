import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                    'resources/css/irms.css',  
                    'resources/js/app.js',
                    'resources/bootstrap-5.0.2-dist/css/bootstrap.css',
                    'resources/bootstrap-5.0.2-dist/js/bootstrap.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
