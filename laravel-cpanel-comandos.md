# Comandos útiles para Laravel en cPanel Terminal (PHP 8.3) – systemsgg

> **Importante:** En este servidor el PHP 8.3 (CLI) es:  
> `/opt/alt/php83/usr/bin/php`  
> **Todos** los comandos abajo ya vienen listos para copiar/pegar con esa ruta.

---

## 0) Entrar al proyecto (siempre)
```bash
cd ~/laravel_apps/systemsgg
```

---

## 1) Diagnóstico rápido

### Versiones
```bash
/opt/alt/php83/usr/bin/php -v
/opt/alt/php83/usr/bin/php artisan --version
/opt/alt/php83/usr/bin/php artisan about
```

### Ver variables clave del .env (sin mostrar password)
```bash
grep -E "APP_ENV|APP_DEBUG|APP_URL|FILESYSTEM_DISK|DB_CONNECTION|DB_HOST|DB_DATABASE|DB_USERNAME|SESSION_DRIVER" .env | sed 's/DB_PASSWORD=.*/DB_PASSWORD=*****/'
```

### Logs (si existen)
```bash
tail -n 120 storage/logs/laravel.log 2>/dev/null
tail -n 120 ~/public_html/error_log 2>/dev/null
```

---

## 2) Git: dejar el server igual que GitHub (opcional)

> **Esto borra** cambios locales y archivos no rastreados dentro del repo. Úsalo cuando quieres que el servidor sea “solo deploy”.
```bash
cd ~/laravel_apps/systemsgg
git fetch origin
git reset --hard origin/main
git clean -fd
```

> Si prefieres guardar cambios locales temporalmente (stash):
```bash
cd ~/laravel_apps/systemsgg
git stash push -u -m "WIP server before pull"
git pull origin main
# Para recuperar:
# git stash pop
```

---

## 3) Deploy rápido (Git + Composer + Artisan + publicar)

### Pull y dependencias (Composer con PHP 8.3)
```bash
cd ~/laravel_apps/systemsgg
git pull origin main
/opt/alt/php83/usr/bin/php /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader
```

### Migraciones (producción)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan migrate --force
```

### Limpieza + caches (producción)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan optimize:clear
/opt/alt/php83/usr/bin/php artisan config:cache
/opt/alt/php83/usr/bin/php artisan route:cache
/opt/alt/php83/usr/bin/php artisan view:cache
```

### Si tu web sirve desde `public_html` (copiar public/)
```bash
cd ~/laravel_apps/systemsgg
cp -R public/* ~/public_html/
```

---

## 4) Cache / Vistas / Config (cuando “no se ven cambios”)

### Limpiar TODO
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan optimize:clear
```

### Solo vistas
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan view:clear
/opt/alt/php83/usr/bin/php artisan view:cache
```

### Solo rutas
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan route:clear
/opt/alt/php83/usr/bin/php artisan route:cache
```

### Solo config
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan config:clear
/opt/alt/php83/usr/bin/php artisan config:cache
```

---

## 5) Storage / imágenes (public_html + /storage)

### Symlink correcto para que funcione `/storage/...` en el dominio
```bash
rm -rf ~/public_html/storage
ln -s ~/laravel_apps/systemsgg/storage/app/public ~/public_html/storage
ls -la ~/public_html/storage
```

### Permisos típicos
```bash
chmod -R 775 ~/laravel_apps/systemsgg/storage ~/laravel_apps/systemsgg/bootstrap/cache
chmod -R 775 ~/laravel_apps/systemsgg/storage/app/public
```

---

## 6) APP_KEY (clave de aplicación)

### Si no existe .env
```bash
cd ~/laravel_apps/systemsgg
cp .env.example .env
```

### Generar APP_KEY
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan key:generate
```

### Forzar regeneración (cuidado)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan key:generate --force
```

---

## 7) Base de datos / migraciones / seed

### Migrar (producción)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan migrate --force
```

### Fresh (borra todo) + seed (solo DEV)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan migrate:fresh --seed
```

### Seed normal
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan db:seed
```

### Ejecutar un seeder específico
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan db:seed --class=Database\\Seeders\\ColorThemeSeeder
```

### Tabla sessions (si `SESSION_DRIVER=database`)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan session:table
/opt/alt/php83/usr/bin/php artisan migrate --force
```

---

## 8) Passport (tokens, keys)

### Generar llaves Passport
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan passport:keys
```

