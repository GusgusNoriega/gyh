<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'name',
        'description',
        'owner_id',
        'baseline_start',
        'baseline_end',
        'start_planned',
        'end_planned',
        'start_actual',
        'end_actual',
        'progress',
        'color',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'baseline_start' => 'date',
        'baseline_end'   => 'date',
        'start_planned'  => 'date',
        'end_planned'    => 'date',
        'start_actual'   => 'date',
        'end_actual'     => 'date',
        'progress'       => 'integer',
    ];

    /**
     * Owner del proyecto.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Auditoría: creado por.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Auditoría: actualizado por.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Tareas del proyecto.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    /**
     * Medios adjuntos (MediaAsset) vía pivot polimórfico media_assetables.
     */
    public function mediaAssets(): MorphToMany
    {
        return $this->morphToMany(MediaAsset::class, 'attachable', 'media_assetables')
            ->withPivot(['file_category_id', 'title', 'is_primary', 'sort_order', 'created_by'])
            ->withTimestamps();
    }
}