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
            return redirect()->route('public.orders.show', $order)->with('status', 'Réservation déjà payée.');
        }

        $order->status = 'paid';
        $order->paid_at = now();
        $order->payment_provider = 'simulate';
        $order->payment_reference = $order->payment_reference ?: ('sim_' . $order->order_number);
        $order->save();

        // Envoi email SMTP (si configuré) + facture PDF en pièce jointe.
        $order->loadMissing(['event', 'tickets.ticketType']);
        $pdfBytes = $invoicePdf->render($order);
        
        try {
            Mail::to($order->customer_email)->send(new OrderPaidMail($order, $pdfBytes));
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas le paiement
            \Log::error('Erreur envoi email confirmation réservation', [
                'order_id' => $order->id,
                'email' => $order->customer_email,
                'error' => $e->getMessage(),
            ]);
            
            // Optionnel : vous pouvez ajouter une notification à l'admin ici
        }

        return redirect()->route('public.orders.show', $order)->with('status', 'Paiement simulé : réservation confirmée.');
    }
}
