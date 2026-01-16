#!/bin/bash
# =============================================================================
# Script de Optimización para Producción - Laravel
# =============================================================================
# Este script optimiza Laravel para máximo rendimiento en producción.
# EJECUTAR DESPUÉS DE CADA DEPLOY
#
# Uso: bash scripts/deploy-optimize.sh
# =============================================================================

set -e  # Salir si hay errores

echo "=============================================="
echo "🚀 Optimizando Laravel para Producción"
echo "=============================================="

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: No se encontró artisan. Ejecuta este script desde la raíz del proyecto.${NC}"
    exit 1
fi

# 1. Limpiar cachés anteriores
echo -e "\n${YELLOW}[1/7] Limpiando cachés anteriores...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan event:clear
echo -e "${GREEN}✓ Cachés limpiados${NC}"

# 2. Cachear configuración (MUY IMPORTANTE para TTFB)
echo -e "\n${YELLOW}[2/7] Cacheando configuración...${NC}"
php artisan config:cache
echo -e "${GREEN}✓ Configuración cacheada${NC}"

# 3. Cachear rutas
echo -e "\n${YELLOW}[3/7] Cacheando rutas...${NC}"
php artisan route:cache
echo -e "${GREEN}✓ Rutas cacheadas${NC}"

# 4. Cachear vistas Blade
echo -e "\n${YELLOW}[4/7] Cacheando vistas Blade...${NC}"
php artisan view:cache
echo -e "${GREEN}✓ Vistas cacheadas${NC}"

# 5. Cachear eventos
echo -e "\n${YELLOW}[5/7] Cacheando eventos...${NC}"
php artisan event:cache
echo -e "${GREEN}✓ Eventos cacheados${NC}"

# 6. Optimizar autoloader de Composer
echo -e "\n${YELLOW}[6/7] Optimizando autoloader de Composer...${NC}"
composer dump-autoload --optimize --no-dev --classmap-authoritative
echo -e "${GREEN}✓ Autoloader optimizado${NC}"

# 7. Optimización general de Laravel
echo -e "\n${YELLOW}[7/7] Ejecutando optimización general...${NC}"
php artisan optimize
echo -e "${GREEN}✓ Optimización completada${NC}"

# Resumen
echo -e "\n=============================================="
echo -e "${GREEN}✅ OPTIMIZACIÓN COMPLETADA${NC}"
echo "=============================================="
echo ""
echo "Optimizaciones aplicadas:"
echo "  • Configuración cacheada (config:cache)"
echo "  • Rutas cacheadas (route:cache)"
echo "  • Vistas Blade compiladas (view:cache)"
echo "  • Eventos cacheados (event:cache)"
echo "  • Composer autoloader optimizado"
echo ""
echo "📝 IMPORTANTE: Recuerda también:"
echo "  1. Habilitar PHP OPcache en el servidor"
echo "  2. Usar 'file' en lugar de 'database' para CACHE_STORE y SESSION_DRIVER"
echo "  3. Considerar usar Redis si está disponible"
echo ""
echo "Para verificar OPcache:"
echo "  php -i | grep opcache"
echo ""
