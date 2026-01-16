# Guía de Instalación Rápida

## Paso 1: Copiar archivos

### Modelos
```bash
cp packages/email-service/app/Models/SmtpSetting.php app/Models/
cp packages/email-service/app/Models/EmailTemplate.php app/Models/
```

### Controladores
```bash
cp packages/email-service/app/Http/Controllers/SmtpSettingController.php app/Http/Controllers/
cp packages/email-service/app/Http/Controllers/EmailTemplateController.php app/Http/Controllers/
```

### Servicios
```bash
mkdir -p app/Services
cp packages/email-service/app/Services/EmailTemplateService.php app/Services/
```

### Migraciones
```bash
cp packages/email-service/database/migrations/2025_01_01_000001_create_smtp_settings_table.php database/migrations/
cp packages/email-service/database/migrations/2025_01_01_000002_create_email_templates_table.php database/migrations/
```

### Seeder (opcional)
```bash
cp packages/email-service/database/seeders/EmailTemplateSeeder.php database/seeders/
```

## Paso 2: Verificar Controller base

Tu `app/Http/Controllers/Controller.php` debe tener estos métodos helper. Si no los tiene, agrégalos:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function apiSuccess(string $message = 'Operación exitosa', ?string $code = null, $data = null, int $status = 200, array $extra = []): JsonResponse
    {
        $payload = array_merge([
            'success' => true,
            'message' => $message,
            'code'    => $code,
            'data'    => $data,
            'errors'  => null,
        ], $extra);

        return response()->json($payload, $status);
    }

    protected function apiCreated(string $message = 'Creado', ?string $code = null, $data = null, array $extra = []): JsonResponse
    {
        return $this->apiSuccess($message, $code, $data, 201, $extra);
    }

    protected function apiError(string $message = 'Error', string $code = 'SERVER_ERROR', ?array $errors = null, $data = null, int $status = 500, array $extra = []): JsonResponse
    {
        $payload = array_merge([
            'success' => false,
            'message' => $message,
            'code'    => $code,
            'data'    => $data,
            'errors'  => $errors,
        ], $extra);

        return response()->json($payload, $status);
    }

    protected function apiValidationError($errors, string $message = 'Datos de entrada inválidos', string $code = 'VALIDATION_ERROR'): JsonResponse
    {
        $errorsArray = $errors instanceof \Illuminate\Support\MessageBag ? $errors->toArray() : $errors;
        return $this->apiError($message, $code, $errorsArray, null, 422);
    }

    protected function apiForbidden(string $message = 'Permisos insuficientes', string $code = 'FORBIDDEN'): JsonResponse
    {
        return $this->apiError($message, $code, null, null, 403);
    }

    protected function apiNotFound(string $message = 'Recurso no encontrado', string $code = 'NOT_FOUND'): JsonResponse
    {
        return $this->apiError($message, $code, null, null, 404);
    }

    protected function apiServerError(\Throwable $e, string $code = 'SERVER_ERROR'): JsonResponse
    {
        $message = config('app.debug') ? $e->getMessage() : 'Error inesperado del servidor';
        return $this->apiError($message, $code, null, null, 500);
    }
}
```

## Paso 3: Agregar rutas

Agrega a tu archivo `routes/api.php`:

```php
use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\EmailTemplateController;

// =======================
// SMTP SETTINGS
// =======================
Route::middleware(['auth:web,api'])->prefix('smtp')->group(function () {
    Route::get('/settings', [SmtpSettingController::class, 'getSettings']);
    Route::put('/settings/{id}', [SmtpSettingController::class, 'updateSetting'])->middleware('admin');
});

// =======================
// EMAIL TEMPLATES
// =======================
Route::middleware(['auth:web,api', 'admin'])->prefix('email-templates')->group(function () {
    Route::get('/', [EmailTemplateController::class, 'index']);
    Route::get('/{id}', [EmailTemplateController::class, 'show']);
    Route::post('/', [EmailTemplateController::class, 'store']);
    Route::put('/{id}', [EmailTemplateController::class, 'update']);
    Route::delete('/{id}', [EmailTemplateController::class, 'destroy']);
    Route::post('/{id}/restore', [EmailTemplateController::class, 'restore']);
});
```

**Nota:** Ajusta el middleware según tu sistema de autenticación:
- Si usas Sanctum: `auth:sanctum`
- Si usas Passport: `auth:api`
- Si usas sesión web: `auth:web`

## Paso 4: Middleware Admin

Si no tienes un middleware `admin`, crea uno o ajusta la verificación en los controladores.

Opción A - Crear middleware:

```bash
php artisan make:middleware CheckAdminRole
```

```php
// app/Http/Middleware/CheckAdminRole.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Permisos insuficientes',
                'code' => 'FORBIDDEN'
            ], 403);
        }

        return $next($request);
    }
}
```

Registrar en `bootstrap/app.php` (Laravel 11):
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\CheckAdminRole::class,
    ]);
})
```

O en `app/Http/Kernel.php` (Laravel 10 o anterior):
```php
protected $middlewareAliases = [
    'admin' => \App\Http\Middleware\CheckAdminRole::class,
];
```

## Paso 5: Ejecutar migraciones

```bash
php artisan migrate
```

## Paso 6: Cargar plantillas de ejemplo (opcional)

```bash
php artisan db:seed --class=EmailTemplateSeeder
```

## Paso 7: Configurar SMTP

### Opción A: Desde .env (básico)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Tu Aplicación"
```

### Opción B: Desde la API (dinámico)

```bash
# Obtener configuración actual
curl -X GET http://tu-app/api/smtp/settings \
  -H "Authorization: Bearer {token}"

# Actualizar configuración (como admin)
curl -X PUT http://tu-app/api/smtp/settings/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"value": "smtp.gmail.com"}'
```

## Paso 8: Probar el servicio

```php
use App\Services\EmailTemplateService;

// En un controlador o comando
$emailService = new EmailTemplateService();

$emailService->send(
    templateKey: 'welcome_email',
    to: 'test@example.com',
    data: [
        'nombre' => 'Usuario de Prueba',
        'app_name' => 'Mi App',
        'login_url' => 'https://mi-app.com/login'
    ]
);
```

## Verificación

✅ Tablas creadas: `smtp_settings`, `email_templates`
✅ Rutas funcionando: `GET /api/smtp/settings`, `GET /api/email-templates`
✅ Servicio funcionando: Email enviado correctamente

## Problemas comunes

### Error: "Class SmtpSetting not found"
- Verifica que el archivo esté en `app/Models/SmtpSetting.php`
- Ejecuta `composer dump-autoload`

### Error: "SMTP connection failed"
- Verifica las credenciales SMTP
- Para Gmail, usa "App Password" si tienes 2FA
- Verifica que el puerto no esté bloqueado por firewall

### Error: "Plantilla no encontrada"
- Verifica que la plantilla existe con `is_active = true`
- La key debe ser exacta (lowercase)

### Error: "Method apiSuccess not found"
- Agrega los métodos helper al Controller base (Paso 2)