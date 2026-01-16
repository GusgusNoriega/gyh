<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaAssetController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ColorThemeController;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskDependencyController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\FileCategoryController;
use App\Http\Controllers\MediaAttachmentController;

use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\EmailTemplateController;

use App\Http\Controllers\QuoteController;
use App\Http\Controllers\QuoteSettingController;

use App\Http\Controllers\LeadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de autenticación API
Route::post('/login', [AuthController::class, 'apiLogin']);

// =======================
// LEADS (público para captación + admin para gestión)
// =======================
// Público: recibe leads desde el formulario de marketing
Route::post('/leads', [LeadController::class, 'store'])->middleware('throttle:20,1');

// Admin: listado / ver / editar / eliminar
Route::middleware(['auth.api', 'admin.api'])->group(function () {
    Route::apiResource('leads', LeadController::class)->except(['store']);
});

// Media Manager routes
Route::apiResource('media', MediaAssetController::class);

// User Management routes protegidas con autenticación Passport
Route::middleware(['auth.api', 'admin.api'])->group(function () {
    Route::apiResource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::get('users/{userId}/roles', [UserController::class, 'getUserRoles']);
    Route::get('users/{userId}/permissions', [UserController::class, 'getUserPermissions']);
    Route::post('users/{userId}/roles/assign', [UserController::class, 'assignRoles']);
    Route::post('users/{userId}/roles/revoke', [UserController::class, 'revokeRoles']);
});

