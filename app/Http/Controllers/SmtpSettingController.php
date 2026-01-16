<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;
use Throwable;

class SmtpSettingController extends Controller
{
    /**
     * API: Obtener todas las configuraciones SMTP.
     */
    public function getSettings(Request $request)
    {
        try {
            $settings = SmtpSetting::all()->map(function ($setting) {
                return [
                    'id' => $setting->id,
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'description' => $setting->description,
                    'created_at' => $setting->created_at,
                    'updated_at' => $setting->updated_at,
                ];
            })->values()->toArray();

            return $this->apiSuccess(
                'Configuraciones SMTP obtenidas exitosamente',
                'SMTP_SETTINGS_FETCHED',
                $settings,
                200
            );
        } catch (Throwable $e) {
            Log::error('Error al obtener configuraciones SMTP', ['exception' => $e]);
            return $this->apiServerError($e);
        }
    }

    /**
     * Vista de administración de configuración SMTP (solo admin).
     */
    public function settingsView()
    {
        return view('smtp.settings');
    }

    /**
     * API: Actualizar una configuración específica (solo admin).
     */
    public function updateSetting(Request $request, $id)
    {
        try {
            // Nota: La verificación de admin ya está en el middleware admin.api

            $setting = SmtpSetting::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'value' => 'required|string',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError(
                    $validator->errors(),
                    'Datos de entrada inválidos',
                    'VALIDATION_ERROR'
                );
            }

            $setting->value = $request->value;
            if ($request->has('description')) {
                $setting->description = $request->description;
            }
            $setting->save();

            // Limpiar cache
            SmtpSetting::clearCache();

            return $this->apiSuccess(
                'Configuración SMTP actualizada exitosamente',
                'SMTP_SETTING_UPDATED',
                $setting,
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->apiNotFound(
                'Configuración SMTP no encontrada',
                'SMTP_SETTING_NOT_FOUND'
            );
        } catch (Throwable $e) {
            Log::error('Error al actualizar configuración SMTP', [
                'id' => $id,
                'exception' => $e,
            ]);
            return $this->apiServerError($e);
        }
    }

