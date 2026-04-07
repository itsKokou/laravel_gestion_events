<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Event;
use App\Models\Order;
use App\Models\TicketType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Orchestration : réservation (hold), expiration, paiement — règles capacité / quota / âge sur les modèles de domaine.
 */
class ReservationService
{
    private const RESERVATION_TTL_MINUTES = 30;

    public function __construct(
        private OrderService $orderService,
        private TicketService $ticketService,
        private ActivityLogService $activityLog,
    ) {}

    /**
     * @return array{event: Event, activeTicketType: TicketType}
     */
    public function bookingContextForCreateForm(Event $event): array
    {
        abort_unless($event->status === 'published', 404);

        $now = now();

        $event->load([
            'ticketTypes' => fn ($q) => $q->where('is_active', true)
                ->where(function ($query) use ($now) {
                    $query->where(function ($q) use ($now) {
                        $q->whereNotNull('sales_starts_at')
                            ->whereNotNull('sales_ends_at')
                            ->where('sales_starts_at', '<=', $now)
                            ->where('sales_ends_at', '>=', $now);
                    })->orWhere(function ($q) {
                        $q->whereNull('sales_starts_at')
                            ->whereNull('sales_ends_at');
                    });
                })
                ->orderBy('sort_order')
                ->orderBy('price_cents'),
            'addons' => fn ($q) => $q->where('is_active', true)->orderBy('price_cents'),
        ]);

        $activeTicketType = $event->ticketTypes->first();
        if (! $activeTicketType) {
            abort(404, 'Aucun tarif disponible pour le moment.');
        }

        return [
            'event' => $event,
            'activeTicketType' => $activeTicketType,
        ];
    }

    /**
     * Crée une réservation : hold de capacité + commande pending + billets.
     *
     * @param  array<string, mixed>  $data  Données validées (StoreReservationRequest)
     */
    public function createReservation(Event $event, array $data): Order
    {
        $now = now();
        $quantity = (int) $data['quantity'];
        $attendees = $data['attendees'];

        if (count($attendees) !== $quantity) {
            throw ValidationException::withMessages([
                'attendees' => 'Le nombre de participants doit correspondre à la quantité de billets.',
            ]);
        }

        $ticketType = $this->resolveActiveTicketType($event, $now);
        if (! $ticketType) {
            throw ValidationException::withMessages([
                'ticket_type' => 'Aucun tarif disponible pour le moment.',
            ]);
        }

        $addons = $this->resolveAddons($event, $data['addons'] ?? []);

        $event->assertAttendeesMeetMinAge($attendees);

        $subtotal = $ticketType->price_cents * $quantity;
        $addonsTotal = (int) $addons->sum('price_cents');
        $total = $subtotal + $addonsTotal;

        return DB::transaction(function () use (
            $data,
            $event,
            $ticketType,
            $quantity,
            $attendees,
            $subtotal,
            $addonsTotal,
            $total,
            $addons,
            $now
        ) {
            $eventLocked = Event::query()->lockForUpdate()->findOrFail($event->id);
            $ticketTypeLocked = TicketType::query()->lockForUpdate()->findOrFail($ticketType->id);

            $ticketTypeLocked->assertQuotaAllowsForEvent($eventLocked, $quantity);
            $eventLocked->reserve($quantity);
            $ticketTypeLocked->reserve($quantity);

            $orderNumber = $this->orderService->generateOrderNumber();
            $expiresAt = $now->copy()->addMinutes(self::RESERVATION_TTL_MINUTES);

            $order = $this->orderService->createPendingOrder([
                'event_id' => $eventLocked->id,
                'order_number' => $orderNumber,
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'status' => 'pending_payment',
                'currency' => $ticketTypeLocked->currency,
                'subtotal_cents' => $subtotal,
                'addons_total_cents' => $addonsTotal,
                'total_cents' => $total,
                'agreed_terms_at' => $now,
                'expires_at' => $expiresAt,
                'metadata' => [
                    'addons' => $addons->map(fn ($a) => [
                        'id' => $a->id,
                        'code' => $a->code,
                        'name' => $a->name,
                        'price_cents' => $a->price_cents,
                    ])->values()->all(),
                ],
            ]);

            $this->ticketService->issueForOrder($order, $eventLocked, $ticketTypeLocked, $attendees, $now);

            $order = $order->fresh(['tickets.ticketType', 'event']);
            $this->activityLog->log('order.created', $eventLocked->id, [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'quantity' => $quantity,
                'expires_at' => $order->expires_at?->toIso8601String(),
            ], auth()->id());

            return $order;
        });
    }

