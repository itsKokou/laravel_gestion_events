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
            </div>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        @foreach ($order->tickets as $ticket)
            <div class="card">
                <div style="font-weight: 850;">Billet</div>
                <div class="muted" style="margin-top: 6px;">
                    {{ $ticket->ticketType->name }} · {{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}
                </div>
                <div class="muted" style="margin-top: 6px;">{{ $ticket->attendee_email }}</div>

                <div style="margin-top: 10px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <div class="card" style="padding: 10px 12px; border-radius: 12px; flex:1;">
                        <div class="muted" style="font-size: 12px;">QR Token (dev)</div>
                        <div style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 12px; word-break: break-all;">
                            {{ $ticket->qr_token }}
                        </div>
                    </div>
                </div>

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

