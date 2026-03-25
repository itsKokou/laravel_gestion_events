<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class TicketType extends Model
{
    protected $fillable = [
        'event_id',
        'code',
        'name',
        'price_cents',
        'currency',
        'quantity_limit',
        'sold_tickets',
        'sales_starts_at',
        'sales_ends_at',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'quantity_limit' => 'integer',
        'sold_tickets' => 'integer',
        'sales_starts_at' => 'datetime',
        'sales_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function assertBelongsToEvent(Event $event): void
    {
        if ((int) $this->event_id !== (int) $event->id) {
            throw ValidationException::withMessages([
                'ticket_type' => 'Tarif invalide pour cet événement.',
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    public function assertQuotaAllowsForEvent(Event $event, int $quantity): void
    {
        $this->assertBelongsToEvent($event);
        if ($this->quantity_limit === null) {
            return;
        }
        if (((int) $this->sold_tickets + $quantity) > (int) $this->quantity_limit) {
            throw ValidationException::withMessages([
                'quantity' => 'Quota dépassé pour ce tarif.',
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    public function reserve(int $quantity): void
    {
        if ($quantity <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantité invalide.']);
        }
        if ($this->quantity_limit !== null && ((int) $this->sold_tickets + $quantity) > (int) $this->quantity_limit) {
            throw ValidationException::withMessages(['quantity' => 'Quota dépassé pour ce tarif.']);
        }
        $this->increment('sold_tickets', $quantity);
    }

    public function release(int $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }
        $actual = min($quantity, (int) $this->sold_tickets);
        if ($actual > 0) {
            $this->decrement('sold_tickets', $actual);
        }
    }
}
