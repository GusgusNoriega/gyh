<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de plantillas de email.
     * 
     * Ejecutar con: php artisan db:seed --class=EmailTemplateSeeder
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Bienvenida',
                'key' => 'welcome_email',
                'subject' => '¡Bienvenido a [app_name]!',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2563eb;">¡Bienvenido, [nombre]!</h1>
        <p>Gracias por registrarte en <strong>[app_name]</strong>.</p>
        <p>Tu cuenta ha sido creada exitosamente. Ahora puedes acceder a todos nuestros servicios.</p>
        <p style="margin: 30px 0;">
            <a href="[login_url]" style="background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Iniciar Sesión
            </a>
        </p>
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #666;">
            Este correo fue enviado por [app_name]. Si no solicitaste esta cuenta, puedes ignorar este mensaje.
        </p>
    </div>
</body>
</html>',
                'variables_schema' => [
                    'nombre' => ['required' => true, 'description' => 'Nombre del usuario'],
                    'app_name' => ['required' => true, 'description' => 'Nombre de la aplicación'],
                    'login_url' => ['required' => true, 'description' => 'URL de inicio de sesión'],
                ],
                'description' => 'Email de bienvenida para nuevos usuarios',
                'is_active' => true,
            ],
            [
                'name' => 'Recuperación de Contraseña',
                'key' => 'password_reset',
                'subject' => 'Recupera tu contraseña - [app_name]',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recuperación de contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #dc2626;">Recuperación de contraseña</h1>
        <p>Hola <strong>[nombre]</strong>,</p>
        <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en [app_name].</p>
        <p style="margin: 30px 0;">
            <a href="[reset_url]" style="background-color: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Restablecer Contraseña
            </a>
        </p>
        <p><strong>Este enlace expira en [expiration_time].</strong></p>
        <p>Si no solicitaste restablecer tu contraseña, puedes ignorar este correo de forma segura.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #666;">
            Por seguridad, este enlace solo es válido por un tiempo limitado.
        </p>
    </div>
</body>
</html>',
                'variables_schema' => [
                    'nombre' => ['required' => true, 'description' => 'Nombre del usuario'],
                    'app_name' => ['required' => true, 'description' => 'Nombre de la aplicación'],
                    'reset_url' => ['required' => true, 'description' => 'URL para resetear contraseña'],
                    'expiration_time' => ['required' => false, 'description' => 'Tiempo de expiración del enlace'],
                ],
                'description' => 'Email para reseteo de contraseña',
                'is_active' => true,
            ],
            [
                'name' => 'Código de Verificación 2FA',
                'key' => 'two_factor_code',
                'subject' => 'Tu código de verificación: [code]',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Código de Verificación</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #059669;">Verificación de Seguridad</h1>
        <p>Hola <strong>[nombre]</strong>,</p>
        <p>Tu código de verificación es:</p>
        <div style="background-color: #f3f4f6; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #059669;">[code]</span>
        </div>
        <p><strong>Este código expira en [expiration_minutes] minutos.</strong></p>
        <p>Si no solicitaste este código, alguien podría estar intentando acceder a tu cuenta. Por favor, asegura tu cuenta cambiando tu contraseña.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #666;">
            Este código es privado. No lo compartas con nadie.
        </p>
    </div>
</body>
</html>',
                'variables_schema' => [
                    'nombre' => ['required' => true, 'description' => 'Nombre del usuario'],
                    'code' => ['required' => true, 'description' => 'Código de verificación'],
                    'expiration_minutes' => ['required' => false, 'description' => 'Minutos hasta expiración'],
                ],
                'description' => 'Email con código de verificación de dos factores',
                'is_active' => true,
            ],
            [
                'name' => 'Confirmación de Compra',
                'key' => 'purchase_confirmation',
                'subject' => 'Confirmación de tu compra - Orden #[order_id]',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Compra</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #059669;">¡Gracias por tu compra!</h1>
        <p>Hola <strong>[nombre]</strong>,</p>
        <p>Tu compra ha sido procesada exitosamente.</p>
        
        <div style="background-color: #f3f4f6; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h2 style="margin-top: 0;">Detalles del pedido</h2>
            <p><strong>Orden:</strong> #[order_id]</p>
            <p><strong>Fecha:</strong> [date]</p>
            <p><strong>Total:</strong> [total]</p>
        </div>
        
        <h3>Productos:</h3>
        <div>[products_list]</div>
        
        <p style="margin: 30px 0;">
            <a href="[order_url]" style="background-color: #059669; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Ver mi pedido
            </a>
        </p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #666;">
            Si tienes alguna pregunta sobre tu pedido, responde a este correo.
        </p>
    </div>
</body>
</html>',
                'variables_schema' => [
                    'nombre' => ['required' => true, 'description' => 'Nombre del usuario'],
                    'order_id' => ['required' => true, 'description' => 'ID de la orden'],
                    'date' => ['required' => false, 'description' => 'Fecha de la compra'],
                    'total' => ['required' => true, 'description' => 'Total de la compra'],
                    'products_list' => ['required' => true, 'description' => 'HTML con lista de productos'],
                    'order_url' => ['required' => true, 'description' => 'URL para ver el pedido'],
                ],
                'description' => 'Email de confirmación después de una compra',
                'is_active' => true,
            ],
            [
                'name' => 'Notificación General',
                'key' => 'general_notification',
                'subject' => '[subject]',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notificación</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2563eb;">[title]</h1>
        <p>Hola <strong>[nombre]</strong>,</p>
        <div>[content]</div>
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 12px; color: #666;">
            Este es un correo automático de [app_name].
        </p>
    </div>
</body>
</html>',
                'variables_schema' => [
                    'nombre' => ['required' => true, 'description' => 'Nombre del usuario'],
                    'subject' => ['required' => true, 'description' => 'Asunto del correo'],
                    'title' => ['required' => true, 'description' => 'Título del correo'],
                    'content' => ['required' => true, 'description' => 'Contenido HTML del mensaje'],
                    'app_name' => ['required' => false, 'description' => 'Nombre de la aplicación'],
                ],
                'description' => 'Plantilla genérica para notificaciones personalizadas',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }

        $this->command->info('✅ ' . count($templates) . ' plantillas de email creadas/actualizadas.');
    }
}