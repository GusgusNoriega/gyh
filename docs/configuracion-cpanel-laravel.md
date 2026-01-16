# Configuración Correcta de Laravel en cPanel

## Problema Actual

Tu estructura actual causa TTFB alto porque:
1. El dominio apunta a `public_html`
2. `public_html/index.html` redirige a `laravel_apps/systemsgg`
3. Esto añade latencia y problemas de caché

```
/home/usuario/
├── public_html/          ← Dominio apunta aquí (❌ incorrecto)
│   └── index.html        ← Redirección (❌ lento)
├── laravel_apps/
│   └── systemsgg/        ← Tu proyecto Laravel
│       └── public/       ← Debería ser el DocumentRoot
```

## Solución 1: Symlink (RECOMENDADO)

Esta es la forma más limpia y rápida.

### Paso 1: Backup de public_html
```bash
# Conectar por SSH al servidor
cd ~
mv public_html public_html_backup
```

### Paso 2: Crear symlink
```bash
# Crear symlink de public_html -> laravel_apps/systemsgg/public
ln -s /home/TU_USUARIO/laravel_apps/systemsgg/public /home/TU_USUARIO/public_html
```

### Paso 3: Verificar permisos
```bash
# Asegurar permisos correctos
chmod 755 /home/gustavo/laravel_apps/systemsgg
chmod -R 755 /home/gustavo/laravel_apps/systemsgg/public
chmod -R 775 /home/gustavo/laravel_apps/systemsgg/storage
chmod -R 775 /home/gustavo/laravel_apps/systemsgg/bootstrap/cache
```

### Paso 4: Ejecutar optimizaciones
```bash
cd /home/TU_USUARIO/laravel_apps/systemsgg
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## Solución 2: .htaccess en public_html (Si no puedes usar symlink)

Si no puedes borrar `public_html`, usa este `.htaccess`:

### Crear/Editar public_html/.htaccess
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirigir todo a la carpeta public de Laravel
    RewriteCond %{REQUEST_URI} !^/laravel_apps/systemsgg/public/
    RewriteRule ^(.*)$ /laravel_apps/systemsgg/public/$1 [L]
</IfModule>
```

**NOTA:** Esta solución es más lenta que el symlink.

---

## Solución 3: Cambiar DocumentRoot en cPanel (Mejor si tienes acceso)

1. Ir a **cPanel > Dominios > Dominios**
2. Editar el dominio `systemsgg.com`
3. Cambiar **Document Root** a: `/home/TU_USUARIO/laravel_apps/systemsgg/public`
4. Guardar

---

## Después de Aplicar Cualquier Solución

### 1. Verificar el .env en el servidor
```bash
cd /home/TU_USUARIO/laravel_apps/systemsgg
nano .env
```

Asegurar que tenga:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://systemsgg.com

CACHE_STORE=file
SESSION_DRIVER=file
```

### 2. Ejecutar optimizaciones de Laravel
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

### 3. Limpiar el autoloader de Composer
```bash
composer dump-autoload --optimize --no-dev
```

### 4. Verificar permisos de storage
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R $(whoami):$(whoami) storage bootstrap/cache
```

---

## Verificar que Funciona

Después de aplicar la solución, ejecuta:

```bash
curl -w "TTFB: %{time_starttransfer}s\n" -o /dev/null -s https://systemsgg.com/
```

El TTFB debería bajar de 9-21 segundos a menos de 1 segundo.

---

## Resumen de Comandos (Copiar y Pegar)

```bash
# Backup y crear symlink
cd ~
mv public_html public_html_backup
ln -s /home/TU_USUARIO/laravel_apps/systemsgg/public /home/TU_USUARIO/public_html

# Ir al proyecto
cd /home/TU_USUARIO/laravel_apps/systemsgg

# Permisos
chmod -R 775 storage bootstrap/cache

# Optimizar Laravel
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
composer dump-autoload --optimize --no-dev
```

**IMPORTANTE:** Reemplaza `TU_USUARIO` con tu nombre de usuario real de cPanel.
