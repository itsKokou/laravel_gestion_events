<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $events = Event::query()
            ->where('status', 'published')
            ->where('starts_at', '>=', now()->subHours(6))
            ->with(['ticketTypes' => fn ($q) => $q->where('is_active', true)])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('venue_name', 'like', "%{$q}%")
                        ->orWhere('theme', 'like', "%{$q}%");
                });
            })
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('public.events.index', [
            'events' => $events,
            'q' => $q,
        ]);
    }

    public function show(Event $event)
    {
        abort_unless($event->status === 'published', 404);

        $now = now();

        // Charger tous les tarifs actifs
        $event->load([
            'ticketTypes' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('price_cents'),
            'addons' => fn ($q) => $q->where('is_active', true)->orderBy('price_cents'),
        ]);

        // Déterminer le tarif actif selon la période actuelle
        $activeTicketType = $event->ticketTypes->first(function ($type) use ($now) {
            // Si les périodes sont nulles, le tarif est toujours disponible
            if (!$type->sales_starts_at && !$type->sales_ends_at) {
                return true;
            }
            // Sinon, vérifier si on est dans la période
            return $type->sales_starts_at && $type->sales_ends_at
                && $type->sales_starts_at <= $now
                && $type->sales_ends_at >= $now;
        });

        return view('public.events.show', [
            'event' => $event,
            'activeTicketType' => $activeTicketType,
        ]);
    }
}
