<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskDependencyController extends Controller
{
    /**
     * GET /api/task-dependencies
     */
    public function index(Request $request): JsonResponse
    {
        $query = DB::table('task_dependencies');

        if ($request->filled('project_id')) {
            $query->where('project_id', (int) $request->input('project_id'));
        }
        if ($request->filled('predecessor_task_id')) {
            $query->whereIn('predecessor_task_id', (array) $request->input('predecessor_task_id'));
        }
        if ($request->filled('successor_task_id')) {
            $query->whereIn('successor_task_id', (array) $request->input('successor_task_id'));
        }
        if ($request->filled('type')) {
            $query->whereIn('type', (array) $request->input('type'));
        }

        $order = $request->input('order', 'created_at');
        $sort  = $request->input('sort', 'desc') === 'asc' ? 'asc' : 'desc';
        $validOrders = ['created_at', 'updated_at', 'project_id', 'predecessor_task_id', 'successor_task_id', 'type', 'lag_minutes'];
        if (!in_array($order, $validOrders, true)) {
            $order = 'created_at';
        }
        $query->orderBy($order, $sort);

        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $data = $query->paginate($perPage);

        return $this->apiSuccess('Listado de dependencias', 'TASK_DEPENDENCY_LIST', $data);
    }

    /**
     * POST /api/task-dependencies
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'project_id'           => ['required', 'integer', 'exists:projects,id'],
            'predecessor_task_id'  => ['required', 'integer', 'exists:tasks,id'],
            'successor_task_id'    => ['required', 'integer', 'exists:tasks,id'],
            'type'                 => ['required', 'string', 'in:FS,SS,FF,SF'],
            'lag_minutes'          => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $data = $validator->validated();
        $projectId = (int) $data['project_id'];
        $pred = Task::find((int) $data['predecessor_task_id']);
        $succ = Task::find((int) $data['successor_task_id']);

        if (!$pred || !$succ) {
            return $this->apiNotFound('Tarea relacionada no encontrada', 'TASK_NOT_FOUND');
        }

        if ((int) $pred->project_id !== $projectId || (int) $succ->project_id !== $projectId) {
            return $this->apiValidationError([
                'project_id' => ['Ambas tareas deben pertenecer al mismo proyecto indicado'],
            ], 'Datos de entrada inválidos');
        }

        if ((int) $pred->id === (int) $succ->id) {
            return $this->apiValidationError([
                'successor_task_id' => ['Una tarea no puede depender de sí misma'],
            ], 'Datos de entrada inválidos');
        }

        // Verificar duplicado
        $exists = DB::table('task_dependencies')
            ->where('predecessor_task_id', $pred->id)
            ->where('successor_task_id', $succ->id)
            ->exists();

        if ($exists) {
            return $this->apiError('La dependencia ya existe', 'DEPENDENCY_EXISTS', null, null, 409);
        }

        $id = DB::table('task_dependencies')->insertGetId([
            'project_id'          => $projectId,
            'predecessor_task_id' => $pred->id,
            'successor_task_id'   => $succ->id,
            'type'                => $data['type'],
            'lag_minutes'         => (int) ($data['lag_minutes'] ?? 0),
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $created = DB::table('task_dependencies')->where('id', $id)->first();

        return $this->apiCreated('Dependencia creada', 'TASK_DEPENDENCY_CREATED', $created);
    }

    /**
     * GET /api/task-dependencies/{id}
     */
    public function show(Request $request, $id): JsonResponse
    {
        $row = DB::table('task_dependencies')->where('id', (int) $id)->first();
        if (!$row) {
            return $this->apiNotFound('Dependencia no encontrada', 'TASK_DEPENDENCY_NOT_FOUND');
        }
        return $this->apiSuccess('Dependencia obtenida', 'TASK_DEPENDENCY_SHOWN', $row);
    }

    /**
     * DELETE /api/task-dependencies/{id}
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $row = DB::table('task_dependencies')->where('id', (int) $id)->first();
        if (!$row) {
            return $this->apiNotFound('Dependencia no encontrada', 'TASK_DEPENDENCY_NOT_FOUND');
        }

        DB::table('task_dependencies')->where('id', (int) $id)->delete();
        return $this->apiSuccess('Dependencia eliminada', 'TASK_DEPENDENCY_DELETED', null);
    }
}