// Currency routes protegidas con autenticación Passport
Route::middleware(['auth.api', 'admin.api'])->group(function () {
    Route::apiResource('currencies', CurrencyController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});

// RBAC routes protegidas con autenticación Passport
Route::middleware(['auth.api', 'admin.api'])->prefix('rbac')->group(function () {
    Route::apiResource('roles', RoleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::apiResource('permissions', PermissionController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::get('roles/{roleId}/permissions', [RolePermissionController::class, 'index']);
    Route::post('roles/{roleId}/permissions/attach', [RolePermissionController::class, 'attach']);
    Route::post('roles/{roleId}/permissions/sync', [RolePermissionController::class, 'sync']);
    Route::post('roles/{roleId}/permissions/detach', [RolePermissionController::class, 'detach']);
});

// Color Theme routes protegidas con autenticación Passport
Route::middleware(['auth.api', 'admin.api'])->group(function () {
   Route::apiResource('color-themes', ColorThemeController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
   Route::post('color-themes/{id}/activate', [ColorThemeController::class, 'activate']);
   Route::get('color-themes/active', [ColorThemeController::class, 'active']);
});

// Project Management routes protegidas con autenticación Passport
Route::middleware(['auth.api', 'admin.api'])->group(function () {
   // Projects
   Route::apiResource('projects', ProjectController::class)->only(['index','store','show','update','destroy'])->names([
       'index' => 'api.projects.index',
       'store' => 'api.projects.store',
       'show' => 'api.projects.show',
       'update' => 'api.projects.update',
       'destroy' => 'api.projects.destroy',
   ]);

   // Tasks
   Route::apiResource('tasks', TaskController::class)->only(['index','store','show','update','destroy'])->names([
       'index' => 'api.tasks.index',
       'store' => 'api.tasks.store',
       'show' => 'api.tasks.show',
       'update' => 'api.tasks.update',
       'destroy' => 'api.tasks.destroy',
   ]);

   // Task Dependencies
   Route::get('task-dependencies', [TaskDependencyController::class, 'index']);
   Route::post('task-dependencies', [TaskDependencyController::class, 'store']);
   Route::get('task-dependencies/{id}', [TaskDependencyController::class, 'show']);
   Route::delete('task-dependencies/{id}', [TaskDependencyController::class, 'destroy']);

   // Catálogos
   Route::get('task-status', [TaskStatusController::class, 'index']);
   Route::get('task-status/{id}', [TaskStatusController::class, 'show']);
   Route::patch('task-status/{id}', [TaskStatusController::class, 'update']);
   Route::get('file-categories', [FileCategoryController::class, 'index']);
   Route::get('file-categories/{id}', [FileCategoryController::class, 'show']);
   Route::patch('file-categories/{id}', [FileCategoryController::class, 'update']);

   // Media attachments
   Route::get('projects/{projectId}/attachments', [MediaAttachmentController::class, 'listProjectAttachments']);
   Route::post('projects/{projectId}/attachments', [MediaAttachmentController::class, 'addProjectAttachment']);
   Route::delete('projects/{projectId}/attachments/{attachmentId}', [MediaAttachmentController::class, 'deleteProjectAttachment']);

   Route::get('tasks/{taskId}/attachments', [MediaAttachmentController::class, 'listTaskAttachments']);
   Route::post('tasks/{taskId}/attachments', [MediaAttachmentController::class, 'addTaskAttachment']);
   Route::delete('tasks/{taskId}/attachments/{attachmentId}', [MediaAttachmentController::class, 'deleteTaskAttachment']);
});

// =======================
// SMTP SETTINGS
// =======================
Route::middleware(['auth.api'])->prefix('smtp')->group(function () {
    Route::get('/settings', [SmtpSettingController::class, 'getSettings']);
    Route::put('/settings/{id}', [SmtpSettingController::class, 'updateSetting'])->middleware('admin.api');
    Route::post('/test', [SmtpSettingController::class, 'testConnection'])->middleware('admin.api');
});

// =======================
// EMAIL TEMPLATES
// =======================
Route::middleware(['auth.api', 'admin.api'])->prefix('email-templates')->group(function () {
    Route::get('/', [EmailTemplateController::class, 'index']);
    Route::get('/{id}', [EmailTemplateController::class, 'show']);
    Route::post('/', [EmailTemplateController::class, 'store']);
    Route::put('/{id}', [EmailTemplateController::class, 'update']);
    Route::delete('/{id}', [EmailTemplateController::class, 'destroy']);
    Route::post('/{id}/restore', [EmailTemplateController::class, 'restore']);
    Route::post('/test/send', [EmailTemplateController::class, 'sendTest']);
});

// =======================
// QUOTES (COTIZACIONES)
// =======================
Route::middleware(['auth.api', 'admin.api'])->prefix('quotes')->group(function () {
    // CRUD básico de cotizaciones
    Route::get('/', [QuoteController::class, 'index']);
    Route::post('/', [QuoteController::class, 'store']);
    Route::get('/{id}', [QuoteController::class, 'show']);
    Route::put('/{id}', [QuoteController::class, 'update']);
    Route::delete('/{id}', [QuoteController::class, 'destroy']);
    
    // Acciones adicionales
    Route::post('/{id}/restore', [QuoteController::class, 'restore']);
    Route::post('/{id}/duplicate', [QuoteController::class, 'duplicate']);
    Route::patch('/{id}/status', [QuoteController::class, 'updateStatus']);
    
    // Items de cotización
    Route::post('/{quoteId}/items', [QuoteController::class, 'addItem']);
    Route::put('/{quoteId}/items/{itemId}', [QuoteController::class, 'updateItem']);
    Route::delete('/{quoteId}/items/{itemId}', [QuoteController::class, 'removeItem']);
    Route::post('/{quoteId}/items/reorder', [QuoteController::class, 'reorderItems']);
    
    // PDF
    Route::get('/{id}/pdf/download', [QuoteController::class, 'downloadPdf']);
    Route::get('/{id}/pdf/preview', [QuoteController::class, 'previewPdf']);
    Route::get('/{id}/pdf/base64', [QuoteController::class, 'getPdfBase64']);
});

// =======================
// QUOTE SETTINGS (CONFIGURACIÓN DE COTIZACIONES)
// =======================
Route::middleware(['auth.api', 'admin.api'])->prefix('quote-settings')->group(function () {
    Route::get('/', [QuoteSettingController::class, 'show']);
    Route::put('/', [QuoteSettingController::class, 'update']);
    
    // Gestión de imágenes
    Route::post('/logo', [QuoteSettingController::class, 'updateCompanyLogo']);
    Route::delete('/logo', [QuoteSettingController::class, 'removeCompanyLogo']);
    Route::post('/background-image', [QuoteSettingController::class, 'updateBackgroundImage']);
    Route::delete('/background-image', [QuoteSettingController::class, 'removeBackgroundImage']);
    Route::post('/last-page-image', [QuoteSettingController::class, 'updateLastPageImage']);
    Route::delete('/last-page-image', [QuoteSettingController::class, 'removeLastPageImage']);
    
    // Términos y notas por defecto
    Route::put('/terms-conditions', [QuoteSettingController::class, 'updateTermsConditions']);
    Route::put('/notes', [QuoteSettingController::class, 'updateNotes']);
});
