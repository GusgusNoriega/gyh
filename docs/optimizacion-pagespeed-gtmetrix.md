# Guía de Optimización para PageSpeed Insights y GTmetrix

Este documento describe las optimizaciones implementadas en el proyecto para lograr altos puntajes en PageSpeed Insights y GTmetrix.

## ⚡ PROBLEMA CRÍTICO: TTFB Alto (Tiempo de Respuesta del Servidor)

Si GTmetrix muestra **"Reduce initial server response time"** con más de 600ms, esto es lo que más impacta tu puntaje. Un TTFB de 3+ segundos es **muy alto**.

### Solución Inmediata (Ejecutar en Producción)

```bash
# 1. Cambiar de 'database' a 'file' en .env (MÁS IMPORTANTE)
CACHE_STORE=file
SESSION_DRIVER=file

# 2. Ejecutar script de optimización
bash scripts/deploy-optimize.sh

# O manualmente:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
composer dump-autoload --optimize --no-dev --classmap-authoritative
```

### Causas del TTFB Alto

| Causa | Impacto | Solución |
|-------|---------|----------|
| `CACHE_STORE=database` | ALTO | Cambiar a `file` o `redis` |
| `SESSION_DRIVER=database` | ALTO | Cambiar a `file` o `redis` |
| Sin config:cache | ALTO | `php artisan config:cache` |
| Sin route:cache | MEDIO | `php artisan route:cache` |
| Sin OPcache | ALTO | Habilitar en php.ini |
| Hosting compartido lento | ALTO | Considerar VPS o mejorar plan |
| Sin CDN | MEDIO | Usar Cloudflare (gratis) |


## Resumen de Optimizaciones Implementadas

### 1. Configuración de Vite (`vite.config.js`)

- **Minificación con Terser**: Compresión avanzada de JavaScript
- **Eliminación de console.log**: En producción se eliminan automáticamente
- **Code splitting**: Chunks separados para vendor y código de la aplicación
- **Tree shaking**: Eliminación de código muerto
- **CSS code splitting**: CSS separado para mejor caché

### 2. Configuración de Apache (`.htaccess`)

#### Compresión
- **GZIP**: Compresión de HTML, CSS, JS, JSON, SVG y fuentes
- **Brotli**: Si está disponible en el servidor (más eficiente que GZIP)

#### Caché del Navegador
| Tipo de Recurso | Tiempo de Caché |
|-----------------|-----------------|
| HTML | Sin caché (dinámico) |
| CSS/JS (con hash) | 1 año (immutable) |
| Imágenes | 1 año |
| Fuentes | 1 año |
| JSON/API | Sin caché |

#### Headers de Seguridad
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `X-XSS-Protection: 1; mode=block`
- `Strict-Transport-Security` (HSTS)
- `Referrer-Policy: strict-origin-when-cross-origin`

### 3. Optimización de Layouts

#### CSS Crítico Inline
Se incluye CSS crítico directamente en el `<head>` para:
- Evitar Flash of Unstyled Content (FOUC)
- Mejorar First Contentful Paint (FCP)
- Reducir Largest Contentful Paint (LCP)

#### Preload de Recursos
```html
<link rel="preload" href="..." as="style" />
<link rel="preconnect" href="https://external-domain.com" />
<link rel="dns-prefetch" href="//external-domain.com" />
```

#### Alpine.js Optimizado
- Versión fija (evita caché-busting innecesario)
- Cargado con `defer` (no bloquea renderizado)

### 4. Componente de Imagen Optimizada

Usa el componente `<x-optimized-image>`:

```blade
{{-- Imagen con lazy loading (por defecto) --}}
<x-optimized-image 
    src="/img/ejemplo.jpg" 
    alt="Descripción"
    width="800"
    height="600"
/>

{{-- Imagen prioritaria (above the fold) --}}
<x-optimized-image 
    src="/img/hero.jpg" 
    alt="Hero image"
    width="1200"
    height="600"
    :priority="true"
/>

{{-- Imagen responsive --}}
<x-optimized-image 
    src="/img/producto.jpg" 
    alt="Producto"
    srcset="/img/producto-400.jpg 400w, /img/producto-800.jpg 800w"
    sizes="(max-width: 600px) 400px, 800px"
/>
```

Características:
- Lazy loading nativo del navegador
- Soporte automático para WebP (si existe)
- Atributos `width` y `height` para evitar CLS
- `fetchpriority="high"` para imágenes críticas
- `decoding="async"` para mejor rendimiento

---

## Checklist de Mejores Prácticas

### Core Web Vitals

#### LCP (Largest Contentful Paint) < 2.5s
- [ ] Imágenes hero/principales con `priority="true"`
- [ ] CSS crítico inline
- [ ] Preload de imágenes LCP
- [ ] Servidor con buen TTFB (< 600ms)
- [ ] CDN para assets estáticos

