@extends('layouts.app')

@section('title', 'Commande ' . $order->order_number . " · Win's Events")

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 850;">Commande {{ $order->order_number }}</div>
                <div class="muted">
                    {{ $order->event->name }} · Statut : <strong>{{ $order->status }}</strong>
                </div>
                <div class="muted" style="margin-top: 6px;">
                    Total : <strong>{{ number_format($order->total_cents / 100, 2, ',', ' ') }} {{ $order->currency }}</strong>
                </div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="btn secondary" href="{{ route('public.events.show', $order->event) }}">Retour soirée</a>
                <a class="btn secondary" href="{{ route('scanner.event', $order->event) }}">Scanner</a>
                @if ($order->status !== 'paid')
                    <form method="POST" action="{{ route('public.orders.pay', $order) }}">
                        @csrf
                        <button class="btn" type="submit">Payer</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if ($order->status !== 'paid')
        <div class="card" style="margin-bottom: 14px;">
            <div style="font-weight: 850;">Paiement requis</div>
            <div class="muted" style="margin-top: 6px;">
                Les billets ne sont valides au scan qu’après paiement. (Actuellement : <strong>mode {{ config('services.paytech.mode') }}</strong>)
            </div>
        </div>
    @endif

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        @foreach ($order->tickets as $ticket)
            <div class="card">
                <div style="font-weight: 850;">Billet</div>
                <div class="muted" style="margin-top: 6px;">
                    {{ $ticket->ticketType->name }} · {{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}
                </div>
                <div class="muted" style="margin-top: 6px;">{{ $ticket->attendee_email }}</div>

                @if ($order->status === 'paid')
                    <div style="margin-top: 10px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <div class="card" style="padding: 10px 12px; border-radius: 12px; display:flex; align-items:center; justify-content:center;">
                            <img src="{{ route('tickets.qr', $ticket) }}" alt="QR Code" style="width: 180px; height: 180px; background: #fff; border-radius: 12px; padding: 10px;" />
                        </div>
                        <div class="card" style="padding: 10px 12px; border-radius: 12px; flex:1;">
                            <div class="muted" style="font-size: 12px;">Token (support)</div>
                            <div style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 12px; word-break: break-all;">
                                {{ $ticket->qr_token }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card" style="margin-top: 10px; padding: 10px 12px; border-radius: 12px;">
                        <div class="muted">QR disponible après paiement.</div>
                    </div>
                @endif

                <div style="margin-top: 10px;">
                    <div class="muted">
                        Check-in :
                        @if ($ticket->checked_in_at)
                            <strong>OK</strong> ({{ $ticket->checked_in_at->format('d/m/Y H:i:s') }})
                        @else
                            <strong>Non scanné</strong>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

