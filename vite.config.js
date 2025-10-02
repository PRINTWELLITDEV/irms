import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',   
                'resources/css/irms.css',
                'resources/js/app.js',
                'resources/js/irms/irms.js'
            ],
            refresh: true,
        }),
    ],
});
