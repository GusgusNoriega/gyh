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

            // =====================================================================
            // LEADS (FORMULARIO DE MARKETING)
            // =====================================================================
            [
                'name' => 'Nuevo lead (notificación interna)',
                'key' => 'lead_form_notification',
                'subject' => 'Nuevo lead recibido - [app_name] #[lead_id]',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nuevo lead</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827; background: #f9fafb;">
    <div style="max-width: 720px; margin: 0 auto; padding: 24px;">
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px;">
            <h1 style="margin: 0 0 12px 0; color: #111827;">Nuevo lead recibido</h1>
            <p style="margin: 0 0 18px 0; color: #374151;">
                Se registró un lead desde el formulario de marketing en <strong>[app_name]</strong>.
            </p>

            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; width: 30%; color: #6b7280;">Lead ID</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;"><strong>#[lead_id]</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Nombre</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[name]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Email</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;"><a href="mailto:[email]" style="color: #2563eb;">[email]</a></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Teléfono</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[phone]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">¿Empresa?</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[is_company]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Empresa</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[company_name]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">RUC</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[company_ruc]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Tipo de proyecto</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[project_type]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Presupuesto hasta</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[budget_up_to]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Fuente</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[source]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">IP</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[ip]</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">Fecha</td>
                    <td style="padding: 10px; border-top: 1px solid #e5e7eb;">[created_at]</td>
                </tr>
            </table>

            <h3 style="margin: 22px 0 10px 0; color: #111827;">Mensaje</h3>
            <div style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; white-space: pre-wrap; color: #111827;">
                [message]
            </div>

            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 22px 0;">
            <p style="font-size: 12px; color: #6b7280; margin: 0;">
                User-Agent: [user_agent]
            </p>
        </div>
    </div>
</body>
</html>',
                'variables_schema' => [
                    'app_name' => ['required' => true, 'description' => 'Nombre de la aplicación'],
                    'lead_id' => ['required' => true, 'description' => 'ID del lead'],
                    'name' => ['required' => false, 'description' => 'Nombre del lead'],
                    'email' => ['required' => true, 'description' => 'Email del lead'],
                    'phone' => ['required' => true, 'description' => 'Teléfono del lead'],
                    'is_company' => ['required' => true, 'description' => 'Indicador si es empresa (Sí/No)'],
                    'company_name' => ['required' => false, 'description' => 'Nombre de la empresa'],
                    'company_ruc' => ['required' => false, 'description' => 'RUC de la empresa'],
                    'project_type' => ['required' => false, 'description' => 'Tipo de proyecto'],
                    'budget_up_to' => ['required' => false, 'description' => 'Presupuesto estimado'],
                    'message' => ['required' => false, 'description' => 'Mensaje del lead'],
                    'source' => ['required' => false, 'description' => 'Fuente del lead'],
                    'ip' => ['required' => false, 'description' => 'IP del lead'],
                    'user_agent' => ['required' => false, 'description' => 'User-Agent del lead'],
                    'created_at' => ['required' => true, 'description' => 'Fecha/hora de creación del lead'],
                ],
                'description' => 'Notificación interna cuando entra un lead desde el formulario (destino configurable en smtp_settings: leads_notification_emails)',
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
