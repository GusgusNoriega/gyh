<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TaskStatusController extends Controller
{
    /**
     * GET /api/task-status
     * Catálogo de estados
     */
    public function index(Request $request): JsonResponse
    {
        $query = TaskStatus::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $order = $request->input('order', 'sort_order');
        $sort  = $request->input('sort', 'asc') === 'desc' ? 'desc' : 'asc';
        $valid = ['id', 'code', 'name', 'sort_order', 'created_at'];
        if (!in_array($order, $valid, true)) {
            $order = 'sort_order';
        }
        $query->orderBy($order, $sort)->orderBy('id', 'asc');

        // Paginación opcional
        if ($request->boolean('paginate', true)) {
            $perPage = (int) $request->input('per_page', 50);
            $perPage = max(1, min(200, $perPage));
            $data = $query->paginate($perPage);
        } else {
            $data = $query->get();
        }

        return $this->apiSuccess('Listado de estados de tarea', 'TASK_STATUS_LIST', $data);
    }

    /**
     * GET /api/task-status/{id}
     * Detalle de estado
     */
    public function show(Request $request, $id): JsonResponse
    {
        $status = TaskStatus::find($id);
        if (!$status) {
            return $this->apiNotFound('Estado no encontrado', 'TASK_STATUS_NOT_FOUND');
        }
        return $this->apiSuccess('Estado de tarea obtenido', 'TASK_STATUS_SHOWN', $status);
    }

    /**
     * PATCH /api/task-status/{id}
     * Actualizar estado (solo nombre, color, orden y is_closed)
     * El código e ID están protegidos y no se pueden modificar
     */
    public function update(Request $request, $id): JsonResponse
    {
        $status = TaskStatus::find($id);
        if (!$status) {
            return $this->apiNotFound('Estado no encontrado', 'TASK_STATUS_NOT_FOUND');
        }

        // Validar solo campos editables
        $validator = Validator::make($request->all(), [
            'name'       => ['nullable', 'string', 'max:255'],
            'color'      => ['nullable', 'string', 'max:32'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_closed'  => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        
        // Aplicar solo los campos permitidos
        foreach ($validated as $key => $value) {
            $status->{$key} = $value;
        }
        
        $status->updated_by = auth()->id() ?? $status->updated_by;
        $status->save();

        return $this->apiSuccess('Estado actualizado', 'TASK_STATUS_UPDATED', $status);
    }
}