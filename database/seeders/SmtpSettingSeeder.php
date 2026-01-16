<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmtpSetting;

class SmtpSettingSeeder extends Seeder
{
    /**
     * Seeder para configurar los valores SMTP por defecto.
     * 
     * Ejecutar con: php artisan db:seed --class=SmtpSettingSeeder
     * 
     * IMPORTANTE: Modifica los valores de este archivo antes de ejecutar
     * el seeder en un nuevo servidor. Los valores actuales son de ejemplo.
     * 
     * Para cambiar la configuración después de ejecutar el seeder:
     * - Opción 1: Accede a /admin/smtp-settings en el dashboard
     * - Opción 2: Modifica directamente la tabla smtp_settings en la base de datos
     * - Opción 3: Actualiza los valores en este seeder y vuelve a ejecutarlo
     */
    public function run(): void
    {
        // =====================================================================
        // CONFIGURACIÓN SMTP POR DEFECTO
        // =====================================================================
        // Modifica estos valores según tu servidor de correo antes de ejecutar
        // el seeder. La contraseña se almacena en texto plano en la base de datos.
        // =====================================================================

        $smtpConfig = [
            // Servidor SMTP
            'smtp_host' => 'systemsgg.com',

            // Puerto: 465 (SSL), 587 (TLS), 25 (sin cifrar)
            'smtp_port' => '465',

            // Encriptación: 'ssl', 'tls', o '' (vacío para ninguna)
            'smtp_encryption' => 'ssl',

            // Usuario/correo de autenticación
            'smtp_username' => 'hola@systemsgg.com',

            // Contraseña del correo (ficticia a propósito)
            // IMPORTANTE: Cambia esta contraseña manualmente en producción
            'smtp_password' => 'CAMBIAR_EN_PRODUCCION',

            // Email que aparecerá como remitente
            'smtp_from_address' => 'hola@systemsgg.com',

            // Nombre que aparecerá como remitente
            'smtp_from_name' => config('app.name', 'Mi Aplicación'),
        ];

        // Descripciones para cada configuración
        $descriptions = [
            'smtp_host' => 'Servidor SMTP (por ejemplo: smtp.gmail.com, systemsgg.com)',
            'smtp_port' => 'Puerto SMTP (465 para SSL, 587 para TLS, 25 sin cifrar)',
            'smtp_encryption' => 'Tipo de encriptación (ssl, tls, o vacío)',
            'smtp_username' => 'Usuario/correo de autenticación SMTP',
            'smtp_password' => 'Contraseña de la cuenta SMTP',
            'smtp_from_address' => 'Correo remitente por defecto (from address)',
            'smtp_from_name' => 'Nombre visible del remitente',
        ];

        // Actualizar o crear cada configuración
        foreach ($smtpConfig as $key => $value) {
            SmtpSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'description' => $descriptions[$key] ?? null,
                ]
            );
        }

        // Limpiar cache de configuraciones
        SmtpSetting::clearCache();

        $this->command->info('✅ Configuración SMTP actualizada exitosamente.');
        $this->command->info('');
        $this->command->info('Configuración aplicada:');
        $this->command->info("  Host: {$smtpConfig['smtp_host']}");
        $this->command->info("  Puerto: {$smtpConfig['smtp_port']}");
        $this->command->info("  Encriptación: {$smtpConfig['smtp_encryption']}");
        $this->command->info("  Usuario: {$smtpConfig['smtp_username']}");
        $this->command->info("  From: {$smtpConfig['smtp_from_address']}");
        $this->command->info("  Nombre: {$smtpConfig['smtp_from_name']}");
        $this->command->info('');
        $this->command->info('Para cambiar la configuración:');
        $this->command->info('  1. Accede a /admin/smtp-settings en el dashboard');
        $this->command->info('  2. O edita este seeder y vuelve a ejecutarlo');
    }
}