### Forzar regeneración (cuidado: puede invalidar tokens)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan passport:keys --force
```

### Permisos correctos para llaves (evita 500)
```bash
cd ~/laravel_apps/systemsgg
chmod 600 storage/oauth-private.key
chmod 600 storage/oauth-public.key
ls -la storage/oauth-*.key
```

### Instalar Passport (si no está instalado)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan passport:install
```

---

## 9) Cola / Jobs / Scheduler

### Cola en foreground (prueba)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan queue:work
```

### Reiniciar workers (si hay supervisor/daemon)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan queue:restart
```

### Ver scheduler (lista de tareas)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan schedule:list
```

### Ejecutar scheduler una vez
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan schedule:run
```

---

## 10) Mantenimiento

### Poner en mantenimiento
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan down
```

### Quitar mantenimiento
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan up
```

---

## 11) Tinker (consultas rápidas)

### Abrir tinker
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan tinker
```

### Tinker sin modo interactivo (ejecuta y sale)
```bash
cd ~/laravel_apps/systemsgg
/opt/alt/php83/usr/bin/php artisan tinker --execute="dump(config('app.url'));"
/opt/alt/php83/usr/bin/php artisan tinker --execute="dump(config('filesystems.default'));"
/opt/alt/php83/usr/bin/php artisan tinker --execute="dump(DB::connection()->getDatabaseName());"
/opt/alt/php83/usr/bin/php artisan tinker --execute="dump(App\\Models\\User::count());"
```

---

## 12) Assets de Vite (CSS/JS compilados) ⚠️ IMPORTANTE

### Problema común: estilos no cargan en producción
Si los estilos CSS no cargan después de hacer deploy, es porque la carpeta `build` en `public_html` no está sincronizada con la del proyecto.

### Solución A: Enlace simbólico (RECOMENDADO - solo una vez)
```bash
# Eliminar carpeta build existente y crear enlace simbólico
rm -rf ~/public_html/build
ln -s ~/laravel_apps/systemsgg/public/build ~/public_html/build
ls -la ~/public_html/build
```
> Con esto, cada vez que actualices el repo, los assets se actualizan automáticamente.

### Solución B: Copiar manualmente (después de cada deploy)
```bash
cd ~/laravel_apps/systemsgg
rm -rf ~/public_html/build
cp -R public/build ~/public_html/
```

### Verificar que los assets existen
```bash
ls -la ~/laravel_apps/systemsgg/public/build/
ls -la ~/laravel_apps/systemsgg/public/build/.vite/
ls -la ~/public_html/build/
```

---

## 13) Deploy en una sola línea (copia/pega)

> **No borra cambios locales**. Si tienes cambios locales, primero usa `git stash -u` o el reset duro del punto 2.

### Con copia de public (tradicional):
```bash
cd ~/laravel_apps/systemsgg && git pull origin main && /opt/alt/php83/usr/bin/php /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader && /opt/alt/php83/usr/bin/php artisan migrate --force && /opt/alt/php83/usr/bin/php artisan optimize:clear && /opt/alt/php83/usr/bin/php artisan config:cache && /opt/alt/php83/usr/bin/php artisan route:cache && /opt/alt/php83/usr/bin/php artisan view:cache && rm -rf ~/public_html/build && cp -R public/* ~/public_html/
```

### Si ya configuraste el enlace simbólico para /build (más rápido):
```bash
cd ~/laravel_apps/systemsgg && git pull origin main && /opt/alt/php83/usr/bin/php /opt/cpanel/composer/bin/composer install --no-dev --optimize-autoloader && /opt/alt/php83/usr/bin/php artisan migrate --force && /opt/alt/php83/usr/bin/php artisan optimize:clear && /opt/alt/php83/usr/bin/php artisan config:cache && /opt/alt/php83/usr/bin/php artisan route:cache && /opt/alt/php83/usr/bin/php artisan view:cache
```

---

### Notas rápidas
- Si cambias `.env` y usas `config:cache`, recuerda ejecutar:
  ```bash
  /opt/alt/php83/usr/bin/php artisan config:clear
  ```
  o mejor:
  ```bash
  /opt/alt/php83/usr/bin/php artisan optimize:clear
  ```
- En producción usa:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  ```
