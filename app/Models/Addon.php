<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'event_id',
        'code',
        'name',
        'price_cents',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'is_active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
