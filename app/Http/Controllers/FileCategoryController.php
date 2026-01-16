<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FileCategoryController extends Controller
{
    /**
     * GET /api/file-categories
     * Catálogo de categorías de archivo
     */
    public function index(Request $request): JsonResponse
    {
        $query = FileCategory::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
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

        return $this->apiSuccess('Listado de categorías de archivo', 'FILE_CATEGORY_LIST', $data);
    }

    /**
     * GET /api/file-categories/{id}
     * Detalle de categoría
     */
    public function show(Request $request, $id): JsonResponse
    {
        $category = FileCategory::find($id);
        if (!$category) {
            return $this->apiNotFound('Categoría no encontrada', 'FILE_CATEGORY_NOT_FOUND');
        }
        return $this->apiSuccess('Categoría obtenida', 'FILE_CATEGORY_SHOWN', $category);
    }

    /**
     * PATCH /api/file-categories/{id}
     * Actualizar categoría (solo nombre, descripción y orden)
     * El código e ID están protegidos y no se pueden modificar
     */
    public function update(Request $request, $id): JsonResponse
    {
        $category = FileCategory::find($id);
        if (!$category) {
            return $this->apiNotFound('Categoría no encontrada', 'FILE_CATEGORY_NOT_FOUND');
        }

        // Validar solo campos editables
        $validator = Validator::make($request->all(), [
            'name'        => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        
        // Aplicar solo los campos permitidos
        foreach ($validated as $key => $value) {
            $category->{$key} = $value;
        }
        
        $category->updated_by = auth()->id() ?? $category->updated_by;
        $category->save();

        return $this->apiSuccess('Categoría actualizada', 'FILE_CATEGORY_UPDATED', $category);
    }
}