#!/bin/bash
# =============================================================================
# Script para configurar enlaces simbólicos en cPanel
# Ejecutar una sola vez en el servidor vía SSH o Terminal de cPanel
# =============================================================================

# Configuración - Ajusta estas rutas según tu servidor
PROJECT_PATH="$HOME/laravel_apps/systemsgg"
PUBLIC_HTML_PATH="$HOME/public_html"

echo "=== Configurando enlaces simbólicos para Laravel en cPanel ==="

# 1. Eliminar carpeta build existente en public_html (si existe)
if [ -d "$PUBLIC_HTML_PATH/build" ]; then
    echo "Eliminando carpeta build existente en public_html..."
    rm -rf "$PUBLIC_HTML_PATH/build"
fi

# 2. Crear enlace simbólico para la carpeta build
echo "Creando enlace simbólico para /build..."
ln -sf "$PROJECT_PATH/public/build" "$PUBLIC_HTML_PATH/build"

# 3. Verificar que el enlace se creó correctamente
if [ -L "$PUBLIC_HTML_PATH/build" ]; then
    echo "✓ Enlace simbólico creado exitosamente: public_html/build -> $PROJECT_PATH/public/build"
else
    echo "✗ Error al crear el enlace simbólico"
    exit 1
fi

# 4. También crear enlace para storage si no existe
if [ ! -L "$PUBLIC_HTML_PATH/storage" ]; then
    echo "Creando enlace simbólico para /storage..."
    ln -sf "$PROJECT_PATH/storage/app/public" "$PUBLIC_HTML_PATH/storage"
    echo "✓ Enlace simbólico storage creado"
fi

echo ""
echo "=== Configuración completada ==="
echo "Los assets de Vite ahora se servirán automáticamente desde el proyecto Laravel."
echo ""
echo "IMPORTANTE: Cada vez que hagas 'npm run build' y subas al repositorio,"
echo "los cambios se reflejarán automáticamente en public_html/build"
