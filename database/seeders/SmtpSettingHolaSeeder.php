<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmtpSetting;

class SmtpSettingHolaSeeder extends Seeder
{
    /**
     * Configuración SMTP por defecto para el correo hola@systemsgg.com.
     *
     * Ejecutar con:
     * php artisan db:seed --class=SmtpSettingHolaSeeder
     *
     * IMPORTANTE:
     * - La contraseña es ficticia a propósito. Reemplázala manualmente en producción.
     * - También se setea el destino por defecto de notificación de leads.
     */
    public function run(): void
    {
        $smtpConfig = [
            'smtp_host' => 'systemsgg.com',
            'smtp_port' => '465',
            'smtp_encryption' => 'ssl',
            'smtp_username' => 'hola@systemsgg.com',
            'smtp_password' => 'CAMBIAR_EN_PRODUCCION',
            'smtp_from_address' => 'hola@systemsgg.com',
            'smtp_from_name' => config('app.name', 'Mi Aplicación'),

            // Destinatarios para leads (pueden ser varios separados por coma)
            'leads_notification_emails' => 'gusgusnoriega@gmail.com, gxstxvx2000@gmail.com',
        ];

        $descriptions = [
            'smtp_host' => 'Servidor SMTP (por ejemplo: smtp.gmail.com, systemsgg.com)',
            'smtp_port' => 'Puerto SMTP (465 para SSL, 587 para TLS, 25 sin cifrar)',
            'smtp_encryption' => 'Tipo de encriptación (ssl, tls, o vacío)',
            'smtp_username' => 'Usuario/correo de autenticación SMTP',
            'smtp_password' => 'Contraseña de la cuenta SMTP (CAMBIAR EN PRODUCCIÓN)',
            'smtp_from_address' => 'Correo remitente por defecto (from address)',
            'smtp_from_name' => 'Nombre visible del remitente',
            'leads_notification_emails' => 'Correos destino para notificación de leads (separados por coma o punto y coma)',
        ];

        foreach ($smtpConfig as $key => $value) {
            SmtpSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'description' => $descriptions[$key] ?? null,
                ]
            );
        }

        SmtpSetting::clearCache();

        $this->command->info('✅ Configuración SMTP (hola@systemsgg.com) aplicada.');
        $this->command->info('⚠️  Recuerda cambiar smtp_password manualmente en producción.');
    }
}

