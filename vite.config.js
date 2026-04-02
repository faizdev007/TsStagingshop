import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/demo1/js/app.js',
                'resources/assets/demo1/sass/app.scss',
            ],
            refresh: true,
        }),
    ],

    define: {
        global: 'window',
    },

    build: {
        minify: 'esbuild',

        rollupOptions: {
            output: {
                manualChunks(id) {

                    // Node modules splitting
                    if (id.includes('node_modules')) {

                        if (id.includes('jquery')) {
                            return 'jquery';
                        }

                        if (id.includes('bootstrap')) {
                            return 'bootstrap';
                        }

                        // fallback (other libs)
                        return 'vendor';
                    }
                }
            }
        }
    }
});