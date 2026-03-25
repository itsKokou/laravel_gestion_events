<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Persistance des lignes billet : création et annulation (soft) pour une commande.
 * Ne modifie pas `events.sold_tickets` — la capacité est gérée uniquement par `Event`.
 */
class TicketService
{
    /**
     * @param  array<int, array<string, mixed>>  $attendees
     */
    public function issueForOrder(
        Order $order,
        Event $event,
        TicketType $ticketType,
        array $attendees,
        Carbon $issuedAt,
    ): void {
        foreach ($attendees as $a) {
            Ticket::create([
                'order_id' => $order->id,
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'attendee_first_name' => $a['first_name'],
                'attendee_last_name' => $a['last_name'],
                'attendee_email' => $a['email'],
                'attendee_phone' => $a['phone'] ?? null,
                'attendee_birthdate' => $a['birthdate'],
                'qr_token' => Str::random(48),
                'issued_at' => $issuedAt,
            ]);
        }
    }

    public function cancelAllForOrder(Order $order): void
    {
        $order->tickets()->update(['cancelled_at' => now()]);
    }
}
