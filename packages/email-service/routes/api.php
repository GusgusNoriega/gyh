<?php

/**
 * Rutas API para el servicio de Email Templates
 * 
 * Copia estas rutas a tu archivo routes/api.php principal
 * o incluye este archivo con: require __DIR__ . '/../packages/email-service/routes/api.php';
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\EmailTemplateController;

// =======================
// SMTP SETTINGS (protegido por autenticación)
// =======================
Route::middleware(['auth:web,api'])->prefix('smtp')->group(function () {
    // Obtener todas las configuraciones SMTP
    Route::get('/settings', [SmtpSettingController::class, 'getSettings']);      // GET /api/smtp/settings

    // Actualizar una configuración SMTP (solo admin)
    Route::put('/settings/{id}', [SmtpSettingController::class, 'updateSetting'])->middleware('admin'); // PUT /api/smtp/settings/{id}
});

// =======================
// EMAIL TEMPLATES (protegido por autenticación y admin)
// =======================
Route::middleware(['auth:web,api', 'admin'])->prefix('email-templates')->group(function () {
    // Listar plantillas de correo
    Route::get('/', [EmailTemplateController::class, 'index']);      // GET /api/email-templates
    
    // Obtener una plantilla específica
    Route::get('/{id}', [EmailTemplateController::class, 'show']);   // GET /api/email-templates/{id}
    
    // Crear nueva plantilla
    Route::post('/', [EmailTemplateController::class, 'store']);     // POST /api/email-templates
    
    // Actualizar plantilla
    Route::put('/{id}', [EmailTemplateController::class, 'update']); // PUT /api/email-templates/{id}
    
    // Eliminar plantilla (soft delete)
    Route::delete('/{id}', [EmailTemplateController::class, 'destroy']); // DELETE /api/email-templates/{id}
    
    // Restaurar plantilla eliminada
    Route::post('/{id}/restore', [EmailTemplateController::class, 'restore']); // POST /api/email-templates/{id}/restore
});