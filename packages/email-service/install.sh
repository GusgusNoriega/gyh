#!/bin/bash

# ==============================================
# Script de Instalación Automática
# Email Service Package para Laravel
# ==============================================

echo "🚀 Instalando Email Service Package..."
echo ""

# Verificar que estamos en un proyecto Laravel
if [ ! -f "artisan" ]; then
    echo "❌ Error: Este script debe ejecutarse desde la raíz de un proyecto Laravel"
    exit 1
fi

# Ruta del paquete
PACKAGE_PATH="packages/email-service"

if [ ! -d "$PACKAGE_PATH" ]; then
    echo "❌ Error: No se encuentra el paquete en $PACKAGE_PATH"
    exit 1
fi

echo "📁 Copiando modelos..."
cp "$PACKAGE_PATH/app/Models/SmtpSetting.php" "app/Models/" 2>/dev/null && echo "   ✅ SmtpSetting.php"
cp "$PACKAGE_PATH/app/Models/EmailTemplate.php" "app/Models/" 2>/dev/null && echo "   ✅ EmailTemplate.php"

echo ""
echo "📁 Copiando controladores..."
cp "$PACKAGE_PATH/app/Http/Controllers/SmtpSettingController.php" "app/Http/Controllers/" 2>/dev/null && echo "   ✅ SmtpSettingController.php"
cp "$PACKAGE_PATH/app/Http/Controllers/EmailTemplateController.php" "app/Http/Controllers/" 2>/dev/null && echo "   ✅ EmailTemplateController.php"

echo ""
echo "📁 Copiando servicios..."
mkdir -p "app/Services"
cp "$PACKAGE_PATH/app/Services/EmailTemplateService.php" "app/Services/" 2>/dev/null && echo "   ✅ EmailTemplateService.php"

echo ""
echo "📁 Copiando migraciones..."
cp "$PACKAGE_PATH/database/migrations/2025_01_01_000001_create_smtp_settings_table.php" "database/migrations/" 2>/dev/null && echo "   ✅ create_smtp_settings_table.php"
cp "$PACKAGE_PATH/database/migrations/2025_01_01_000002_create_email_templates_table.php" "database/migrations/" 2>/dev/null && echo "   ✅ create_email_templates_table.php"

echo ""
echo "📁 Copiando seeder..."
cp "$PACKAGE_PATH/database/seeders/EmailTemplateSeeder.php" "database/seeders/" 2>/dev/null && echo "   ✅ EmailTemplateSeeder.php"

echo ""
echo "📁 Copiando configuración..."
cp "$PACKAGE_PATH/config/email-service.php" "config/" 2>/dev/null && echo "   ✅ email-service.php"

echo ""
echo "=========================================="
echo "✅ Archivos copiados exitosamente!"
echo "=========================================="
echo ""
echo "📋 Pasos siguientes:"
echo ""
echo "1. Verifica tu Controller base (app/Http/Controllers/Controller.php)"
echo "   debe tener los métodos apiSuccess, apiError, etc."
echo "   Ver: $PACKAGE_PATH/app/Http/Controllers/Controller.php"
echo ""
echo "2. Agrega las rutas a routes/api.php:"
echo "   Ver: $PACKAGE_PATH/routes/api.php"
echo ""
echo "3. Ejecuta las migraciones:"
echo "   php artisan migrate"
echo ""
echo "4. (Opcional) Cargar plantillas de ejemplo:"
echo "   php artisan db:seed --class=EmailTemplateSeeder"
echo ""
echo "5. Configura SMTP en .env o través de la API"
echo ""
echo "📖 Para más información, consulta:"
echo "   - $PACKAGE_PATH/README.md"
echo "   - $PACKAGE_PATH/INSTALL.md"
echo ""