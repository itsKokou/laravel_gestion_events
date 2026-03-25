<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;

class TicketRepository
{
    /**
     * Billets non annulés rattachés à une commande payée.
     */
    public function countPaidNonCancelledForEvent(int $eventId): int
    {
        return Ticket::query()
            ->where('tickets.event_id', $eventId)
            ->active()
            ->whereHas('order', fn ($q) => $q->where('orders.status', 'paid'))
            ->count();
    }

    public function countPaidNonCancelledGlobally(): int
    {
        return Ticket::query()
            ->active()
            ->whereHas('order', fn ($q) => $q->where('orders.status', 'paid'))
            ->count();
    }

    public function countCheckedInPaidNonCancelledGlobally(): int
    {
        return Ticket::query()
            ->active()
            ->whereNotNull('checked_in_at')
            ->whereHas('order', fn ($q) => $q->where('orders.status', 'paid'))
            ->count();
    }

    /**
     * Ventes par tarif (commandes payées, billets actifs).
     * Une requête commandes + tickets pour éviter N+1 ; agrégation en mémoire (volume admin).
     *
     * @return array<int, array{ticket_type_id: int, code: string|null, name: string|null, sold_count: int, revenue_cents: int}>
     */
    public function paidSalesByTicketTypeForEvent(int $eventId): array
    {
        $orders = Order::query()
            ->where('event_id', $eventId)
            ->where('status', 'paid')
            ->whereHas('tickets', fn ($q) => $q->whereNull('cancelled_at'))
            ->with(['tickets' => fn ($q) => $q->whereNull('cancelled_at')])
            ->get();

        $agg = [];
        foreach ($orders as $order) {
            $first = $order->tickets->first();
            if ($first === null) {
                continue;
            }
            $tid = (int) $first->ticket_type_id;
            if (! isset($agg[$tid])) {
                $agg[$tid] = ['sold_count' => 0, 'revenue_cents' => 0];
            }
            $agg[$tid]['sold_count'] += $order->tickets->count();
            $agg[$tid]['revenue_cents'] += (int) $order->subtotal_cents;
        }

        if ($agg === []) {
            return [];
        }

        $types = TicketType::query()
            ->whereIn('id', array_keys($agg))
            ->get()
            ->keyBy('id');

        $out = [];
        foreach ($agg as $tid => $data) {
            $t = $types->get($tid);
            $out[] = [
                'ticket_type_id' => $tid,
                'code' => $t?->code,
                'name' => $t?->name,
                'sold_count' => $data['sold_count'],
                'revenue_cents' => $data['revenue_cents'],
            ];
        }

        usort($out, fn ($a, $b) => $a['ticket_type_id'] <=> $b['ticket_type_id']);

        return $out;
    }
}
