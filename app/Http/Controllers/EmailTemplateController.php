<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailTemplateController extends Controller
{
    /**
     * Listar plantillas de correo.
     */
    public function index(Request $request)
    {
        try {
            $query = EmailTemplate::query();

            if ($request->boolean('only_trashed')) {
                $query->onlyTrashed();
            } elseif ($request->boolean('with_trashed') || $request->boolean('with_inactive')) {
                // alias retrocompatible para incluir elementos en papelera
                $query->withTrashed();
            }

            // Filtros opcionales
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('key', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            }

            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            $templates = $query
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return $this->apiSuccess(
                'Plantillas de correo obtenidas exitosamente',
                'EMAIL_TEMPLATES_FETCHED',
                $templates,
                200
            );
        } catch (Throwable $e) {
            Log::error('Error al listar plantillas de correo', ['exception' => $e]);
            return $this->apiServerError($e);
        }
    }

    /**
     * Mostrar una plantilla de correo específica.
     */
    public function show(Request $request, $id)
    {
        try {
            $builder = EmailTemplate::query();

            if ($request->boolean('with_trashed') || $request->boolean('with_inactive')) {
                $builder = $builder->withTrashed();
            }

            $template = $builder->findOrFail($id);

            return $this->apiSuccess(
                'Plantilla de correo obtenida exitosamente',
                'EMAIL_TEMPLATE_FETCHED',
                $template,
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->apiNotFound('Plantilla de correo no encontrada', 'EMAIL_TEMPLATE_NOT_FOUND');
        } catch (Throwable $e) {
            Log::error('Error al obtener plantilla de correo', ['id' => $id, 'exception' => $e]);
            return $this->apiServerError($e);
        }
    }

    /**
     * Crear una nueva plantilla de correo.
     */
    public function store(Request $request)
    {
        try {
            // Nota: La verificación de admin ya está en el middleware admin.api

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'key' => 'required|string|max:255|alpha_dash|unique:email_templates,key',
                'subject' => 'required|string|max:255',
                'content_html' => 'required|string',
                'variables_schema' => 'nullable|array',
                'description' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError(
                    $validator->errors(),
                    'Datos de entrada inválidos',
                    'VALIDATION_ERROR'
                );
            }

            $data = $request->only([
                'name',
                'key',
                'subject',
                'content_html',
                'variables_schema',
                'description',
                'is_active',
            ]);

            // Normalizar key (opcional, pero asegura consistencia)
            $data['key'] = strtolower($data['key']);

            $template = EmailTemplate::create($data);

            return $this->apiCreated(
                'Plantilla de correo creada exitosamente',
                'EMAIL_TEMPLATE_CREATED',
                $template
            );
        } catch (Throwable $e) {
            Log::error('Error al crear plantilla de correo', ['payload' => $request->all(), 'exception' => $e]);
            return $this->apiServerError($e);
        }
    }

    /**
     * Actualizar una plantilla de correo.
     */
    public function update(Request $request, $id)
    {
        try {
            // Nota: La verificación de admin ya está en el middleware admin.api

            $template = EmailTemplate::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'key' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    'alpha_dash',
                    'unique:email_templates,key,' . $template->id,
                ],
                'subject' => 'sometimes|required|string|max:255',
                'content_html' => 'sometimes|required|string',
                'variables_schema' => 'sometimes|nullable|array',
                'description' => 'sometimes|nullable|string|max:255',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError(
                    $validator->errors(),
                    'Datos de entrada inválidos',
                    'VALIDATION_ERROR'
                );
            }

            $data = $request->only([
                'name',
                'key',
                'subject',
                'content_html',
                'variables_schema',
                'description',
                'is_active',
            ]);

            if (isset($data['key'])) {
                $data['key'] = strtolower($data['key']);
            }

            $template->update($data);

            return $this->apiSuccess(
                'Plantilla de correo actualizada exitosamente',
                'EMAIL_TEMPLATE_UPDATED',
                $template->fresh(),
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->apiNotFound('Plantilla de correo no encontrada', 'EMAIL_TEMPLATE_NOT_FOUND');
        } catch (Throwable $e) {
            Log::error('Error al actualizar plantilla de correo', [
                'id' => $id,
                'payload' => $request->all(),
                'exception' => $e,
            ]);
            return $this->apiServerError($e);
        }
    }

    /**
     * Eliminar (soft delete) una plantilla de correo.
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Nota: La verificación de admin ya está en el middleware admin.api

            $template = EmailTemplate::findOrFail($id);

            // Soft delete
            $template->delete();

            return $this->apiSuccess(
                'Plantilla de correo enviada a la papelera exitosamente',
                'EMAIL_TEMPLATE_TRASHED',
                null,
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->apiNotFound('Plantilla de correo no encontrada', 'EMAIL_TEMPLATE_NOT_FOUND');
        } catch (Throwable $e) {
            Log::error('Error al eliminar plantilla de correo', ['id' => $id, 'exception' => $e]);
            return $this->apiServerError($e);
        }
    }

    /**
     * Restaurar una plantilla de correo eliminada (soft delete).
     */
    public function restore(Request $request, $id)
    {
        try {
            // Nota: La verificación de admin ya está en el middleware admin.api

            $template = EmailTemplate::onlyTrashed()->findOrFail($id);
            $template->restore();

            return $this->apiSuccess(
                'Plantilla de correo restaurada exitosamente',
                'EMAIL_TEMPLATE_RESTORED',
                $template->fresh(),
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->apiNotFound('Plantilla de correo no encontrada en la papelera', 'EMAIL_TEMPLATE_NOT_FOUND');
        } catch (Throwable $e) {
            Log::error('Error al restaurar plantilla de correo', ['id' => $id, 'exception' => $e]);
            return $this->apiServerError($e);
        }
    }

    /**
     * Enviar un correo de prueba usando una plantilla.
     */
    public function sendTest(Request $request)
    {
        try {
            // Nota: La verificación de admin ya está en el middleware admin.api

            $validator = Validator::make($request->all(), [
                'template_key' => 'required|string|exists:email_templates,key',
                'to' => 'required|email',
                'data' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->apiValidationError(
                    $validator->errors(),
                    'Datos de entrada inválidos',
                    'VALIDATION_ERROR'
                );
            }

            $templateKey = $request->template_key;
            $to = $request->to;
            $data = $request->data ?? [];

            $emailService = new EmailTemplateService();
            $emailService->send(
                templateKey: $templateKey,
                to: $to,
                data: $data
            );

            return $this->apiSuccess(
                'Correo de prueba enviado exitosamente a ' . $to,
                'TEST_EMAIL_SENT',
                [
                    'template_key' => $templateKey,
                    'to' => $to,
                    'data' => $data,
                ],
                200
            );
        } catch (\InvalidArgumentException $e) {
            return $this->apiError(
                $e->getMessage(),
                'TEMPLATE_ERROR',
                null,
                null,
                400
            );
        } catch (Throwable $e) {
            Log::error('Error al enviar correo de prueba', [
                'template_key' => $request->template_key ?? null,
                'to' => $request->to ?? null,
                'exception' => $e,
            ]);
            return $this->apiServerError($e);
        }
    }
}