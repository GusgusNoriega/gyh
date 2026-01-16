<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use App\Models\MediaAttachment;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para administrar adjuntos (media_assetables) en Projects y Tasks.
 * Mantiene el formato estándar de respuestas JSON del proyecto.
 */
class MediaAttachmentController extends Controller
{
    /**
     * GET /api/projects/{projectId}/attachments
     */
    public function listProjectAttachments(Request $request, int $projectId): JsonResponse
    {
        $project = Project::find($projectId);
        if (!$project) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }

        $query = $project->mediaAssets()->withPivot(['file_category_id', 'title', 'is_primary', 'sort_order', 'created_by']);

        // Filtros opcionales
        if ($request->filled('file_category_id')) {
            $query->wherePivot('file_category_id', $request->input('file_category_id'));
        }
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('media_assets.name', 'like', "%{$search}%")
                  ->orWhere('media_assets.alt', 'like', "%{$search}%");
            });
        }

        // Orden
        $order = $request->input('order', 'media_assetables.sort_order');
        $sort  = $request->input('sort', 'asc') === 'desc' ? 'desc' : 'asc';
        $valid = ['media_assetables.sort_order', 'media_assets.created_at', 'media_assets.name', 'media_assets.type'];
        if (!in_array($order, $valid, true)) {
            $order = 'media_assetables.sort_order';
        }
        $query->orderBy($order, $sort);

        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));
        $data = $query->paginate($perPage);

        return $this->apiSuccess('Adjuntos del proyecto', 'PROJECT_ATTACHMENTS', $data);
    }

    /**
     * POST /api/projects/{projectId}/attachments
     */
    public function addProjectAttachment(Request $request, int $projectId): JsonResponse
    {
        $project = Project::find($projectId);
        if (!$project) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }

        $validator = Validator::make($request->all(), [
            'media_asset_id'  => ['required', 'integer', 'exists:media_assets,id'],
            'file_category_id'=> ['nullable', 'integer', 'exists:file_categories,id'],
            'title'           => ['nullable', 'string', 'max:255'],
            'is_primary'      => ['nullable', 'boolean'],
            'sort_order'      => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();

        // Evitar duplicado por combinación (media_asset_id, attachable_type, attachable_id, file_category_id)
        $exists = MediaAttachment::query()
            ->where('media_asset_id', (int) $validated['media_asset_id'])
            ->where('attachable_type', Project::class)
            ->where('attachable_id', $project->id)
            ->where('file_category_id', $validated['file_category_id'] ?? null)
            ->exists();

        if ($exists) {
            return $this->apiError('El adjunto ya existe para este proyecto y categoría', 'PROJECT_ATTACHMENT_EXISTS', null, null, 409);
        }

        $attachment = MediaAttachment::create([
            'media_asset_id'  => (int) $validated['media_asset_id'],
            'attachable_type' => Project::class,
            'attachable_id'   => $project->id,
            'file_category_id'=> $validated['file_category_id'] ?? null,
            'title'           => $validated['title'] ?? null,
            'is_primary'      => (bool) ($validated['is_primary'] ?? false),
            'sort_order'      => (int) ($validated['sort_order'] ?? 0),
            'created_by'      => auth()->id() ?? 1,
        ]);

        return $this->apiCreated('Adjunto agregado al proyecto', 'PROJECT_ATTACHMENT_CREATED', $attachment);
    }

    /**
     * DELETE /api/projects/{projectId}/attachments/{attachmentId}
     */
    public function deleteProjectAttachment(Request $request, int $projectId, int $attachmentId): JsonResponse
    {
        $project = Project::find($projectId);
        if (!$project) {
            return $this->apiNotFound('Proyecto no encontrado', 'PROJECT_NOT_FOUND');
        }

        $attachment = MediaAttachment::query()
            ->where('id', $attachmentId)
            ->where('attachable_type', Project::class)
            ->where('attachable_id', $project->id)
            ->first();

        if (!$attachment) {
            return $this->apiNotFound('Adjunto no encontrado para este proyecto', 'PROJECT_ATTACHMENT_NOT_FOUND');
        }

        $attachment->delete();
        return $this->apiSuccess('Adjunto eliminado del proyecto', 'PROJECT_ATTACHMENT_DELETED', null);
    }

    /**
     * GET /api/tasks/{taskId}/attachments
     */
    public function listTaskAttachments(Request $request, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);
        if (!$task) {
            return $this->apiNotFound('Tarea no encontrada', 'TASK_NOT_FOUND');
        }

        $query = $task->mediaAssets()->withPivot(['file_category_id', 'title', 'is_primary', 'sort_order', 'created_by']);

        if ($request->filled('file_category_id')) {
            $query->wherePivot('file_category_id', $request->input('file_category_id'));
        }
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('media_assets.name', 'like', "%{$search}%")
                  ->orWhere('media_assets.alt', 'like', "%{$search}%");
            });
        }

        $order = $request->input('order', 'media_assetables.sort_order');
        $sort  = $request->input('sort', 'asc') === 'desc' ? 'desc' : 'asc';
        $valid = ['media_assetables.sort_order', 'media_assets.created_at', 'media_assets.name', 'media_assets.type'];
        if (!in_array($order, $valid, true)) {
            $order = 'media_assetables.sort_order';
        }
        $query->orderBy($order, $sort);

        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));
        $data = $query->paginate($perPage);

        return $this->apiSuccess('Adjuntos de la tarea', 'TASK_ATTACHMENTS', $data);
    }

    /**
     * POST /api/tasks/{taskId}/attachments
     */
    public function addTaskAttachment(Request $request, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);
        if (!$task) {
            return $this->apiNotFound('Tarea no encontrada', 'TASK_NOT_FOUND');
        }

        $validator = Validator::make($request->all(), [
            'media_asset_id'  => ['required', 'integer', 'exists:media_assets,id'],
            'file_category_id'=> ['nullable', 'integer', 'exists:file_categories,id'],
            'title'           => ['nullable', 'string', 'max:255'],
            'is_primary'      => ['nullable', 'boolean'],
            'sort_order'      => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $validated = $validator->validated();

        $exists = MediaAttachment::query()
            ->where('media_asset_id', (int) $validated['media_asset_id'])
            ->where('attachable_type', Task::class)
            ->where('attachable_id', $task->id)
            ->where('file_category_id', $validated['file_category_id'] ?? null)
            ->exists();

        if ($exists) {
            return $this->apiError('El adjunto ya existe para esta tarea y categoría', 'TASK_ATTACHMENT_EXISTS', null, null, 409);
        }

        $attachment = MediaAttachment::create([
            'media_asset_id'  => (int) $validated['media_asset_id'],
            'attachable_type' => Task::class,
            'attachable_id'   => $task->id,
            'file_category_id'=> $validated['file_category_id'] ?? null,
            'title'           => $validated['title'] ?? null,
            'is_primary'      => (bool) ($validated['is_primary'] ?? false),
            'sort_order'      => (int) ($validated['sort_order'] ?? 0),
            'created_by'      => auth()->id() ?? 1,
        ]);

        return $this->apiCreated('Adjunto agregado a la tarea', 'TASK_ATTACHMENT_CREATED', $attachment);
    }

    /**
     * DELETE /api/tasks/{taskId}/attachments/{attachmentId}
     */
    public function deleteTaskAttachment(Request $request, int $taskId, int $attachmentId): JsonResponse
    {
        $task = Task::find($taskId);
        if (!$task) {
            return $this->apiNotFound('Tarea no encontrada', 'TASK_NOT_FOUND');
        }

        $attachment = MediaAttachment::query()
            ->where('id', $attachmentId)
            ->where('attachable_type', Task::class)
            ->where('attachable_id', $task->id)
            ->first();

        if (!$attachment) {
            return $this->apiNotFound('Adjunto no encontrado para esta tarea', 'TASK_ATTACHMENT_NOT_FOUND');
        }

        $attachment->delete();
        return $this->apiSuccess('Adjunto eliminado de la tarea', 'TASK_ATTACHMENT_DELETED', null);
    }
}