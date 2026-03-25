<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Event;
use App\Models\Order;
use App\Services\ReservationService;
use Illuminate\Validation\ValidationException;

class PublicReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService,
    ) {}

    public function create(Event $event)
    {
        $ctx = $this->reservationService->bookingContextForCreateForm($event);

        return view('public.reservations.create', [
            'event' => $ctx['event'],
            'activeTicketType' => $ctx['activeTicketType'],
        ]);
    }

    public function store(StoreReservationRequest $request, Event $event)
    {
        abort_unless($event->status === 'published', 404);

        try {
            $order = $this->reservationService->createReservation($event, $request->validated());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('public.orders.show', $order)->with('status', 'Réservation enregistrée. Paiement requis pour activer les billets.');
    }

    public function show(Order $order)
    {
        $order->load(['event', 'tickets.ticketType']);

        return view('public.orders.show', [
            'order' => $order,
        ]);
    }
}
