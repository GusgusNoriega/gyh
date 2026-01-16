<?php

/**
 * Configuración del servicio de Email Templates
 * 
 * Publicar con: php artisan vendor:publish --tag=email-service-config
 * O copiar manualmente a config/email-service.php
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Cache de configuración SMTP
    |--------------------------------------------------------------------------
    |
    | Tiempo en segundos que se mantiene en cache la configuración SMTP.
    | Por defecto: 3600 segundos (1 hora).
    |
    */
    'smtp_cache_ttl' => env('SMTP_CACHE_TTL', 3600),

    /*
    |--------------------------------------------------------------------------
    | Nombre de la aplicación por defecto
    |--------------------------------------------------------------------------
    |
    | Se usará como fallback si no se pasa la variable [app_name] en las plantillas.
    |
    */
    'default_app_name' => env('APP_NAME', 'Mi Aplicación'),

    /*
    |--------------------------------------------------------------------------
    | Nombre del remitente por defecto
    |--------------------------------------------------------------------------
    |
    | Se usará cuando no haya configuración SMTP en BD ni en .env.
    |
    */
    'default_from_name' => env('MAIL_FROM_NAME', 'Mi Aplicación'),

    /*
    |--------------------------------------------------------------------------
    | Tipos de notificación soportados
    |--------------------------------------------------------------------------
    |
    | Estos tipos se usan para verificar preferencias de usuarios.
    | Solo aplica si implementas UserNotificationPreference.
    |
    */
    'notification_types' => [
        'security',         // Siempre se envía (no desactivable)
        'course_updates',
        'enrollments',
        'comments',
        'quizzes',
        'progress',
        'community',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Habilitar logging detallado del servicio de email.
    |
    */
    'enable_logging' => env('EMAIL_SERVICE_LOGGING', true),
];