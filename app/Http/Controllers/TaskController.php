<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * GET /api/tasks
     */
    public function index(Request $request): JsonResponse
    {
        $query = Task::query();

        // Soft deletes handling
        if ($request->boolean('only_trashed')) {
            $query->onlyTrashed();
        } elseif ($request->boolean('with_trashed') || $request->boolean('with_inactive')) {
            $query->withTrashed();
        }

        // Filters
        if ($request->filled('project_id')) {
            $query->whereIn('project_id', (array) $request->input('project_id'));
        }
        if ($request->filled('status_id')) {
            $query->whereIn('status_id', (array) $request->input('status_id'));
        }
        if ($request->filled('assignee_id')) {
            $query->whereIn('assignee_id', (array) $request->input('assignee_id'));
        }
        if ($request->filled('parent_id')) {
            $parentId = $request->input('parent_id');
            if ($parentId === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->whereIn('parent_id', (array) $parentId);
            }
        }
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
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
        $validOrders = [
            'created_at', 'updated_at', 'title', 'code',
            'start_planned', 'end_planned', 'priority', 'order_index', 'progress'
        ];
        if (!in_array($order, $validOrders, true)) {
            $order = 'created_at';
        }
        $sort = $sort === 'asc' ? 'asc' : 'desc';
        $query->orderBy($order, $sort);

        // Pagination
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $data = $query->paginate($perPage);

        return $this->apiSuccess('Listado de tareas', 'TASK_LIST', $data);
    }

    /**
     * POST /api/tasks
     */
    public function store(Request $request): JsonResponse
    {
        $projectId = $request->input('project_id');

        $validator = Validator::make($request->all(), [
            'project_id'    => ['required', 'integer', 'exists:projects,id'],
            'parent_id'     => ['nullable', 'integer', 'exists:tasks,id'],
            'status_id'     => ['nullable', 'integer', 'exists:task_status,id'],
            'code'          => [
                'nullable', 'string', 'max:50',
                Rule::unique('tasks', 'code')->where(function ($q) use ($projectId) {
                    return $q->where('project_id', $projectId);
                }),
            ],
            'wbs_code'      => ['nullable', 'string', 'max:50'],
            'wbs_path'      => ['nullable', 'string', 'max:255'],
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'priority'      => ['nullable', 'integer'],
            'order_index'   => ['nullable', 'integer'],
            'start_planned' => ['nullable', 'date'],
            'end_planned'   => ['nullable', 'date', 'after_or_equal:start_planned'],
            'start_actual'  => ['nullable', 'date'],
            'end_actual'    => ['nullable', 'date', 'after_or_equal:start_actual'],
            'estimate_hours'=> ['nullable', 'numeric', 'min:0'],
            'spent_hours'   => ['nullable', 'numeric', 'min:0'],
            'progress'      => ['nullable', 'integer', 'min:0', 'max:100'],
            'assignee_id'   => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $data = $validator->validated();

        // Validaciones de dominio
        if (!Project::find($data['project_id'])) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }
        if (!empty($data['parent_id'])) {
            $parent = Task::find($data['parent_id']);
            if (!$parent) {
                return $this->apiNotFound('Tarea padre no encontrada', 'TASK_PARENT_NOT_FOUND');
            }
            if ((int) $parent->project_id !== (int) $data['project_id']) {
                return $this->apiValidationError(['parent_id' => ['La tarea padre pertenece a otro proyecto']], 'Datos de entrada inválidos');
            }
        }

        $userId = auth()->id() ?? 1;
        $task = new Task($data);
        $task->created_by = $userId;
        $task->updated_by = $userId;
        $task->save();

        return $this->apiCreated('Tarea creada', 'TASK_CREATED', $task);
    }

    /**
     * GET /api/tasks/{id}
     */
    public function show(Request $request, $id): JsonResponse
    {
        $query = Task::where('id', $id);

        if ($request->boolean('only_trashed')) {
            $query->onlyTrashed();
        } elseif ($request->boolean('with_trashed') || $request->boolean('with_inactive')) {
            $query->withTrashed();
        }

        $task = $query->first();
        if (!$task) {
            return $this->apiNotFound('Tarea no encontrada', 'TASK_NOT_FOUND');
        }

        return $this->apiSuccess('Tarea obtenida', 'TASK_SHOWN', $task);
    }

    /**
     * PATCH /api/tasks/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $task = Task::withTrashed()->find($id);
        if (!$task) {
            return $this->apiNotFound('Tarea no encontrada', 'TASK_NOT_FOUND');
        }

        $projectId = $request->input('project_id', $task->project_id);

        $validator = Validator::make($request->all(), [
            'project_id'    => ['nullable', 'integer', 'exists:projects,id'],
            'parent_id'     => ['nullable', 'integer', 'exists:tasks,id'],
            'status_id'     => ['nullable', 'integer', 'exists:task_status,id'],
            'code'          => [
                'nullable', 'string', 'max:50',
                Rule::unique('tasks', 'code')->ignore($task->id)->where(function ($q) use ($projectId) {
                    return $q->where('project_id', $projectId);
                }),
            ],
            'wbs_code'      => ['nullable', 'string', 'max:50'],
            'wbs_path'      => ['nullable', 'string', 'max:255'],
            'title'         => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'priority'      => ['nullable', 'integer'],
            'order_index'   => ['nullable', 'integer'],
            'start_planned' => ['nullable', 'date'],
            'end_planned'   => ['nullable', 'date', 'after_or_equal:start_planned'],
            'start_actual'  => ['nullable', 'date'],
            'end_actual'    => ['nullable', 'date', 'after_or_equal:start_actual'],
            'estimate_hours'=> ['nullable', 'numeric', 'min:0'],
            'spent_hours'   => ['nullable', 'numeric', 'min:0'],
            'progress'      => ['nullable', 'integer', 'min:0', 'max:100'],
            'assignee_id'   => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();

        // Validaciones de dominio
        $targetProjectId = $validated['project_id'] ?? $task->project_id;
        if (!Project::find($targetProjectId)) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }
        if (!empty($validated['parent_id'])) {
            $parent = Task::find($validated['parent_id']);
            if (!$parent) {
                return $this->apiNotFound('Tarea padre no encontrada', 'TASK_PARENT_NOT_FOUND');
            }
            if ((int) $parent->project_id !== (int) $targetProjectId) {
                return $this->apiValidationError(['parent_id' => ['La tarea padre pertenece a otro proyecto']], 'Datos de entrada inválidos');
            }
            if ((int) $parent->id === (int) $task->id) {
                return $this->apiValidationError(['parent_id' => ['La tarea no puede ser su propia padre']], 'Datos de entrada inválidos');
            }
        }

        foreach ($validated as $k => $v) {
            $task->{$k} = $v;
        }
        $task->updated_by = auth()->id() ?? $task->updated_by;
        $task->save();

        return $this->apiSuccess('Tarea actualizada', 'TASK_UPDATED', $task);
    }

    /**
     * DELETE /api/tasks/{id}
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->apiNotFound('Tarea no encontrada', 'TASK_NOT_FOUND');
        }

        $task->delete();
        return $this->apiSuccess('Tarea enviada a la papelera', 'TASK_TRASHED', null);
    }
}