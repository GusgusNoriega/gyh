# Email Service Package para Laravel

Este paquete proporciona un sistema completo de envío de emails con plantillas dinámicas y configuración SMTP almacenada en base de datos.

## Características

- ✅ Configuración SMTP dinámica desde base de datos
- ✅ Plantillas de email con shortcodes reemplazables
- ✅ Soporte para variables required/optional en plantillas
- ✅ Cache automático de configuraciones SMTP
- ✅ API REST completa para gestión de plantillas y configuración
- ✅ Soft delete en plantillas de email
- ✅ Soporte para preferencias de notificación de usuarios (opcional)

## Estructura del Paquete

```
packages/email-service/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php           # Controlador base con helpers API
│   │       ├── SmtpSettingController.php
│   │       └── EmailTemplateController.php
│   ├── Models/
│   │   ├── SmtpSetting.php
│   │   └── EmailTemplate.php
│   └── Services/
│       └── EmailTemplateService.php
├── database/
│   └── migrations/
│       ├── 2025_01_01_000001_create_smtp_settings_table.php
│       └── 2025_01_01_000002_create_email_templates_table.php
├── routes/
│   └── api.php
├── README.md
└── config/
    └── email-service.php
```

## Instalación

### 1. Copiar archivos al proyecto destino

Copia los archivos a las siguientes ubicaciones en tu proyecto Laravel:

```bash
# Modelos
cp app/Models/SmtpSetting.php [tu-proyecto]/app/Models/
cp app/Models/EmailTemplate.php [tu-proyecto]/app/Models/

# Controladores
cp app/Http/Controllers/SmtpSettingController.php [tu-proyecto]/app/Http/Controllers/
cp app/Http/Controllers/EmailTemplateController.php [tu-proyecto]/app/Http/Controllers/

# Servicios
cp app/Services/EmailTemplateService.php [tu-proyecto]/app/Services/

# Migraciones
cp database/migrations/*.php [tu-proyecto]/database/migrations/
```

### 2. Agregar métodos al Controller base

Si no tienes los métodos helper de API en tu Controller base, agrégalos desde `app/Http/Controllers/Controller.php`:

```php
// app/Http/Controllers/Controller.php
abstract class Controller
{
    protected function apiSuccess(string $message, ?string $code, $data, int $status = 200, array $extra = []): JsonResponse
    protected function apiCreated(string $message, ?string $code, $data, array $extra = []): JsonResponse
    protected function apiError(string $message, string $code, ?array $errors, $data, int $status, array $extra = []): JsonResponse
    protected function apiValidationError(array $errors, string $message, string $code): JsonResponse
    protected function apiForbidden(string $message, string $code): JsonResponse
    protected function apiNotFound(string $message, string $code): JsonResponse
    protected function apiServerError(\Throwable $e, string $code): JsonResponse
}
```

### 3. Registrar las rutas

Agrega las siguientes rutas a tu archivo `routes/api.php`:

```php
use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\EmailTemplateController;

// SMTP Settings (protegido por autenticación)
Route::middleware(['auth:web,api'])->prefix('smtp')->group(function () {
    Route::get('/settings', [SmtpSettingController::class, 'getSettings']);
    Route::put('/settings/{id}', [SmtpSettingController::class, 'updateSetting'])->middleware('admin');
});

// Email Templates (protegido por autenticación y admin)
Route::middleware(['auth:web,api', 'admin'])->prefix('email-templates')->group(function () {
    Route::get('/', [EmailTemplateController::class, 'index']);
    Route::get('/{id}', [EmailTemplateController::class, 'show']);
    Route::post('/', [EmailTemplateController::class, 'store']);
    Route::put('/{id}', [EmailTemplateController::class, 'update']);
    Route::delete('/{id}', [EmailTemplateController::class, 'destroy']);
    Route::post('/{id}/restore', [EmailTemplateController::class, 'restore']);
});
```

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

## Configuración

### Variables de entorno fallback (.env)

La configuración SMTP en base de datos tiene prioridad, pero puede usar fallback de .env:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Tu Aplicación"
```

### Configuración inicial de SMTP (automática)

La migración crea automáticamente los siguientes registros en `smtp_settings`:

| key | value | description |
|-----|-------|-------------|
| smtp_host | (vacío) | Servidor SMTP |
| smtp_port | 587 | Puerto SMTP |
| smtp_encryption | tls | Tipo de encriptación |
| smtp_username | (vacío) | Usuario SMTP |
| smtp_password | (vacío) | Contraseña SMTP |
| smtp_from_address | (vacío) | Email remitente |
| smtp_from_name | Mentora | Nombre remitente |

## Uso del Servicio

### Enviar un email con plantilla

```php
use App\Services\EmailTemplateService;

