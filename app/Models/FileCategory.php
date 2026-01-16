<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileCategory extends Model
{
    protected $table = 'file_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_system',
        'sort_order',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Registros pivot de adjuntos asociados a la categoría.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(MediaAttachment::class, 'file_category_id');
    }
}