    /**
     * API: Probar la conexión SMTP enviando un correo de prueba.
     */
    public function testConnection(Request $request)
    {
        try {
            // Nota: La verificación de admin ya está en el middleware admin.api

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError(
                    $validator->errors(),
                    'Datos de entrada inválidos',
                    'VALIDATION_ERROR'
                );
            }

            $testEmail = $request->email;

            // Obtener la configuración SMTP
            $config = SmtpSetting::smtpConfig();

            // Log de diagnóstico: Configuración obtenida de la BD
            Log::info('[SMTP Test] Configuración obtenida de la base de datos', [
                'host' => $config['host'],
                'port' => $config['port'],
                'encryption' => $config['encryption'],
                'username' => $config['username'] ? substr($config['username'], 0, 5) . '***' : 'NO CONFIGURADO',
                'password' => $config['password'] ? '***SET***' : 'NO CONFIGURADO',
                'from_address' => $config['from_address'],
                'from_name' => $config['from_name'],
            ]);

            // Validar que tengamos los datos mínimos necesarios
            if (empty($config['host'])) {
                Log::warning('[SMTP Test] Host SMTP no configurado');
                return $this->apiError(
                    'El host SMTP no está configurado',
                    'SMTP_HOST_MISSING',
                    null,
                    400
                );
            }

            if (empty($config['port'])) {
                Log::warning('[SMTP Test] Puerto SMTP no configurado');
                return $this->apiError(
                    'El puerto SMTP no está configurado',
                    'SMTP_PORT_MISSING',
                    null,
                    400
                );
            }

            // Log: Configuración actual del mailer antes de cambios
            Log::info('[SMTP Test] Configuración actual del mailer ANTES de cambios', [
                'default_mailer' => config('mail.default'),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
                'smtp_encryption' => config('mail.mailers.smtp.encryption'),
                'smtp_username' => config('mail.mailers.smtp.username') ? '***SET***' : 'NOT SET',
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]);

            // Configurar el mailer dinámicamente
            config([
                'mail.default' => 'smtp', // Forzar usar SMTP
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $config['host'],
                'mail.mailers.smtp.port' => $config['port'],
                'mail.mailers.smtp.encryption' => $config['encryption'] ?: null,
                'mail.mailers.smtp.username' => $config['username'],
                'mail.mailers.smtp.password' => $config['password'],
                'mail.from.address' => $config['from_address'] ?: $config['username'],
                'mail.from.name' => $config['from_name'] ?: config('app.name'),
            ]);

            // Log: Configuración del mailer DESPUÉS de cambios
            Log::info('[SMTP Test] Configuración del mailer DESPUÉS de aplicar cambios', [
                'default_mailer' => config('mail.default'),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
                'smtp_encryption' => config('mail.mailers.smtp.encryption'),
                'smtp_username' => config('mail.mailers.smtp.username') ? '***SET***' : 'NOT SET',
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]);

            // Purgar el mailer para que tome la nueva configuración
            Mail::purge('smtp');
            Log::info('[SMTP Test] Mailer SMTP purgado para recargar configuración');

            // Intentar enviar correo de prueba
            $fromAddress = $config['from_address'] ?: $config['username'];
            $fromName = $config['from_name'] ?: config('app.name');

            Log::info('[SMTP Test] Iniciando envío de correo de prueba', [
                'to' => $testEmail,
                'from_address' => $fromAddress,
                'from_name' => $fromName,
            ]);

            // Usar mailer('smtp') explícitamente para asegurarnos de usar SMTP
            Mail::mailer('smtp')->raw(
                "Este es un correo de prueba enviado desde " . config('app.name') . ".\n\n" .
                "Si recibes este mensaje, la configuración SMTP está funcionando correctamente.\n\n" .
                "Configuración utilizada:\n" .
                "- Host: {$config['host']}\n" .
                "- Puerto: {$config['port']}\n" .
                "- Encriptación: " . ($config['encryption'] ?: 'Ninguna') . "\n" .
                "- Usuario: " . ($config['username'] ? substr($config['username'], 0, 3) . '***' : 'No configurado') . "\n\n" .
                "Fecha y hora: " . now()->format('Y-m-d H:i:s'),
                function ($message) use ($testEmail, $fromAddress, $fromName) {
                    $message->to($testEmail)
                        ->subject('Prueba de configuración SMTP - ' . config('app.name'))
                        ->from($fromAddress, $fromName);
                }
            );

            Log::info('[SMTP Test] ✓ Correo de prueba enviado correctamente (sin excepciones)', [
                'to' => $testEmail,
                'host' => $config['host'],
                'port' => $config['port'],
                'encryption' => $config['encryption'],
                'from' => $fromAddress,
            ]);

            return $this->apiSuccess(
                'Correo de prueba enviado exitosamente a ' . $testEmail,
                'SMTP_TEST_SUCCESS',
                [
                    'email' => $testEmail,
                    'host' => $config['host'],
                    'port' => $config['port'],
                    'encryption' => $config['encryption'],
                    'from_address' => $fromAddress,
                    'from_name' => $fromName,
                    'debug_info' => 'Revisa los logs en storage/logs/laravel.log para más detalles',
                ],
                200
            );

        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            Log::error('[SMTP Test] ✗ Error de transporte SMTP (Symfony)', [
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ]);

            return $this->apiError(
                'Error de conexión SMTP: ' . $e->getMessage(),
                'SMTP_TRANSPORT_ERROR',
                [
                    'error' => $e->getMessage(),
                    'type' => 'transport_error',
                    'exception_class' => get_class($e),
                ],
                400
            );
        } catch (\Swift_TransportException $e) {
            Log::error('[SMTP Test] ✗ Error de transporte Swift', [
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ]);

            return $this->apiError(
                'Error de conexión SMTP: ' . $e->getMessage(),
                'SMTP_TRANSPORT_ERROR',
                [
                    'error' => $e->getMessage(),
                    'type' => 'swift_transport_error',
                    'exception_class' => get_class($e),
                ],
                400
            );
        } catch (Throwable $e) {
            Log::error('[SMTP Test] ✗ Error general al probar conexión SMTP', [
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->apiError(
                'Error al enviar correo de prueba: ' . $e->getMessage(),
                'SMTP_TEST_ERROR',
                [
                    'error' => $e->getMessage(),
                    'type' => get_class($e),
                ],
                500
            );
        }
    }
}