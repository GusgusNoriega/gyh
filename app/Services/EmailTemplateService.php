<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class EmailTemplateService
{
    /**
     * Enviar un correo basado en una plantilla identificada por su clave.
     *
     * @param string $templateKey  Clave única de la plantilla (columna "key").
     * @param string|array $to     Destinatario(s).
     * @param array $data          Datos para reemplazar los shortcodes.
     * @param string|null $subjectOverride  Permite sobreescribir el asunto.
     * @param string|null $fromAddress      Dirección "from" personalizada.
     * @param string|null $fromName         Nombre "from" personalizado.
     * @param int|null $userId     ID del usuario para verificar preferencias (opcional).
     * @param string $notificationType  Tipo de notificación: 'security', 'enrollments', 'course_updates', etc.
     */
    public function send(
        string $templateKey,
        string|array $to,
        array $data = [],
        ?string $subjectOverride = null,
        ?string $fromAddress = null,
        ?string $fromName = null,
        ?int $userId = null,
        string $notificationType = 'security',
        ?string $replyTo = null,
        ?string $replyToName = null
    ): void {
        // Verificar preferencias del usuario si se proporciona userId
        // NOTA: Descomenta el siguiente bloque si implementas UserNotificationPreference
        /*
        if ($userId !== null) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $preferences = $user->getOrCreateNotificationPreferences();
                if (!$preferences->shouldNotify($notificationType, 'email')) {
                    Log::info('EmailTemplateService@send: notificación omitida por preferencias del usuario', [
                        'template_key' => $templateKey,
                        'user_id' => $userId,
                        'notification_type' => $notificationType,
                        'email_enabled' => $preferences->email_enabled,
                    ]);
                    return; // No enviar si el usuario tiene deshabilitadas estas notificaciones
                }
            }
        }
        */

        // Log de inicio del proceso de envío
        Log::info('EmailTemplateService@send: inicio', [
            'template_key' => $templateKey,
            'to' => $to,
            'data_keys' => array_keys($data),
            'subject_override' => $subjectOverride,
            'from_address_override' => $fromAddress,
            'from_name_override' => $fromName,
            'mailer_default_config' => config('mail.default'),
        ]);

        $template = EmailTemplate::query()
            ->where('key', $templateKey)
            ->where('is_active', true)
            ->first();

        if (! $template) {
            Log::warning('EmailTemplateService@send: plantilla no encontrada o inactiva', [
                'template_key' => $templateKey,
            ]);

            throw new InvalidArgumentException("La plantilla de correo [{$templateKey}] no existe o está inactiva.");
        }

        $schema = $template->variables_schema ?? [];
        $this->validateRequiredVariables($schema, $data);

        Log::info('EmailTemplateService@send: plantilla encontrada y variables validadas', [
            'template_id' => $template->id ?? null,
            'template_key' => $template->key ?? null,
        ]);

        $subject = $subjectOverride ?? $template->subject;
        $html = $template->content_html;

        $subject = $this->applyShortcodes($subject, $data);
        $html = $this->applyShortcodes($html, $data);

        // Aplicar configuración SMTP dinámica (BD + .env)
        $smtpConfig = $this->applySmtpConfig();

        $fromAddress = $fromAddress
            ?? $smtpConfig['from_address']
            ?? config('mail.from.address');

        $fromName = $fromName
            ?? $smtpConfig['from_name']
            ?? config('mail.from.name');

        // Siempre forzar el uso de SMTP
        $mailerName = 'smtp';

        Log::info('[EmailTemplateService] Configuración SMTP aplicada para envío', [
            'mailer_name' => $mailerName,
            'smtp_host' => $smtpConfig['host'] ?? null,
            'smtp_port' => $smtpConfig['port'] ?? null,
            'smtp_encryption' => $smtpConfig['encryption'] ?? null,
            'smtp_username' => $smtpConfig['username'] ? substr($smtpConfig['username'], 0, 5) . '***' : 'NO CONFIGURADO',
            'smtp_password_present' => !empty($smtpConfig['password']),
            'from_address_final' => $fromAddress,
            'from_name_final' => $fromName,
            'config_mail_default' => config('mail.default'),
            'config_smtp_transport' => config('mail.mailers.smtp.transport'),
            'config_smtp_host' => config('mail.mailers.smtp.host'),
        ]);

        try {
            // Usar explícitamente el mailer SMTP
            Mail::mailer($mailerName)->html($html, function ($message) use ($to, $subject, $fromAddress, $fromName, $replyTo, $replyToName) {
                $message->to($to)->subject($subject);

                if ($fromAddress) {
                    $message->from($fromAddress, $fromName ?? null);
                }

                if ($replyTo) {
                    $message->replyTo($replyTo, $replyToName ?? null);
                }
            });

            Log::info('[EmailTemplateService] ✓ Correo enviado exitosamente', [
                'template_key' => $templateKey,
                'to' => $to,
                'mailer_name' => $mailerName,
                'subject' => $subject,
            ]);
        } catch (\Throwable $e) {
            Log::error('[EmailTemplateService] ✗ Error al enviar correo', [
                'template_key' => $templateKey,
                'to' => $to,
                'mailer_name' => $mailerName,
                'exception_message' => $e->getMessage(),
                'exception_class' => get_class($e),
                'exception_file' => $e->getFile() . ':' . $e->getLine(),
            ]);

            throw $e;
        }
    }

    /**
     * Reemplaza shortcodes tipo [campo] en el contenido.
     *
     * @param string $content
     * @param array $data
     * @return string
     */
    protected function applyShortcodes(string $content, array $data): string
    {
        if ($content === '') {
            return $content;
        }

        foreach ($data as $key => $value) {
            $placeholder = '[' . $key . ']';
            $content = str_replace($placeholder, (string) $value, $content);
        }

        return $content;
    }

    /**
     * Valida que todas las variables requeridas en el schema estén presentes.
     *
     * @param array $schema
     * @param array $data
     */
    protected function validateRequiredVariables(array $schema, array $data): void
    {
        $missing = [];

        foreach ($schema as $key => $meta) {
            $required = is_array($meta) && ! empty($meta['required']);

            if ($required && ! array_key_exists($key, $data)) {
                $missing[] = $key;
            }
        }

        if (! empty($missing)) {
            $list = implode(', ', $missing);

            throw new InvalidArgumentException(
                'Faltan variables requeridas para la plantilla de correo: ' . $list
            );
        }
    }

    /**
     * Aplica en tiempo de ejecución la configuración SMTP almacenada en BD.
     *
     * @return array La configuración SMTP aplicada
     */
    protected function applySmtpConfig(): array
    {
        $smtp = SmtpSetting::smtpConfig();

        Log::info('[EmailTemplateService] Configuración SMTP obtenida de BD', [
            'host' => $smtp['host'],
            'port' => $smtp['port'],
            'encryption' => $smtp['encryption'],
            'username' => $smtp['username'] ? substr($smtp['username'], 0, 5) . '***' : 'NO CONFIGURADO',
            'password_set' => !empty($smtp['password']),
            'from_address' => $smtp['from_address'],
            'from_name' => $smtp['from_name'],
        ]);

        // Forzar el mailer por defecto a SMTP
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $smtp['host'] ?? config('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => $smtp['port'] ?? config('mail.mailers.smtp.port'),
            'mail.mailers.smtp.encryption' => $smtp['encryption'] ?? config('mail.mailers.smtp.encryption'),
            'mail.mailers.smtp.username' => $smtp['username'] ?? config('mail.mailers.smtp.username'),
            'mail.mailers.smtp.password' => $smtp['password'] ?? config('mail.mailers.smtp.password'),
            'mail.from.address' => $smtp['from_address'] ?? config('mail.from.address'),
            'mail.from.name' => $smtp['from_name'] ?? config('mail.from.name'),
        ]);

        // Purgar el mailer SMTP para que tome la nueva configuración
        Mail::purge('smtp');

        Log::info('[EmailTemplateService] Mailer SMTP reconfigurado y purgado', [
            'new_default' => config('mail.default'),
            'new_transport' => config('mail.mailers.smtp.transport'),
            'new_host' => config('mail.mailers.smtp.host'),
            'new_port' => config('mail.mailers.smtp.port'),
        ]);

        return $smtp;
    }
}
