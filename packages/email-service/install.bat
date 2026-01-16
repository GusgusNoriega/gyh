@echo off
REM ==============================================
REM Script de Instalación Automática (Windows)
REM Email Service Package para Laravel
REM ==============================================

echo 🚀 Instalando Email Service Package...
echo.

REM Verificar que estamos en un proyecto Laravel
if not exist "artisan" (
    echo ❌ Error: Este script debe ejecutarse desde la raíz de un proyecto Laravel
    exit /b 1
)

REM Ruta del paquete
set PACKAGE_PATH=packages\email-service

if not exist "%PACKAGE_PATH%" (
    echo ❌ Error: No se encuentra el paquete en %PACKAGE_PATH%
    exit /b 1
)

echo 📁 Copiando modelos...
copy /Y "%PACKAGE_PATH%\app\Models\SmtpSetting.php" "app\Models\" >nul 2>&1 && echo    ✅ SmtpSetting.php
copy /Y "%PACKAGE_PATH%\app\Models\EmailTemplate.php" "app\Models\" >nul 2>&1 && echo    ✅ EmailTemplate.php

echo.
echo 📁 Copiando controladores...
copy /Y "%PACKAGE_PATH%\app\Http\Controllers\SmtpSettingController.php" "app\Http\Controllers\" >nul 2>&1 && echo    ✅ SmtpSettingController.php
copy /Y "%PACKAGE_PATH%\app\Http\Controllers\EmailTemplateController.php" "app\Http\Controllers\" >nul 2>&1 && echo    ✅ EmailTemplateController.php

echo.
echo 📁 Copiando servicios...
if not exist "app\Services" mkdir "app\Services"
copy /Y "%PACKAGE_PATH%\app\Services\EmailTemplateService.php" "app\Services\" >nul 2>&1 && echo    ✅ EmailTemplateService.php

echo.
echo 📁 Copiando migraciones...
copy /Y "%PACKAGE_PATH%\database\migrations\2025_01_01_000001_create_smtp_settings_table.php" "database\migrations\" >nul 2>&1 && echo    ✅ create_smtp_settings_table.php
copy /Y "%PACKAGE_PATH%\database\migrations\2025_01_01_000002_create_email_templates_table.php" "database\migrations\" >nul 2>&1 && echo    ✅ create_email_templates_table.php

echo.
echo 📁 Copiando seeder...
copy /Y "%PACKAGE_PATH%\database\seeders\EmailTemplateSeeder.php" "database\seeders\" >nul 2>&1 && echo    ✅ EmailTemplateSeeder.php

echo.
echo 📁 Copiando configuración...
copy /Y "%PACKAGE_PATH%\config\email-service.php" "config\" >nul 2>&1 && echo    ✅ email-service.php

echo.
echo ==========================================
echo ✅ Archivos copiados exitosamente!
echo ==========================================
echo.
echo 📋 Pasos siguientes:
echo.
echo 1. Verifica tu Controller base (app\Http\Controllers\Controller.php)
echo    debe tener los métodos apiSuccess, apiError, etc.
echo    Ver: %PACKAGE_PATH%\app\Http\Controllers\Controller.php
echo.
echo 2. Agrega las rutas a routes\api.php:
echo    Ver: %PACKAGE_PATH%\routes\api.php
echo.
echo 3. Ejecuta las migraciones:
echo    php artisan migrate
echo.
echo 4. (Opcional) Cargar plantillas de ejemplo:
echo    php artisan db:seed --class=EmailTemplateSeeder
echo.
echo 5. Configura SMTP en .env o través de la API
echo.
echo 📖 Para más información, consulta:
echo    - %PACKAGE_PATH%\README.md
echo    - %PACKAGE_PATH%\INSTALL.md
echo.

pause