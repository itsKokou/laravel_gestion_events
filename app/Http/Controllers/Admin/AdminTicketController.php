<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'q' => ['nullable', 'string', 'max:255'],
            'billet' => ['nullable', 'string', 'in:all,actifs,annules'],
        ]);

        $tickets = $this->filteredTicketsQuery($request)
            ->with(['order.event', 'ticketType', 'event'])
            ->orderByDesc('tickets.id')
            ->paginate(40)
            ->withQueryString();

        $events = Event::query()
            ->orderByDesc('starts_at')
            ->limit(250)
            ->get(['id', 'name', 'starts_at']);

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'events' => $events,
            'filters' => [
                'event_id' => $request->input('event_id'),
                'q' => $request->input('q'),
                'billet' => $request->input('billet', 'all'),
            ],
        ]);
    }

    private function filteredTicketsQuery(Request $request): Builder
    {
        $q = Ticket::query();

        if ($request->filled('event_id')) {
            $q->where('event_id', (int) $request->input('event_id'));
        }

        if ($request->filled('q')) {
            $raw = trim((string) $request->input('q'));
            $term = '%'.addcslashes($raw, '%_\\').'%';
            $q->where(function (Builder $w) use ($term): void {
                $w->where('attendee_first_name', 'like', $term)
                    ->orWhere('attendee_last_name', 'like', $term)
                    ->orWhere('attendee_email', 'like', $term)
                    ->orWhereHas('order', function (Builder $oq) use ($term): void {
                        $oq->where('order_number', 'like', $term)
                            ->orWhere('customer_email', 'like', $term);
                    });
            });
        }

        $billet = $request->input('billet', 'all');
        if ($billet === 'actifs') {
            $q->whereNull('cancelled_at');
        } elseif ($billet === 'annules') {
            $q->whereNotNull('cancelled_at');
        }

        return $q;
    }
}
