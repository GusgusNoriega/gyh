<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
            // Solo administradores pueden actualizar configuraciones
            // Ajusta esta verificación según tu sistema de roles
            if (!$request->user() || !$request->user()->hasRole('admin')) {
                return $this->apiForbidden(
                    'No tienes permisos para actualizar configuraciones SMTP',
                    'FORBIDDEN'
                );
            }

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
}