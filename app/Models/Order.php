<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'event_id',
        'order_number',
        'customer_email',
        'customer_phone',
        'status',
        'currency',
        'subtotal_cents',
        'addons_total_cents',
        'total_cents',
        'agreed_terms_at',
        'expires_at',
        'payment_provider',
        'payment_reference',
        'paid_at',
        'cancelled_at',
        'metadata',
    ];

    protected $casts = [
        'subtotal_cents' => 'integer',
        'addons_total_cents' => 'integer',
        'total_cents' => 'integer',
        'agreed_terms_at' => 'datetime',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
