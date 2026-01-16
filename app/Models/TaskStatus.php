<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskStatus extends Model
{
    protected $table = 'task_status';

    protected $fillable = [
        'code',
        'name',
        'color',
        'is_closed',
        'sort_order',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Tareas asociadas a este estado.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}