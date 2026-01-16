<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_company',
        'company_name',
        'company_ruc',
        'project_type',
        'budget_up_to',
        'message',
        'status',
        'notes',
        'source',
        'ip',
        'user_agent',
        'thank_you_token',
        'thank_you_viewed_at',
    ];

    protected $casts = [
        'is_company' => 'boolean',
        'budget_up_to' => 'integer',
        'thank_you_viewed_at' => 'datetime',
    ];
}