$emailService = new EmailTemplateService();

// Envío básico
$emailService->send(
    templateKey: 'welcome_email',
    to: 'usuario@email.com',
    data: [
        'nombre' => 'Juan Pérez',
        'enlace_activacion' => 'https://tu-app.com/activate/123'
    ]
);

// Envío con opciones avanzadas
$emailService->send(
    templateKey: 'password_reset',
    to: ['usuario@email.com', 'otro@email.com'],
    data: [
        'nombre' => 'Juan Pérez',
        'enlace_reset' => 'https://tu-app.com/reset/abc123',
        'expiracion' => '24 horas'
    ],
    subjectOverride: 'Asunto personalizado',
    fromAddress: 'noreply@tu-app.com',
    fromName: 'Tu Aplicación - No Responder'
);
```

### Envío con verificación de preferencias de usuario

Si implementas el sistema de preferencias de notificación:

```php
$emailService->send(
    templateKey: 'course_update',
    to: $usuario->email,
    data: ['curso' => 'Laravel Avanzado'],
    userId: $usuario->id,
    notificationType: 'course_updates'  // Verificará si el usuario tiene habilitado este tipo
);
```

## Creación de Plantillas

### Estructura de una plantilla

```json
{
    "name": "Bienvenida",
    "key": "welcome_email",
    "subject": "¡Bienvenido [nombre]!",
    "content_html": "<h1>Hola [nombre]</h1><p>Tu cuenta ha sido creada...</p>",
    "variables_schema": {
        "nombre": {
            "required": true,
            "description": "Nombre del usuario"
        },
        "enlace_activacion": {
            "required": true,
            "description": "URL de activación de cuenta"
        },
        "fecha_registro": {
            "required": false,
            "description": "Fecha de registro del usuario"
        }
    },
    "description": "Email de bienvenida para nuevos usuarios",
    "is_active": true
}
```

### Shortcodes disponibles

Las plantillas usan shortcodes con formato `[nombre_variable]` que son reemplazados automáticamente:

```html
<h1>Hola [nombre]</h1>
<p>Tu curso "[nombre_curso]" comienza el [fecha_inicio].</p>
<p>Accede aquí: <a href="[enlace_curso]">Ver Curso</a></p>
```

### API para gestión de plantillas

#### Crear plantilla
```bash
POST /api/email-templates
Content-Type: application/json
Authorization: Bearer {token}

{
    "name": "Recuperación de contraseña",
    "key": "password_reset",
    "subject": "Recuperación de contraseña - [app_name]",
    "content_html": "<p>Hola [nombre],</p><p>Haz click aquí para recuperar tu contraseña: <a href=\"[reset_link]\">Recuperar</a></p>",
    "variables_schema": {
        "nombre": {"required": true},
        "reset_link": {"required": true},
        "app_name": {"required": false}
    },
    "description": "Email para reseteo de contraseña",
    "is_active": true
}
```

#### Listar plantillas
```bash
GET /api/email-templates?search=password&is_active=true
Authorization: Bearer {token}
```

#### Actualizar plantilla
```bash
PUT /api/email-templates/{id}
Content-Type: application/json
Authorization: Bearer {token}

{
    "subject": "Nuevo asunto actualizado",
    "content_html": "<p>Contenido actualizado...</p>"
}
```

## API de Configuración SMTP

### Obtener configuración
```bash
GET /api/smtp/settings
Authorization: Bearer {token}
```

Respuesta:
```json
{
    "success": true,
    "message": "Configuraciones SMTP obtenidas exitosamente",
    "code": "SMTP_SETTINGS_FETCHED",
    "data": [
        {
            "id": 1,
            "key": "smtp_host",
            "value": "smtp.gmail.com",
            "description": "Servidor SMTP"
        },
        ...
    ]
}
```

### Actualizar configuración (solo admin)
```bash
PUT /api/smtp/settings/{id}
Content-Type: application/json
Authorization: Bearer {token}

