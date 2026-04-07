<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;

class OrderRepository
{
    public function countAll(): int
    {
        return Order::query()->count();
    }

    public function countByStatus(string $status): int
    {
        return Order::query()->where('status', $status)->count();
    }

    public function sumPaidTotalCents(): int
    {
        return (int) Order::query()
            ->where('status', 'paid')
            ->sum('total_cents');
    }

    public function sumPaidTotalCentsForEvent(int $eventId): int
    {
        return (int) Order::query()
            ->where('event_id', $eventId)
            ->where('status', 'paid')
            ->sum('total_cents');
    }

    /**
     * Commandes en attente de paiement dont la session n’a pas expiré.
     */
    public function countActivePendingReservations(?Carbon $now = null): int
    {
        $now ??= now();

        return Order::query()
            ->where('status', 'pending_payment')
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            })
            ->count();
    }

    /**
     * Commandes marquées expirées (post-traitement job).
     */
    public function countExpiredReservationOrders(): int
    {
        return Order::query()->where('status', 'expired')->count();
    }

    /**
     * Sessions pending théoriquement expirées mais pas encore traitées (santé du flux).
     */
    public function countStalePendingOrders(?Carbon $now = null): int
    {
        $now ??= now();

        return Order::query()
            ->where('status', 'pending_payment')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->count();
    }

    /**
     * Série quotidienne sur la période : une entrée par jour calendaire (inclusif),
     * avec des jours à 0 quand il n’y a pas eu de commande payée.
     *
     * @return array<int, array{date: string, revenue_cents: int, orders_count: int}>
     */
    public function paidRevenueGroupedByDay(Carbon $from, Carbon $to): array
    {
        $orders = Order::query()
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->select(['paid_at', 'total_cents'])
            ->orderBy('paid_at')
            ->get();

        $grouped = [];
        foreach ($orders as $order) {
            $day = $order->paid_at->format('Y-m-d');
            if (! isset($grouped[$day])) {
                $grouped[$day] = [
                    'date' => $day,
                    'revenue_cents' => 0,
                    'orders_count' => 0,
                ];
            }
            $grouped[$day]['revenue_cents'] += (int) $order->total_cents;
            $grouped[$day]['orders_count']++;
        }

        $filled = [];
        $cursor = $from->copy()->startOfDay();
        $lastDay = $to->copy()->startOfDay();

        while ($cursor->lte($lastDay)) {
            $day = $cursor->format('Y-m-d');
            $filled[] = $grouped[$day] ?? [
                'date' => $day,
                'revenue_cents' => 0,
                'orders_count' => 0,
            ];
            $cursor->addDay();
        }

        return $filled;
    }

    public function countPaidOrdersBetween(Carbon $from, Carbon $to): int
    {
        return Order::query()
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->count();
    }

    public function sumPaidTotalCentsBetween(Carbon $from, Carbon $to): int
    {
        return (int) Order::query()
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->sum('total_cents');
    }

    public function averagePaidOrderValueCentsBetween(Carbon $from, Carbon $to): int
    {
        $avg = Order::query()
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->avg('total_cents');

        return (int) round((float) ($avg ?? 0));
    }
}
