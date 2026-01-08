<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model;

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
}
