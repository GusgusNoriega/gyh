<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    /**
     * Estados permitidos para una cotización.
     *
     * Importante: debe mantenerse alineado con el enum en la migración.
     */
    public const STATUSES = ['draft', 'sent', 'accepted', 'rejected', 'expired'];

    protected $table = 'quotes';

    protected $fillable = [
        'quote_number',
        'user_id',
        'created_by',
        'title',
        'description',
        'currency_id',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total',
        'status',
        'valid_until',
        'estimated_start_date',
        'notes',
        'terms_conditions',
        'client_name',
        'client_ruc',
        'client_email',
        'client_phone',
        'client_address',
        'custom_background_image_id',
        'custom_last_page_image_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'valid_until' => 'date',
        'estimated_start_date' => 'date',
    ];

    /**
     * Boot method for the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quote) {
            if (empty($quote->quote_number)) {
                $quote->quote_number = self::generateQuoteNumber();
            }
        });
    }

    /**
     * Generate a unique quote number
     */
    public static function generateQuoteNumber(): string
    {
        $prefix = 'COT';
        $year = date('Y');
        $month = date('m');
        
        $lastQuote = self::withTrashed()
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastQuote ? ((int) substr($lastQuote->quote_number, -5)) + 1 : 1;
        
        return sprintf('%s-%s%s-%05d', $prefix, $year, $month, $sequence);
    }

    /**
     * Calculate totals based on items
     */
    public function calculateTotals(): self
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->total;
        });

        $this->subtotal = $subtotal;
        $this->tax_amount = ($subtotal - $this->discount_amount) * ($this->tax_rate / 100);
        $this->total = $subtotal - $this->discount_amount + $this->tax_amount;

        return $this;
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the client/user for this quote
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who created this quote
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the currency for this quote
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Get all items for this quote
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class, 'quote_id')->orderBy('sort_order');
    }

    /**
     * Get the custom background image
     */
    public function customBackgroundImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'custom_background_image_id');
    }

    /**
     * Get the custom last page image
     */
    public function customLastPageImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'custom_last_page_image_id');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get the client name (from user or custom field)
     */
    public function getClientDisplayNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->client_name ?? 'Sin cliente';
    }

    /**
     * Get the client email (from user or custom field)
     */
    public function getClientDisplayEmailAttribute(): ?string
    {
        if ($this->user) {
            return $this->user->email;
        }
        return $this->client_email;
    }

    // ==================== SCOPES ====================

    /**
     * Scope for draft quotes
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for sent quotes
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for accepted quotes
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for expired quotes
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere(function ($q) {
                $q->whereNotNull('valid_until')
                    ->where('valid_until', '<', now())
                    ->whereNotIn('status', ['accepted', 'rejected']);
            });
    }
}
