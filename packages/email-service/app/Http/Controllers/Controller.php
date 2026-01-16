<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Respuesta de éxito estándar.
     */
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

    /**
     * Respuesta 201 Created estándar.
     */
    protected function apiCreated(string $message = 'Creado', ?string $code = null, $data = null, array $extra = []): JsonResponse
    {
        return $this->apiSuccess($message, $code, $data, 201, $extra);
    }

    /**
     * Respuesta de error estándar.
     */
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

    /**
     * 422 Unprocessable Entity (errores de validación).
     */
    protected function apiValidationError($errors, string $message = 'Datos de entrada inválidos', string $code = 'VALIDATION_ERROR'): JsonResponse
    {
        $errorsArray = $errors instanceof \Illuminate\Support\MessageBag ? $errors->toArray() : $errors;
        return $this->apiError($message, $code, $errorsArray, null, 422);
    }

    /**
     * 403 Forbidden.
     */
    protected function apiForbidden(string $message = 'Permisos insuficientes', string $code = 'FORBIDDEN'): JsonResponse
    {
        return $this->apiError($message, $code, null, null, 403);
    }

    /**
     * 404 Not Found.
     */
    protected function apiNotFound(string $message = 'Recurso no encontrado', string $code = 'NOT_FOUND'): JsonResponse
    {
        return $this->apiError($message, $code, null, null, 404);
    }

    /**
     * 500 Server Error con mensaje acorde a APP_DEBUG.
     */
    protected function apiServerError(\Throwable $e, string $code = 'SERVER_ERROR'): JsonResponse
    {
        $message = config('app.debug') ? $e->getMessage() : 'Error inesperado del servidor';
        return $this->apiError($message, $code, null, null, 500);
    }
}