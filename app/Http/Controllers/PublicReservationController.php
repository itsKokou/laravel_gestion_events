<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Addon;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicReservationController extends Controller
{
    public function create(Event $event)
    {
        abort_unless($event->status === 'published', 404);

        $event->load([
            'ticketTypes' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('price_cents'),
            'addons' => fn ($q) => $q->where('is_active', true)->orderBy('price_cents'),
        ]);

        return view('public.reservations.create', [
            'event' => $event,
        ]);
    }

    public function store(StoreReservationRequest $request, Event $event)
    {
        abort_unless($event->status === 'published', 404);

        $data = $request->validated();

        $ticketType = TicketType::query()
            ->where('id', $data['ticket_type_id'])
            ->where('event_id', $event->id)
            ->where('is_active', true)
            ->firstOrFail();

        $quantity = (int) $data['quantity'];
        $attendees = $data['attendees'];
        if (count($attendees) !== $quantity) {
            return back()->withErrors(['attendees' => "Le nombre de participants doit correspondre à la quantité de billets."])->withInput();
        }

        $addons = Addon::query()
            ->where('event_id', $event->id)
            ->whereIn('id', $data['addons'] ?? [])
            ->where('is_active', true)
            ->get();

        $now = now();

        // Contraintes business: capacité + quotas + âge minimum
        $reservedCount = Ticket::query()
            ->where('event_id', $event->id)
            ->whereNull('cancelled_at')
            ->count();
        if ($reservedCount + $quantity > $event->capacity) {
            return back()->withErrors(['quantity' => "Capacité dépassée : il reste " . max(0, $event->capacity - $reservedCount) . " place(s)."])->withInput();
        }

        if ($ticketType->quantity_limit !== null) {
            $typeCount = Ticket::query()
                ->where('event_id', $event->id)
                ->where('ticket_type_id', $ticketType->id)
                ->whereNull('cancelled_at')
                ->count();
            if ($typeCount + $quantity > (int) $ticketType->quantity_limit) {
                return back()->withErrors(['quantity' => "Quota dépassé pour ce tarif."])->withInput();
            }
        }

        foreach ($attendees as $idx => $a) {
            $birthdate = \Carbon\Carbon::createFromFormat('Y-m-d', $a['birthdate'])->startOfDay();
            $age = $birthdate->diffInYears($event->starts_at, false);
            if ($age < $event->min_age) {
                return back()->withErrors(["attendees.$idx.birthdate" => "Âge minimum non respecté ({$event->min_age}+)."])->withInput();
            }
        }

        $subtotal = $ticketType->price_cents * $quantity;
        $addonsTotal = (int) $addons->sum('price_cents');
        $total = $subtotal + $addonsTotal;

        $order = DB::transaction(function () use ($data, $event, $ticketType, $quantity, $attendees, $subtotal, $addonsTotal, $total, $addons, $now) {
            $orderNumber = $this->generateOrderNumber();

            $order = Order::create([
                'event_id' => $event->id,
                'order_number' => $orderNumber,
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'status' => 'paid', // Paiement PayTech à brancher : ici on simule une commande payée.
                'currency' => $ticketType->currency,
                'subtotal_cents' => $subtotal,
                'addons_total_cents' => $addonsTotal,
                'total_cents' => $total,
                'agreed_terms_at' => now(),
                'paid_at' => now(),
                'metadata' => [
                    'addons' => $addons->map(fn ($a) => ['id' => $a->id, 'code' => $a->code, 'name' => $a->name, 'price_cents' => $a->price_cents])->values()->all(),
                ],
            ]);

            foreach ($attendees as $a) {
                Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'ticket_type_id' => $ticketType->id,
                    'attendee_first_name' => $a['first_name'],
                    'attendee_last_name' => $a['last_name'],
                    'attendee_email' => $a['email'],
                    'attendee_phone' => $a['phone'] ?? null,
                    'attendee_birthdate' => $a['birthdate'],
                    'qr_token' => Str::random(48),
                    'issued_at' => $now,
                ]);
            }

            return $order->fresh(['tickets.ticketType', 'event']);
        });

        return redirect()->route('public.orders.show', $order)->with('status', "Réservation confirmée (paiement simulé).");
    }

    public function show(Order $order)
    {
        $order->load(['event', 'tickets.ticketType']);

        return view('public.orders.show', [
            'order' => $order,
        ]);
    }

    private function generateOrderNumber(): string
    {
        for ($i = 0; $i < 5; $i++) {
            $candidate = 'WE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            if (! Order::where('order_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        return 'WE-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(8));
    }
}
