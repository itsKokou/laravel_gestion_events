<?php

namespace App\Repositories;

use App\Models\Event;
use Carbon\Carbon;

class EventRepository
{
    public function countAll(): int
    {
        return Event::query()->count();
    }

    public function findOrFail(int $id): Event
    {
        return Event::query()->findOrFail($id);
    }

    public function countPublishedUpcoming(?Carbon $now = null): int
    {
        $now ??= now();

        return Event::query()
            ->where('status', 'published')
            ->where('starts_at', '>=', $now)
            ->count();
    }
}
