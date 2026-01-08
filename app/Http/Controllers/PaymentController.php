<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaytechService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request, Order $order, PaytechService $paytech)
    {
        if ($order->status === 'paid') {
            return redirect()->route('public.orders.show', $order)->with('status', 'Commande déjà payée.');
        }

        $mode = (string) config('services.paytech.mode', 'simulate');

        if ($mode === 'simulate') {
            $order->status = 'paid';
            $order->paid_at = now();
            $order->payment_provider = 'simulate';
            $order->payment_reference = $order->payment_reference ?: ('sim_' . $order->order_number);
            $order->save();

            return redirect()->route('public.orders.show', $order)->with('status', 'Paiement simulé : commande confirmée.');
        }

        $payment = $paytech->createPayment($order);

        $order->status = 'pending_payment';
        $order->payment_provider = 'paytech';
        $order->payment_reference = $payment['reference'];
        $order->save();

        return redirect()->away($payment['redirect_url']);
    }

    /**
     * Endpoint de callback PayTech (server-to-server).
     * À adapter quand on a le format exact (signature, champs, etc.).
     */
    public function callback(Request $request)
    {
        $data = $request->all();

        $orderNumber = $data['order'] ?? $data['order_number'] ?? null;
        $status = $data['status'] ?? null;
        $reference = $data['reference'] ?? $data['payment_reference'] ?? null;

        if (!is_string($orderNumber) || !is_string($status)) {
            return response()->json(['ok' => false], 422);
        }

        $order = Order::query()->where('order_number', $orderNumber)->first();
        if (!$order) {
            return response()->json(['ok' => false], 404);
        }

        if (is_string($reference) && $reference !== '') {
            $order->payment_reference = $reference;
        }

        if (in_array($status, ['success', 'paid', 'completed'], true)) {
            $order->status = 'paid';
            $order->paid_at = $order->paid_at ?: now();
        } elseif (in_array($status, ['failed', 'cancelled', 'canceled', 'error'], true)) {
            $order->status = 'failed';
        }

        $order->payment_provider = $order->payment_provider ?: 'paytech';
        $order->save();

        return response()->json(['ok' => true]);
    }

    /**
     * Page de retour après paiement (côté navigateur).
     * En mode simulate, on peut marquer "paid" via l'URL de retour.
     */
    public function returned(Request $request)
    {
        $orderNumber = (string) $request->query('order', '');
        $status = (string) $request->query('status', '');

        $order = Order::query()->where('order_number', $orderNumber)->firstOrFail();

        if (config('services.paytech.mode') === 'simulate' && in_array($status, ['success', 'paid'], true)) {
            if ($order->status !== 'paid') {
                $order->status = 'paid';
                $order->paid_at = now();
                $order->payment_provider = $order->payment_provider ?: 'simulate';
                $order->save();
            }
        }

        return redirect()->route('public.orders.show', $order);
    }
}