#### FID/INP (Interactividad) < 200ms
- [ ] JavaScript con `defer`
- [ ] Evitar bloques de JS largos (> 50ms)
- [ ] Code splitting de chunks grandes
- [ ] Web Workers para tareas pesadas

#### CLS (Cumulative Layout Shift) < 0.1
- [ ] Dimensiones en imágenes (`width`, `height`)
- [ ] Reservar espacio para contenido dinámico
- [ ] Fuentes con `font-display: swap`
- [ ] Evitar insertar contenido encima del visible

### Optimización de Imágenes

```bash
# Convertir a WebP (menor tamaño, misma calidad)
# Instalación: brew install webp (Mac) / apt install webp (Linux)

# Convertir JPG a WebP
cwebp -q 85 imagen.jpg -o imagen.webp

# Convertir PNG a WebP (con transparencia)
cwebp -q 85 -alpha_q 100 imagen.png -o imagen.webp

# Batch conversion
for f in public/img/*.jpg; do cwebp -q 85 "$f" -o "${f%.jpg}.webp"; done
```

### Checklist Pre-Deploy

1. **Build de producción**
   ```bash
   npm run build
   ```

2. **Verificar tamaño de chunks**
   - CSS total < 50KB (gzip)
   - JS total < 150KB (gzip)
   - Chunk individual < 100KB

3. **Verificar caché**
   ```bash
   curl -I https://tudominio.com/build/assets/app-XXXXX.css
   # Debe mostrar: Cache-Control: public, max-age=31536000, immutable
   ```

4. **Verificar compresión**
   ```bash
   curl -H "Accept-Encoding: gzip,br" -I https://tudominio.com
   # Debe mostrar: Content-Encoding: gzip (o br para Brotli)
   ```

5. **Probar con herramientas**
   - [PageSpeed Insights](https://pagespeed.web.dev/)
   - [GTmetrix](https://gtmetrix.com/)
   - [WebPageTest](https://www.webpagetest.org/)
   - [Chrome DevTools Lighthouse](chrome://lighthouse)

---

## Configuración del Servidor (Opcional)

### PHP OPcache (php.ini)
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.validate_timestamps=0  ; En producción
```

### Laravel Optimizations
```bash
# En producción:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

### Redis/Memcached para Sessions y Caché
```env
# .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

## Solución de Problemas Comunes

### "Serve static assets with an efficient cache policy"
**Causa**: Headers de caché no configurados
**Solución**: Verificar que `.htaccess` se está aplicando

```bash
curl -I https://tudominio.com/build/assets/app.css | grep -i cache
```

### "Enable text compression"
**Causa**: mod_deflate o mod_brotli no activo
**Solución**: Habilitar en Apache

```bash
# En servidor
sudo a2enmod deflate
sudo a2enmod brotli  # Si está disponible
sudo systemctl restart apache2
```

### "Reduce initial server response time"
**Causa**: TTFB alto
**Solución**:
- PHP OPcache habilitado
- Base de datos optimizada
- Cache de consultas frecuentes
- CDN para distribución geográfica

### "Eliminate render-blocking resources"
**Causa**: CSS/JS bloqueando renderizado
**Solución**:
- CSS crítico inline (ya implementado)
- JavaScript con `defer`
- No usar `@import` en CSS

### "Properly size images"
**Causa**: Imágenes más grandes de lo necesario
**Solución**:
- Redimensionar al tamaño de visualización
- Usar `srcset` para responsive
- Servir WebP/AVIF

---

## Monitoreo Continuo

### Google Search Console
- Core Web Vitals report
- Mobile usability

### Real User Monitoring (RUM)
```javascript
// En tu app.js (opcional)
if ('PerformanceObserver' in window) {
    // LCP
    new PerformanceObserver((entryList) => {
        const entries = entryList.getEntries();
        const lastEntry = entries[entries.length - 1];
        console.log('LCP:', lastEntry.startTime);
    }).observe({type: 'largest-contentful-paint', buffered: true});
    
    // CLS
    new PerformanceObserver((entryList) => {
        let cls = 0;
        for (const entry of entryList.getEntries()) {
            if (!entry.hadRecentInput) cls += entry.value;
        }
        console.log('CLS:', cls);
    }).observe({type: 'layout-shift', buffered: true});
}
```

---

## Recursos Adicionales

- [web.dev - Web Vitals](https://web.dev/vitals/)
- [Google PageSpeed Documentation](https://developers.google.com/speed/docs/insights/v5/about)
- [GTmetrix Documentation](https://gtmetrix.com/blog/)
- [Apache mod_deflate](https://httpd.apache.org/docs/current/mod/mod_deflate.html)
- [HTTP Caching - MDN](https://developer.mozilla.org/en-US/docs/Web/HTTP/Caching)
