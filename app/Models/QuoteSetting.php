<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteSetting extends Model
{
    protected $table = 'quote_settings';

    protected $fillable = [
        'company_name',
        'company_ruc',
        'company_address',
        'company_phone',
        'company_email',
        'company_logo_id',
        'background_image_id',
        'last_page_image_id',
        'default_terms_conditions',
        'default_notes',
        'default_tax_rate',
        'work_hours_per_day',
    ];

    protected $casts = [
        'default_tax_rate' => 'decimal:2',
        'work_hours_per_day' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the company logo image
     */
    public function companyLogo(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'company_logo_id');
    }

    /**
     * Get the default background image for all quote pages
     */
    public function backgroundImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'background_image_id');
    }

    /**
     * Get the last page image (for signatures, etc.)
     */
    public function lastPageImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'last_page_image_id');
    }

    // ==================== STATIC METHODS ====================

    /**
     * Get or create the settings record (singleton pattern)
     */
    public static function getSettings(): self
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'company_name' => config('app.name', 'Mi Empresa'),
                'default_tax_rate' => 0,
            ]);
        }

        return $settings;
    }

    /**
     * Update settings
     */
    public static function updateSettings(array $data): self
    {
        $settings = self::getSettings();
        $settings->update($data);
        return $settings->fresh();
    }

    // ==================== ACCESSORS ====================

    /**
     * Get the background image URL
     */
    public function getBackgroundImageUrlAttribute(): ?string
    {
        return $this->backgroundImage?->url;
    }

    /**
     * Get the last page image URL
     */
    public function getLastPageImageUrlAttribute(): ?string
    {
        return $this->lastPageImage?->url;
    }

    /**
     * Get the company logo URL
     */
    public function getCompanyLogoUrlAttribute(): ?string
    {
        return $this->companyLogo?->url;
    }
}
