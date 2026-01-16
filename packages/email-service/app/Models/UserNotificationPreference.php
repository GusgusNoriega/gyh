<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ARCHIVO OPCIONAL
 * 
 * Implementa este modelo si deseas que los usuarios puedan controlar
 * qué tipos de notificaciones reciben.
 * 
 * Requiere crear la migración correspondiente (ver: database/migrations/optional/)
 */
class UserNotificationPreference extends Model
{
    protected $table = 'user_notification_preferences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'email_enabled',
        'whatsapp_enabled',
        'two_factor_enabled',
        'two_factor_method',
        'notify_course_updates',
        'notify_enrollments',
        'notify_comments',
        'notify_quizzes',
        'notify_progress',
        'notify_community',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_enabled' => 'boolean',
        'whatsapp_enabled' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'notify_course_updates' => 'boolean',
        'notify_enrollments' => 'boolean',
        'notify_comments' => 'boolean',
        'notify_quizzes' => 'boolean',
        'notify_progress' => 'boolean',
        'notify_community' => 'boolean',
    ];

    /**
     * Relación con el usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener o crear preferencias para un usuario.
     *
     * @param int $userId
     * @return UserNotificationPreference
     */
    public static function getOrCreateForUser(int $userId): UserNotificationPreference
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            [
                'email_enabled' => true,
                'whatsapp_enabled' => true,
                'two_factor_enabled' => false,
                'two_factor_method' => 'email',
                'notify_course_updates' => true,
                'notify_enrollments' => true,
                'notify_comments' => true,
                'notify_quizzes' => true,
                'notify_progress' => true,
                'notify_community' => true,
            ]
        );
    }

    /**
     * Verificar si debe enviar notificación según tipo.
     *
     * @param string $notificationType Tipo: 'course_updates', 'enrollments', 'comments', 'quizzes', 'progress', 'community', 'security'
     * @param string $channel Canal: 'email' o 'whatsapp'
     * @return bool
     */
    public function shouldNotify(string $notificationType, string $channel = 'email'): bool
    {
        // Las notificaciones de seguridad SIEMPRE se envían
        if ($notificationType === 'security') {
            return true;
        }

        // Verificar si el canal está habilitado
        if ($channel === 'email' && !$this->email_enabled) {
            return false;
        }

        if ($channel === 'whatsapp' && !$this->whatsapp_enabled) {
            return false;
        }

        // Verificar la preferencia específica del tipo
        $preferenceMap = [
            'course_updates' => 'notify_course_updates',
            'enrollments' => 'notify_enrollments',
            'comments' => 'notify_comments',
            'quizzes' => 'notify_quizzes',
            'progress' => 'notify_progress',
            'community' => 'notify_community',
        ];

        if (isset($preferenceMap[$notificationType])) {
            return (bool) $this->{$preferenceMap[$notificationType]};
        }

        // Por defecto, permitir envío
        return true;
    }
}