<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    use SoftDeletes;

    protected $table = 'tasks';

    protected $fillable = [
        'project_id',
        'parent_id',
        'status_id',
        'code',
        'wbs_code',
        'wbs_path',
        'title',
        'description',
        'priority',
        'order_index',
        'start_planned',
        'end_planned',
        'start_actual',
        'end_actual',
        'estimate_hours',
        'spent_hours',
        'progress',
        'assignee_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_planned' => 'date',
        'end_planned'   => 'date',
        'start_actual'  => 'date',
        'end_actual'    => 'date',
        'estimate_hours'=> 'decimal:2',
        'spent_hours'   => 'decimal:2',
        'progress'      => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

   public function parent(): BelongsTo
   {
       return $this->belongsTo(Task::class, 'parent_id');
   }

   public function children(): HasMany
   {
       return $this->hasMany(Task::class, 'parent_id');
   }

   public function status(): BelongsTo
   {
       return $this->belongsTo(TaskStatus::class, 'status_id');
   }

   public function assignee(): BelongsTo
   {
       return $this->belongsTo(User::class, 'assignee_id');
   }

   public function mediaAssets(): MorphToMany
   {
       return $this->morphToMany(MediaAsset::class, 'attachable', 'media_assetables')
           ->withPivot(['file_category_id', 'title', 'is_primary', 'sort_order', 'created_by'])
           ->withTimestamps();
   }

   // Tasks that must finish/start before this task (predecessors)
   public function predecessors(): BelongsToMany
   {
       return $this->belongsToMany(
           Task::class,
           'task_dependencies',
           'successor_task_id',
           'predecessor_task_id'
       )->withPivot(['project_id', 'type', 'lag_minutes'])
        ->withTimestamps();
   }

   // Tasks that depend on this task (successors)
   public function successors(): BelongsToMany
   {
       return $this->belongsToMany(
           Task::class,
           'task_dependencies',
           'predecessor_task_id',
           'successor_task_id'
       )->withPivot(['project_id', 'type', 'lag_minutes'])
        ->withTimestamps();
   }
}