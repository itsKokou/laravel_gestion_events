<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Paiement simulé uniquement.
     */
    public function pay(Request $request, Order $order)
    {
        if ($order->status === 'paid') {
            return redirect()->route('public.orders.show', $order)->with('status', 'Commande déjà payée.');
        }

        $order->status = 'paid';
        $order->paid_at = now();
        $order->payment_provider = 'simulate';
        $order->payment_reference = $order->payment_reference ?: ('sim_' . $order->order_number);
        $order->save();

        return redirect()->route('public.orders.show', $order)->with('status', 'Paiement simulé : commande confirmée.');
    }
}
