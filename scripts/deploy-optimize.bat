@echo off
REM =============================================================================
REM Script de Optimización para Producción - Laravel (Windows)
REM =============================================================================
REM Este script optimiza Laravel para máximo rendimiento en producción.
REM EJECUTAR DESPUÉS DE CADA DEPLOY
REM
REM Uso: scripts\deploy-optimize.bat
REM =============================================================================

echo ==============================================
echo    Optimizando Laravel para Produccion
echo ==============================================

REM Verificar que estamos en el directorio correcto
if not exist "artisan" (
    echo Error: No se encontro artisan. Ejecuta este script desde la raiz del proyecto.
    exit /b 1
)

REM 1. Limpiar caches anteriores
echo.
echo [1/7] Limpiando caches anteriores...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan event:clear
echo OK - Caches limpiados

REM 2. Cachear configuracion
echo.
echo [2/7] Cacheando configuracion...
php artisan config:cache
echo OK - Configuracion cacheada

REM 3. Cachear rutas
echo.
echo [3/7] Cacheando rutas...
php artisan route:cache
echo OK - Rutas cacheadas

REM 4. Cachear vistas Blade
echo.
echo [4/7] Cacheando vistas Blade...
php artisan view:cache
echo OK - Vistas cacheadas

REM 5. Cachear eventos
echo.
echo [5/7] Cacheando eventos...
php artisan event:cache
echo OK - Eventos cacheados

REM 6. Optimizar autoloader de Composer
echo.
echo [6/7] Optimizando autoloader de Composer...
composer dump-autoload --optimize --no-dev --classmap-authoritative
echo OK - Autoloader optimizado

REM 7. Optimizacion general
echo.
echo [7/7] Ejecutando optimizacion general...
php artisan optimize
echo OK - Optimizacion completada

echo.
echo ==============================================
echo    OPTIMIZACION COMPLETADA
echo ==============================================
echo.
echo Optimizaciones aplicadas:
echo   - Configuracion cacheada (config:cache)
echo   - Rutas cacheadas (route:cache)
echo   - Vistas Blade compiladas (view:cache)
echo   - Eventos cacheados (event:cache)
echo   - Composer autoloader optimizado
echo.
echo IMPORTANTE: Recuerda tambien:
echo   1. Habilitar PHP OPcache en el servidor
echo   2. Usar 'file' en lugar de 'database' para CACHE_STORE y SESSION_DRIVER
echo   3. Considerar usar Redis si esta disponible
echo.
pause