    /**
     * Si la commande est en attente et expirée, libère la capacité et marque la commande / billets.
     *
     * @return bool true si une expiration a été appliquée
     */
    public function expirePendingOrderIfStale(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            $order = Order::query()->lockForUpdate()->find($order->id);
            if (! $order) {
                return false;
            }
            if ($order->status !== 'pending_payment') {
                return false;
            }
            if ($order->expires_at === null || $order->expires_at->isFuture()) {
                return false;
            }

            $this->releaseHeldCapacityForExpiredPendingOrder($order);

            return true;
        });
    }

    public function expireAllDuePendingOrders(): int
    {
        $ids = Order::query()
            ->where('status', 'pending_payment')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->pluck('id');

        $count = 0;
        foreach ($ids as $id) {
            $order = Order::find($id);
            if ($order && $this->expirePendingOrderIfStale($order)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Paiement : expiration d’abord (transaction séparée), puis enregistrement du paiement.
     *
     * @throws ValidationException
     */
    public function processPayment(Order $order, string $paymentProvider, ?string $paymentReference = null): void
    {
        $this->expirePendingOrderIfStale($order);
        $order->refresh();

        if ($order->status === 'paid') {
            return;
        }

        if ($order->status !== 'pending_payment') {
            $message = match ($order->status) {
                'expired' => 'La session de paiement a expiré. Veuillez refaire une réservation.',
                'cancelled' => 'Cette commande a été annulée et ne peut plus être payée.',
                'failed' => 'Le paiement précédent a échoué. Veuillez recommencer la réservation.',
                default => 'Cette commande ne peut pas être payée.',
            };

            throw ValidationException::withMessages([
                'order' => $message,
            ]);
        }

        $this->orderService->recordSuccessfulPayment($order, $paymentProvider, $paymentReference);
    }

    private function releaseHeldCapacityForExpiredPendingOrder(Order $order): void
    {
        $event = Event::query()->lockForUpdate()->findOrFail($order->event_id);
        $tickets = $order->tickets()->get(['ticket_type_id']);
        $qty = $tickets->count();
        $event->release($qty);

        $tickets
            ->groupBy('ticket_type_id')
            ->each(function ($group, $ticketTypeId) use ($event): void {
                if (! $ticketTypeId) {
                    return;
                }
                $ticketType = TicketType::query()
                    ->lockForUpdate()
                    ->where('event_id', $event->id)
                    ->find($ticketTypeId);
                if (! $ticketType) {
                    return;
                }
                $ticketType->release($group->count());
            });

        $this->orderService->markAsExpired($order);
        $this->ticketService->cancelAllForOrder($order);

        $this->activityLog->log('reservation.expired', $order->event_id, [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'tickets_released' => $qty,
        ], null);
    }

    private function resolveActiveTicketType(Event $event, Carbon $now): ?TicketType
    {
        return TicketType::query()
            ->where('event_id', $event->id)
            ->where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->whereNotNull('sales_starts_at')
                        ->whereNotNull('sales_ends_at')
                        ->where('sales_starts_at', '<=', $now)
                        ->where('sales_ends_at', '>=', $now);
                })->orWhere(function ($q) {
                    $q->whereNull('sales_starts_at')
                        ->whereNull('sales_ends_at');
                });
            })
            ->orderBy('sort_order')
            ->orderBy('price_cents')
            ->first();
    }

    /**
     * @param  array<int, int>  $addonIds
     * @return \Illuminate\Support\Collection<int, Addon>
     */
    private function resolveAddons(Event $event, array $addonIds): \Illuminate\Support\Collection
    {
        if ($addonIds === []) {
            return collect();
        }

        $addons = Addon::query()
            ->where('event_id', $event->id)
            ->whereIn('id', $addonIds)
            ->where('is_active', true)
            ->get();

        if ($addons->count() !== count(array_unique($addonIds))) {
            throw ValidationException::withMessages([
                'addons' => 'Une ou plusieurs options ne sont pas valides pour cet événement.',
            ]);
        }

        return $addons;
    }
}
