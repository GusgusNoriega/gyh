<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * GET /api/projects
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::query();

        // Soft deletes handling
        if ($request->boolean('only_trashed')) {
            $query->onlyTrashed();
        } elseif ($request->boolean('with_trashed') || $request->boolean('with_inactive')) {
            $query->withTrashed();
        }

        // Filters
        if ($request->filled('owner_id')) {
            $query->whereIn('owner_id', (array) $request->input('owner_id'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('start_planned', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('end_planned', '<=', $request->date('to'));
        }

        // Sorting
        $sort = $request->input('sort', 'desc');
        $order = $request->input('order', 'created_at');
        $validOrders = ['created_at', 'updated_at', 'name', 'code', 'start_planned', 'end_planned', 'progress'];
        if (!in_array($order, $validOrders, true)) {
            $order = 'created_at';
        }
        $sort = $sort === 'asc' ? 'asc' : 'desc';
        $query->orderBy($order, $sort);

        // Pagination
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $data = $query->paginate($perPage);

        return $this->apiSuccess('Listado de proyectos', 'PROJECT_LIST', $data);
    }

    /**
     * POST /api/projects
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code'           => ['required', 'string', 'max:50', 'unique:projects,code'],
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'owner_id'       => ['nullable', 'integer', 'exists:users,id'],
            'baseline_start' => ['nullable', 'date'],
            'baseline_end'   => ['nullable', 'date', 'after_or_equal:baseline_start'],
            'start_planned'  => ['nullable', 'date'],
            'end_planned'    => ['nullable', 'date', 'after_or_equal:start_planned'],
            'start_actual'   => ['nullable', 'date'],
            'end_actual'     => ['nullable', 'date', 'after_or_equal:start_actual'],
            'progress'       => ['nullable', 'integer', 'min:0', 'max:100'],
            'color'          => ['nullable', 'string', 'max:32'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $data = $validator->validated();
        $userId = auth()->id() ?? 1;

        $project = new Project($data);
        $project->created_by = $userId;
        $project->updated_by = $userId;
        $project->save();

        return $this->apiCreated('Proyecto creado', 'PROJECT_CREATED', $project);
    }

    /**
     * GET /api/projects/{id}
     */
    public function show(Request $request, $id): JsonResponse
    {
        $query = Project::where('id', $id);

        if ($request->boolean('only_trashed')) {
            $query->onlyTrashed();
        } elseif ($request->boolean('with_trashed') || $request->boolean('with_inactive')) {
            $query->withTrashed();
        }

        $project = $query->first();
        if (!$project) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }

        return $this->apiSuccess('Proyecto obtenido', 'PROJECT_SHOWN', $project);
    }

    /**
     * PATCH /api/projects/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $project = Project::withTrashed()->find($id);
        if (!$project) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }

        $validator = Validator::make($request->all(), [
            'code'           => ['nullable', 'string', 'max:50', Rule::unique('projects', 'code')->ignore($project->id)],
            'name'           => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'owner_id'       => ['nullable', 'integer', 'exists:users,id'],
            'baseline_start' => ['nullable', 'date'],
            'baseline_end'   => ['nullable', 'date', 'after_or_equal:baseline_start'],
            'start_planned'  => ['nullable', 'date'],
            'end_planned'    => ['nullable', 'date', 'after_or_equal:start_planned'],
            'start_actual'   => ['nullable', 'date'],
            'end_actual'     => ['nullable', 'date', 'after_or_equal:start_actual'],
            'progress'       => ['nullable', 'integer', 'min:0', 'max:100'],
            'color'          => ['nullable', 'string', 'max:32'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        foreach ($validated as $k => $v) {
            $project->{$k} = $v;
        }
        $project->updated_by = auth()->id() ?? $project->updated_by;
        $project->save();

        return $this->apiSuccess('Proyecto actualizado', 'PROJECT_UPDATED', $project);
    }

    /**
     * DELETE /api/projects/{id}
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $project = Project::find($id);
        if (!$project) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }

        $project->delete();
        return $this->apiSuccess('Proyecto enviado a la papelera', 'PROJECT_TRASHED', null);
    }
}