{
    "value": "smtp.nuevoemail.com",
    "description": "Nuevo servidor SMTP"
}
```

## Plantillas de Ejemplo

### 1. Bienvenida
```json
{
    "key": "welcome_email",
    "subject": "¡Bienvenido a [app_name]!",
    "content_html": "<!DOCTYPE html><html><body><h1>Hola [nombre],</h1><p>¡Bienvenido a nuestra plataforma!</p><p>Tu cuenta ha sido creada exitosamente.</p><p><a href='[login_url]'>Iniciar Sesión</a></p></body></html>",
    "variables_schema": {
        "nombre": {"required": true},
        "app_name": {"required": true},
        "login_url": {"required": true}
    }
}
```

### 2. Recuperación de Contraseña
```json
{
    "key": "password_reset",
    "subject": "Recupera tu contraseña - [app_name]",
    "content_html": "<!DOCTYPE html><html><body><h1>Recuperación de contraseña</h1><p>Hola [nombre],</p><p>Hemos recibido una solicitud para restablecer tu contraseña.</p><p><a href='[reset_url]'>Restablecer contraseña</a></p><p>Este enlace expira en [expiration_time].</p><p>Si no solicitaste esto, ignora este email.</p></body></html>",
    "variables_schema": {
        "nombre": {"required": true},
        "app_name": {"required": true},
        "reset_url": {"required": true},
        "expiration_time": {"required": false}
    }
}
```

### 3. Confirmación de Compra
```json
{
    "key": "purchase_confirmation",
    "subject": "Confirmación de tu compra - Orden #[order_id]",
    "content_html": "<!DOCTYPE html><html><body><h1>¡Gracias por tu compra!</h1><p>Hola [nombre],</p><p>Tu compra ha sido procesada exitosamente.</p><h2>Detalles del pedido</h2><p>Orden: #[order_id]</p><p>Total: [total]</p><p>Productos: [products_list]</p><p><a href='[order_url]'>Ver mi pedido</a></p></body></html>",
    "variables_schema": {
        "nombre": {"required": true},
        "order_id": {"required": true},
        "total": {"required": true},
        "products_list": {"required": true},
        "order_url": {"required": true}
    }
}
```

### 4. Código 2FA
```json
{
    "key": "two_factor_code",
    "subject": "Tu código de verificación: [code]",
    "content_html": "<!DOCTYPE html><html><body><h1>Verificación de seguridad</h1><p>Hola [nombre],</p><p>Tu código de verificación es:</p><h2 style='font-size:32px;letter-spacing:5px;'>[code]</h2><p>Este código expira en [expiration_minutes] minutos.</p><p>Si no solicitaste este código, ignora este email.</p></body></html>",
    "variables_schema": {
        "nombre": {"required": true},
        "code": {"required": true},
        "expiration_minutes": {"required": false}
    }
}
```

## Integración con Preferencias de Usuario (Opcional)

Si deseas que los usuarios puedan controlar qué notificaciones reciben, implementa el modelo `UserNotificationPreference`. El servicio verificará automáticamente las preferencias si pasas el `userId`:

```php
// El servicio verificará si el usuario tiene habilitado 'course_updates'
$emailService->send(
    templateKey: 'new_lesson_available',
    to: $user->email,
    data: [...],
    userId: $user->id,
    notificationType: 'course_updates'
);
```

Tipos de notificación soportados:
- `security` - Siempre se envía (no se puede deshabilitar)
- `course_updates`
- `enrollments`
- `comments`
- `quizzes`
- `progress`
- `community`

## Notas Importantes

1. **Cache**: La configuración SMTP se cachea por 1 hora. Se limpia automáticamente al actualizar.

2. **Seguridad**: Solo usuarios con rol 'admin' pueden modificar configuración SMTP y plantillas.

3. **Fallback**: Si no hay configuración en BD, usa valores de `.env`.

4. **Logs**: El servicio genera logs detallados para debugging:
   - `info`: Inicio de envío, configuración aplicada, éxito
   - `warning`: Plantilla no encontrada
   - `error`: Errores de envío

5. **Soft Delete**: Las plantillas usan soft delete, permitiendo restauración.

## Troubleshooting

### El email no se envía

1. Verifica la configuración SMTP en la tabla `smtp_settings`
2. Revisa los logs de Laravel (`storage/logs/laravel.log`)
3. Verifica que la plantilla existe y está activa (`is_active = true`)
4. Confirma que las variables requeridas están siendo enviadas

### Error "Plantilla no encontrada"

1. Verifica que la key de la plantilla es correcta
2. Confirma que `is_active = true`
3. La key debe coincidir exactamente (case-sensitive después de lowercase)

### Error de conexión SMTP

1. Verifica host, puerto y credenciales
2. Para Gmail, usa "App Password" si tienes 2FA activado
3. Prueba con `smtp_encryption = tls` o `ssl` según tu servidor

## Licencia

Este paquete es de código abierto y puede ser utilizado libremente.