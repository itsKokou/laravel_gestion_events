<?php

namespace App\Services;

use App\Models\ActivityLog;

/**
 * Journal d’audit append-only (observabilité SaaS).
 */
class ActivityLogService
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function log(string $type, ?int $eventId, array $payload, ?int $userId = null): void
    {
        ActivityLog::create([
            'type' => $type,
            'user_id' => $userId,
            'event_id' => $eventId,
            'payload' => $payload,
            'created_at' => now(),
        ]);
    }
}
