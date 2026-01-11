<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPaidMail;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Paiement simulé uniquement.
     */
    public function pay(Request $request, Order $order, InvoicePdfService $invoicePdf)
    {
        if ($order->status === 'paid') {
            return redirect()->route('public.orders.show', $order)->with('status', 'Commande déjà payée.');
        }

        $order->status = 'paid';
        $order->paid_at = now();
        $order->payment_provider = 'simulate';
        $order->payment_reference = $order->payment_reference ?: ('sim_' . $order->order_number);
        $order->save();

        // Envoi email SMTP (si configuré) + facture PDF en pièce jointe.
        $order->loadMissing(['event', 'tickets.ticketType']);
        $pdfBytes = $invoicePdf->render($order);
        Mail::to($order->customer_email)->send(new OrderPaidMail($order, $pdfBytes));

        return redirect()->route('public.orders.show', $order)->with('status', 'Paiement simulé : commande confirmée.');
    }
}
