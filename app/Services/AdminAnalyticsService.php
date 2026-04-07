<?php

namespace App\Services;

use App\Repositories\EventRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TicketRepository;
use Carbon\Carbon;

/**
 * Agrégats admin (lecture seule) — structurés pour exposition API / futur temps réel (Echo).
 */
class AdminAnalyticsService
{
    public function __construct(
        private EventRepository $events,
        private OrderRepository $orders,
        private TicketRepository $tickets,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function getGlobalStats(): array
    {
        $now = now();
        $currentFrom = $now->copy()->subDays(30)->startOfDay();
        $previousFrom = $now->copy()->subDays(60)->startOfDay();
        $previousTo = $now->copy()->subDays(30)->endOfDay();

        $revenueCurrent = $this->orders->sumPaidTotalCentsBetween($currentFrom, $now);
        $revenuePrevious = $this->orders->sumPaidTotalCentsBetween($previousFrom, $previousTo);
        $growthRate = $revenuePrevious > 0
            ? round((($revenueCurrent - $revenuePrevious) / $revenuePrevious) * 100, 1)
            : null;

        $paidTickets = $this->tickets->countPaidNonCancelledGlobally();
        $checkedInTickets = $this->tickets->countCheckedInPaidNonCancelledGlobally();
        $checkinRate = $paidTickets > 0 ? round(($checkedInTickets / $paidTickets) * 100, 1) : null;

        return [
            'total_events' => $this->events->countAll(),
            'upcoming_events' => $this->events->countPublishedUpcoming($now),
            'total_orders' => $this->orders->countAll(),
            'total_paid_orders' => $this->orders->countByStatus('paid'),
            'total_revenue_cents' => $this->orders->sumPaidTotalCents(),
            'total_tickets_sold' => $paidTickets,
            'checked_in_tickets' => $checkedInTickets,
            'active_reservations' => $this->orders->countActivePendingReservations($now),
            'expired_reservations' => $this->orders->countExpiredReservationOrders(),
            'revenue_30d_cents' => $revenueCurrent,
            'revenue_previous_30d_cents' => $revenuePrevious,
            'revenue_growth_percent' => $growthRate,
            'avg_order_value_30d_cents' => $this->orders->averagePaidOrderValueCentsBetween($currentFrom, $now),
            'checkin_rate_percent' => $checkinRate,
            'reservation_flow_health' => [
                'stale_pending_orders' => $this->orders->countStalePendingOrders($now),
            ],
            'meta' => [
                'generated_at' => $now->toIso8601String(),
                'schema_version' => 1,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getEventStats(int $eventId): array
    {
        $event = $this->events->findOrFail($eventId);
        $reserved = (int) $event->sold_tickets;
        $soldPaid = $this->tickets->countPaidNonCancelledForEvent($eventId);
        $revenue = $this->orders->sumPaidTotalCentsForEvent($eventId);

        return [
            'event_id' => $event->id,
            'total_capacity' => (int) $event->capacity,
            'reserved_tickets' => $reserved,
            'sold_tickets_paid' => $soldPaid,
            'remaining_capacity' => $event->remainingCapacity(),
            'conversion_rate' => $this->computeConversionRate($reserved, $soldPaid),
            'total_revenue_cents' => $revenue,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
                'schema_version' => 1,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getRevenueStats(string $period = '30d'): array
    {
        [$from, $to] = $this->parsePeriod($period);
        $breakdown = $this->orders->paidRevenueGroupedByDay($from, $to);

        return [
            'period' => $period,
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'total_revenue_cents' => $this->orders->sumPaidTotalCentsBetween($from, $to),
            'paid_orders_count' => $this->orders->countPaidOrdersBetween($from, $to),
            'daily_breakdown' => $breakdown,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
                'schema_version' => 1,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getTicketSalesStats(int $eventId): array
    {
        $this->events->findOrFail($eventId);

        $byType = $this->tickets->paidSalesByTicketTypeForEvent($eventId);
        $totalSold = array_sum(array_column($byType, 'sold_count'));

        return [
            'event_id' => $eventId,
            'total_sold' => $totalSold,
            'by_ticket_type' => $byType,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
                'schema_version' => 1,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getConversionRate(int $eventId): array
    {
        $event = $this->events->findOrFail($eventId);
        $reserved = (int) $event->sold_tickets;
        $soldPaid = $this->tickets->countPaidNonCancelledForEvent($eventId);

        return [
            'event_id' => $eventId,
            'conversion_rate' => $this->computeConversionRate($reserved, $soldPaid),
            'reserved_tickets' => $reserved,
            'sold_tickets_paid' => $soldPaid,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }

    private function computeConversionRate(int $reserved, int $soldPaid): ?float
    {
        if ($reserved <= 0) {
            return null;
        }

        return round($soldPaid / $reserved, 4);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function parsePeriod(string $period): array
    {
        $to = now();

        return match (strtolower($period)) {
            '7d' => [$to->copy()->subDays(7)->startOfDay(), $to],
            '30d' => [$to->copy()->subDays(30)->startOfDay(), $to],
            '90d' => [$to->copy()->subDays(90)->startOfDay(), $to],
            'year' => [$to->copy()->subYear()->startOfDay(), $to],
            default => [$to->copy()->subDays(30)->startOfDay(), $to],
        };
    }
}
