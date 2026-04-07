<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Validation\ValidationException;

/**
 * @property int $sold_tickets Nombre de places réservées (lignes ticket non annulées), indépendamment du paiement.
 */
class Event extends Model
{
    protected $fillable = [
        'created_by_user_id',
        'name',
        'slug',
        'starts_at',
        'ends_at',
        'venue_name',
        'venue_address',
        'theme',
        'description',
        'min_age',
        'capacity',
        'sold_tickets',
        'sales_ends_at',
        'hero_image_path',
        'status',
        'published_at',
        'archived_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'sales_ends_at' => 'datetime',
        'published_at' => 'datetime',
        'archived_at' => 'datetime',
        'min_age' => 'integer',
        'capacity' => 'integer',
        'sold_tickets' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(Addon::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }

    /**
     * Places libres pour de nouvelles réservations (non annulées).
     */
    public function remainingCapacity(): int
    {
        return max(0, (int) $this->capacity - (int) $this->sold_tickets);
    }

    public function canReserve(int $quantity): bool
    {
        return $quantity > 0 && $this->remainingCapacity() >= $quantity;
    }

    /**
     * @throws ValidationException
     */
    public function reserve(int $quantity): void
    {
        if ($quantity <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantité invalide.']);
        }
        if (! $this->canReserve($quantity)) {
            throw ValidationException::withMessages(['quantity' => 'Capacité insuffisante.']);
        }
        $this->increment('sold_tickets', $quantity);
    }

    /**
     * Libère jusqu’à la quantité demandée (plafonné par les places actuellement réservées).
     */
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

    /**
     * @param  array<int, array<string, mixed>>  $attendees
     *
     * @throws ValidationException
     */
    public function assertAttendeesMeetMinAge(array $attendees): void
    {
        foreach ($attendees as $idx => $a) {
            $birthdate = Carbon::createFromFormat('Y-m-d', $a['birthdate'])->startOfDay();
            $age = $birthdate->diffInYears($this->starts_at, false);
            if ($age < (int) $this->min_age) {
                throw ValidationException::withMessages([
                    "attendees.$idx.birthdate" => "Âge minimum non respecté ({$this->min_age}+).",
                ]);
            }
        }
    }
}
