<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Transaction commerciale : création de commande, numéro, passage à « payé », expiration statut.
 * La capacité événement (`Event::reserve` / `release`) et les billets (`TicketService`) sont hors de ce service.
 */
class OrderService
{
    public function __construct(
        private ActivityLogService $activityLog,
    ) {}

    public function generateOrderNumber(): string
    {
        for ($i = 0; $i < 5; $i++) {
            $candidate = 'WE-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
            if (! Order::where('order_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        return 'WE-'.now()->format('YmdHis').'-'.strtoupper(Str::random(8));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createPendingOrder(array $attributes): Order
    {
        return Order::create($attributes);
    }

    /**
     * Enregistre le paiement réussi (verrouillage ligne commande).
     *
     * @throws ValidationException
     */
    public function recordSuccessfulPayment(Order $order, string $paymentProvider, ?string $paymentReference = null): void
    {
        DB::transaction(function () use ($order, $paymentProvider, $paymentReference) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);

            if ($order->status !== 'pending_payment') {
                throw ValidationException::withMessages([
                    'order' => 'Cette commande ne peut pas être payée.',
                ]);
            }

            if ($order->expires_at && $order->expires_at->isPast()) {
                throw ValidationException::withMessages([
                    'order' => 'La session de paiement a expiré. Veuillez refaire une réservation.',
                ]);
            }

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_provider' => $paymentProvider,
                'payment_reference' => $paymentReference ?: ('sim_'.$order->order_number),
            ]);

            $order->refresh();
            $this->activityLog->log('payment.success', $order->event_id, [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_cents' => $order->total_cents,
                'payment_provider' => $paymentProvider,
            ], auth()->id());
        });
    }

    public function markAsExpired(Order $order): void
    {
        $order->update([
            'status' => 'expired',
            'cancelled_at' => now(),
        ]);
    }
}
