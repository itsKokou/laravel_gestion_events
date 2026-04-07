<?php

namespace App\Http\Controllers;

use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Services\InvoicePdfService;
use App\Services\ReservationService;
use App\Services\TicketPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function checkout(Order $order)
    {
        $order->refresh();

        if ($order->status === 'pending_payment') {
            // Si la commande est expirée, on la fait basculer avant d'afficher le checkout.
            app(ReservationService::class)->expirePendingOrderIfStale($order);
            $order->refresh();
        }

        if ($order->status === 'paid') {
            return redirect()->route('public.orders.show', $order)->with('status', 'Réservation déjà payée.');
        }

        if ($order->status !== 'pending_payment') {
            $message = match ($order->status) {
                'expired' => 'La session de paiement a expiré. Veuillez refaire une réservation.',
                'cancelled' => 'Cette commande a été annulée et ne peut plus être payée.',
                'failed' => 'Le paiement précédent a échoué. Veuillez recommencer la réservation.',
                default => 'Cette commande ne peut pas être payée.',
            };

            return redirect()->route('public.orders.show', $order)->withErrors(['order' => $message]);
        }

        $order->loadMissing(['event', 'tickets.ticketType']);

        return view('public.orders.checkout', compact('order'));
    }

    /**
     * Paiement simulé uniquement.
     */
    public function pay(Request $request, Order $order, InvoicePdfService $invoicePdf, TicketPdfService $ticketPdf, ReservationService $reservations)
    {
        $order->refresh();

        if ($order->status === 'paid') {
            return redirect()->route('public.orders.show', $order)->with('status', 'Réservation déjà payée.');
        }

        if ($order->status !== 'pending_payment') {
            $message = match ($order->status) {
                'expired' => 'La session de paiement a expiré. Veuillez refaire une réservation.',
                'cancelled' => 'Cette commande a été annulée et ne peut plus être payée.',
                'failed' => 'Le paiement précédent a échoué. Veuillez recommencer la réservation.',
                default => 'Cette commande ne peut pas être payée.',
            };

            return redirect()->route('public.orders.show', $order)->withErrors(['order' => $message]);
        }

        $validated = $request->validate([
            'payment_method' => ['nullable', 'in:card,orange_money,wave,paypal'],
            'billing_address' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['nullable', 'string', 'max:100'],
            'billing_postal_code' => ['nullable', 'string', 'max:30'],
            'billing_country' => ['nullable', 'string', 'max:100'],
        ]);

        $metadata = (array) ($order->metadata ?? []);
        $existingBilling = (array) ($metadata['billing'] ?? []);
        $metadata['billing'] = [
            'address' => $validated['billing_address'] ?? $existingBilling['address'] ?? '',
            'city' => $validated['billing_city'] ?? $existingBilling['city'] ?? '',
            'postal_code' => $validated['billing_postal_code'] ?? $existingBilling['postal_code'] ?? null,
            'country' => $validated['billing_country'] ?? $existingBilling['country'] ?? '',
        ];
        $order->metadata = $metadata;
        $order->save();

        try {
            $reservations->processPayment($order, $validated['payment_method'] ?? 'card', $order->payment_reference ?: null);
        } catch (ValidationException $e) {
            $reservations->expirePendingOrderIfStale($order->fresh());

            return redirect()
                ->route('public.orders.checkout', $order)
                ->withErrors($e->errors());
        }

        $order->refresh();

        $order->loadMissing(['event', 'tickets.ticketType']);
        $pdfBytes = $invoicePdf->render($order);
        $ticketPdfs = $ticketPdf->renderForOrder($order);

        try {
            Mail::to($order->customer_email)->send(new OrderPaidMail($order, $pdfBytes, $ticketPdfs));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email confirmation réservation', [
                'order_id' => $order->id,
                'email' => $order->customer_email,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('public.orders.show', $order)->with('status', 'Paiement effectué : Votre réservation est confirmée.');
    }
}
