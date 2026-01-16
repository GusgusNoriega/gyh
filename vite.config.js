import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';

// Plugin personalizado para copiar manifest a ubicación legacy (Laravel < 11.x)
const copyManifestPlugin = () => ({
    name: 'copy-manifest-legacy',
    closeBundle: () => {
        const sourceDir = 'public/build/.vite';
        const sourcePath = path.join(sourceDir, 'manifest.json');
        const destPath = 'public/build/manifest.json';
        
        // Esperar un poco para que el archivo exista
        setTimeout(() => {
            if (fs.existsSync(sourcePath)) {
                fs.copyFileSync(sourcePath, destPath);
                console.log('✓ Manifest copiado a public/build/manifest.json (compatibilidad Laravel)');
            }
        }, 100);
    }
});

export default defineConfig(({ command }) => ({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        // Solo copiar manifest en build de producción
        command === 'build' && copyManifestPlugin(),
    ].filter(Boolean),
    build: {
        // Genera el manifest
        manifest: true,
        outDir: 'public/build',
        // Optimizaciones para PageSpeed/GTmetrix
        target: 'es2020',
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info', 'console.debug'],
            },
            mangle: true,
            format: {
                comments: false,
            },
        },
        cssMinify: true,
        cssCodeSplit: true,
        // Source maps desactivados en producción para mejor rendimiento
        sourcemap: false,
        // Optimización de chunks
        chunkSizeWarningLimit: 500,
        rollupOptions: {
            output: {
                // Chunks separados para mejor caché
                manualChunks: (id) => {
                    if (id.includes('node_modules')) {
                        // Separar vendor chunks por librería principal
                        if (id.includes('axios')) return 'vendor-axios';
                        if (id.includes('alpinejs')) return 'vendor-alpine';
                        return 'vendor';
                    }
                },
                // Asegura nombres de archivo consistentes con hash para caché
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    // CSS crítico en ubicación específica
                    if (assetInfo.name && assetInfo.name.endsWith('.css')) {
                        return 'assets/css/[name]-[hash].[ext]';
                    }
                    // Fuentes
                    if (assetInfo.name && /\.(woff2?|eot|ttf|otf)$/.test(assetInfo.name)) {
                        return 'assets/fonts/[name]-[hash].[ext]';
                    }
                    // Imágenes
                    if (assetInfo.name && /\.(png|jpe?g|gif|svg|webp|avif|ico)$/.test(assetInfo.name)) {
                        return 'assets/img/[name]-[hash].[ext]';
                    }
                    return 'assets/[name]-[hash].[ext]';
                }
            }
        }
    },
    // Optimizaciones adicionales
    esbuild: {
        // Eliminar console.log en producción
        drop: command === 'build' ? ['console', 'debugger'] : [],
    },
    // Optimizar dependencias
    optimizeDeps: {
        include: ['axios'],
    },
}));
