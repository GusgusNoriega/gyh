<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItemTask extends Model
{
    protected $table = 'quote_item_tasks';

    protected $fillable = [
        'quote_item_id',
        'name',
        'description',
        'duration_value',
        'duration_unit',
        'sort_order',
    ];

    protected $casts = [
        'duration_value' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(QuoteItem::class, 'quote_item_id');
    }
}

