<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteItem extends Model
{
    protected $table = 'quote_items';

    protected $fillable = [
        'quote_id',
        'name',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'subtotal',
        'total',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Boot method for the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->calculateTotals();
        });

        static::saved(function ($item) {
            // Recalcular totales de la cotización padre
            if ($item->quote) {
                $item->quote->calculateTotals();
                $item->quote->save();
            }
        });

        static::deleted(function ($item) {
            // Recalcular totales de la cotización padre después de eliminar
            if ($item->quote) {
                $item->quote->calculateTotals();
                $item->quote->save();
            }
        });
    }

    /**
     * Calculate item totals
     */
    public function calculateTotals(): self
    {
        $this->subtotal = $this->quantity * $this->unit_price;
        
        // Calcular descuento
        if ($this->discount_percent > 0) {
            $this->discount_amount = $this->subtotal * ($this->discount_percent / 100);
        }
        
        $this->total = $this->subtotal - $this->discount_amount;

        return $this;
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the quote that owns this item
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    /**
     * Tasks breakdown for this quote item
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(QuoteItemTask::class, 'quote_item_id')->orderBy('sort_order');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        $currency = $this->quote?->currency;
        $symbol = $currency?->symbol ?? '$';
        return $symbol . number_format($this->unit_price, 2);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute(): string
    {
        $currency = $this->quote?->currency;
        $symbol = $currency?->symbol ?? '$';
        return $symbol . number_format($this->total, 2);
    }
}
