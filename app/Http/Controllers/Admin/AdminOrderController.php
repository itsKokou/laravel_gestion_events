<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderCancelledMail;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminOrderController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog,
    ) {}

    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'string', 'in:all,pending_payment,paid,cancelled,failed,expired'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $orders = $this->filteredOrdersQuery($request)
            ->with('event')
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        $kpis = $this->computeKpis($request);

        $events = Event::query()
            ->orderByDesc('starts_at')
            ->limit(400)
            ->get(['id', 'name', 'starts_at']);

        return view('admin.orders.index', [
            'orders' => $orders,
            'kpis' => $kpis,
            'events' => $events,
            'filters' => [
                'status' => $request->input('status', 'all'),
                'event_id' => $request->input('event_id'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'q' => $request->input('q'),
            ],
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['event', 'tickets.ticketType']);

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $request->validate([
            'status' => ['nullable', 'string', 'in:all,pending_payment,paid,cancelled,failed,expired'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $filename = 'reservations_'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Numéro',
                'Soirée',
                'Email',
                'Téléphone',
                'Statut',
                'Total',
                'Devise',
                'Créée le',
                'Payée le',
                'Expire le',
            ], ';');

            $this->filteredOrdersQuery($request)
                ->with('event')
                ->orderByDesc('created_at')
                ->chunk(500, function ($chunk) use ($handle): void {
                    foreach ($chunk as $order) {
                        /** @var Order $order */
                        fputcsv($handle, [
                            $order->order_number,
                            $order->event?->name ?? '',
                            $order->customer_email,
                            $order->customer_phone ?? '',
                            $order->status,
                            (string) $order->total_cents,
                            $order->currency,
                            $order->created_at?->format('Y-m-d H:i:s') ?? '',
                            $order->paid_at?->format('Y-m-d H:i:s') ?? '',
                            $order->expires_at?->format('Y-m-d H:i:s') ?? '',
                        ], ';');
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function cancelOrder(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('status', 'Cette commande est déjà annulée.');
        }
        if (! in_array($order->status, ['pending_payment', 'paid', 'failed', 'expired'], true)) {
            abort(403);
        }

        $didCancel = false;

        DB::transaction(function () use ($order, &$didCancel): void {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($order->status === 'cancelled') {
                return;
            }

            $event = Event::query()->lockForUpdate()->findOrFail($order->event_id);

            foreach ($order->tickets()->whereNull('cancelled_at')->get() as $ticket) {
                $this->releaseCapacityForTicket($event, $ticket);
                $ticket->update(['cancelled_at' => now()]);
            }

            $order->refresh();
            $this->syncOrderTicketLineTotals($order);

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            $this->activityLog->log('admin.order.cancelled', $order->event_id, [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ], auth()->id());

            $didCancel = true;
        });

        if (! $didCancel) {
            return back()->with('status', 'Cette commande était déjà annulée.');
        }

        $order->refresh()->load('event');
        Mail::to($order->customer_email)->send(new OrderCancelledMail($order));

        return back()->with('status', 'La réservation a été annulée.');
    }

    public function cancelTicket(Order $order, Ticket $ticket)
    {
        abort_if((int) $ticket->order_id !== (int) $order->id, 404);
        abort_if((int) $ticket->event_id !== (int) $order->event_id, 404);

        if ($ticket->cancelled_at) {
            return back()->with('status', 'Ce billet est déjà annulé.');
        }
        if ($order->status === 'cancelled') {
            return back()->with('status', 'La commande est déjà annulée.');
        }
        if (! in_array($order->status, ['pending_payment', 'paid', 'failed', 'expired'], true)) {
            abort(403);
        }

        $orderFullyCancelled = false;

        DB::transaction(function () use ($order, $ticket, &$orderFullyCancelled): void {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $ticket = Ticket::query()->lockForUpdate()->findOrFail($ticket->id);

            if ($ticket->cancelled_at !== null || $order->status === 'cancelled') {
                return;
            }

            $event = Event::query()->lockForUpdate()->findOrFail($order->event_id);
            $this->releaseCapacityForTicket($event, $ticket);
            $ticket->update(['cancelled_at' => now()]);

            $order->refresh();
            $this->syncOrderTicketLineTotals($order);

            $this->activityLog->log('admin.ticket.cancelled', $order->event_id, [
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
            ], auth()->id());

            $remaining = Ticket::query()
                ->where('order_id', $order->id)
                ->whereNull('cancelled_at')
                ->count();

            if ($remaining === 0) {
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
                $this->activityLog->log('admin.order.cancelled', $order->event_id, [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'reason' => 'last_ticket',
                ], auth()->id());
                $orderFullyCancelled = true;
            }
        });

        $order->refresh()->load('event');

        if ($orderFullyCancelled) {
            Mail::to($order->customer_email)->send(new OrderCancelledMail($order));
        }

        return back()->with('status', $orderFullyCancelled
            ? 'Le billet a été annulé ; la commande ne contient plus de billet actif et a été clôturée.'
            : 'Le billet a été annulé.');
    }

    private function releaseCapacityForTicket(Event $event, Ticket $ticket): void
    {
        $event->release(1);
        if ($ticket->ticket_type_id) {
            $ticketType = TicketType::query()
                ->lockForUpdate()
                ->where('event_id', $event->id)
                ->find($ticket->ticket_type_id);
            if ($ticketType) {
                $ticketType->release(1);
            }
        }
    }

    /**
     * Recalcule sous-total et total à partir des billets encore actifs (prix du tarif) + options.
     */
    private function syncOrderTicketLineTotals(Order $order): void
    {
        $order->refresh();

        $subtotal = (int) Ticket::query()
            ->where('tickets.order_id', $order->id)
            ->whereNull('tickets.cancelled_at')
            ->join('ticket_types', 'tickets.ticket_type_id', '=', 'ticket_types.id')
            ->sum('ticket_types.price_cents');

        $addonsTotal = (int) $order->addons_total_cents;

        $order->update([
            'subtotal_cents' => $subtotal,
            'total_cents' => $subtotal + $addonsTotal,
        ]);
    }

    private function computeKpis(Request $request): array
    {
        $base = $this->filteredOrdersQuery($request);

        $pending = (clone $base)->where('status', 'pending_payment')->count();
        $paidCount = (clone $base)->where('status', 'paid')->count();

        $revenueByCurrency = (clone $base)
            ->where('status', 'paid')
            ->selectRaw('currency, SUM(total_cents) as total_cents')
            ->groupBy('currency')
            ->get()
            ->map(fn ($row) => [
                'currency' => $row->currency,
                'total_cents' => (int) $row->total_cents,
            ])
            ->all();

        $ticketsSold = Ticket::query()
            ->whereNull('cancelled_at')
            ->whereHas('order', function (Builder $q) use ($request): void {
                $this->applyOrderFilters($q, $request);
                $q->where('status', 'paid');
            })
            ->count();

        return [
            'pending' => $pending,
            'paid_count' => $paidCount,
            'revenue_by_currency' => $revenueByCurrency,
            'tickets_sold' => $ticketsSold,
        ];
    }

    private function filteredOrdersQuery(Request $request): Builder
    {
        $q = Order::query();
        $this->applyOrderFilters($q, $request);

        return $q;
    }

    private function applyOrderFilters(Builder $query, Request $request): void
    {
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', (int) $request->input('event_id'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        if ($request->filled('q')) {
            $raw = trim((string) $request->input('q'));
            $term = '%'.addcslashes($raw, '%_\\').'%';
            $query->where(function (Builder $w) use ($term): void {
                $w->where('order_number', 'like', $term)
                    ->orWhere('customer_email', 'like', $term)
                    ->orWhere('customer_phone', 'like', $term);
            });
        }
    }
}
