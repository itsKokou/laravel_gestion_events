@extends('layouts.admin')

@section('title', 'Réservation ' . $order->order_number . ' · Admin')

@php
    $statusColors = [
        'pending_payment' => ['bg' => 'rgba(251, 191, 36, 0.1)', 'text' => '#d97706', 'label' => 'En attente de paiement'],
        'paid' => ['bg' => 'rgba(34, 197, 94, 0.1)', 'text' => '#16a34a', 'label' => 'Payée'],
        'cancelled' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Annulée'],
        'failed' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Échouée'],
        'expired' => ['bg' => 'rgba(100, 116, 139, 0.12)', 'text' => '#64748b', 'label' => 'Expirée'],
    ];
    $status = $statusColors[$order->status] ?? $statusColors['pending_payment'];
@endphp

@section('content')
    @php(auth()->user()?->loadMissing('roles'))
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.orders.index', request()->query()) }}" class="muted" style="font-size: 14px; font-weight: 600; text-decoration: none;">
            ← Retour aux réservations
        </a>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 28px;">
        <div>
            <div class="muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                Commande</div>
            <h1 style="font-size: 32px; font-weight: 900; margin-bottom: 12px; font-family: ui-monospace, monospace;">
                {{ $order->order_number }}</h1>
            <span
                style="display: inline-block; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; letter-spacing: 0.5px;">
                {{ $status['label'] }}
            </span>
            @if ($order->paid_at)
                <p class="muted" style="margin-top: 12px; font-size: 14px;">Payée le {{ $order->paid_at->format('d/m/Y à H:i') }}</p>
            @endif
            @if ($order->status === 'pending_payment' && $order->expires_at)
                <p class="muted" style="margin-top: 8px; font-size: 14px;">Expiration : {{ $order->expires_at->format('d/m/Y à H:i') }}</p>
            @endif
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-end;">
            <a href="{{ route('public.orders.show', $order) }}" target="_blank" rel="noopener noreferrer" class="btn secondary" style="padding: 12px 18px; font-weight: 700;">
                Page client ↗
            </a>
            @if ($order->status === 'paid')
                <a href="{{ route('public.orders.invoice', $order) }}" target="_blank" rel="noopener noreferrer" class="btn secondary" style="padding: 12px 18px; font-weight: 700;">
                    Facture PDF
                </a>
            @endif
            @if ($order->event)
                <a href="{{ route('admin.events.edit', $order->event) }}" class="btn secondary" style="padding: 12px 18px; font-weight: 700;">
                    Éditer la soirée
                </a>
                @if (auth()->user()?->hasAnyRole(['admin', 'controller']))
                    <a href="{{ route('scanner.event', $order->event) }}" class="btn secondary" style="padding: 12px 18px; font-weight: 700;">
                        Scanner
                    </a>
                @endif
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 24px;">
            <h2 style="font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; color: var(--we-muted);">
                Client</h2>
            <p style="font-weight: 700; font-size: 16px; margin-bottom: 8px;">{{ $order->customer_email }}</p>
            @if ($order->customer_phone)
                <p class="muted" style="font-size: 15px;">{{ $order->customer_phone }}</p>
            @endif
        </div>
        <div class="card" style="padding: 24px;">
            <h2 style="font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; color: var(--we-muted);">
                Montants</h2>
            <p style="font-size: 28px; font-weight: 900;">{{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}</p>
            @if ($order->subtotal_cents > 0 || $order->addons_total_cents > 0)
                <p class="muted" style="font-size: 13px; margin-top: 8px;">
                    Billets : {{ number_format($order->subtotal_cents, 0, ',', ' ') }} {{ $order->currency }}
                    @if ($order->addons_total_cents > 0)
                        · Options : {{ number_format($order->addons_total_cents, 0, ',', ' ') }} {{ $order->currency }}
                    @endif
                </p>
            @endif
        </div>
        @if ($order->event)
            <div class="card" style="padding: 24px;">
                <h2 style="font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; color: var(--we-muted);">
                    Soirée</h2>
                <p style="font-weight: 800; font-size: 17px; margin-bottom: 6px;">{{ $order->event->name }}</p>
                <p class="muted" style="font-size: 14px;">
                    {{ optional($order->event->starts_at)->format('d/m/Y H:i') }}
                    — {{ optional($order->event->ends_at)->format('H:i') }}
                </p>
                <p class="muted" style="font-size: 14px; margin-top: 8px;">{{ $order->event->venue_name }}</p>
            </div>
        @endif
    </div>

    <div class="card" style="padding: 24px;">
        <h2 style="font-size: 18px; font-weight: 900; margin-bottom: 20px;">Billets ({{ $order->tickets->count() }})</h2>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse ($order->tickets as $ticket)
                <div
                    style="border: 1px solid var(--we-border); border-radius: 12px; padding: 18px; background: #fafafa;">
                    <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                        <div>
                            <span
                                style="display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 800; background: rgba(234, 88, 12, 0.12); color: var(--we-primary); text-transform: uppercase;">
                                {{ $ticket->ticketType?->name ?? 'Billet' }}</span>
                            <p style="font-weight: 800; font-size: 16px; margin-top: 10px;">
                                {{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}</p>
                            <p class="muted" style="font-size: 14px; margin-top: 4px;">{{ $ticket->attendee_email }}</p>
                            @if ($ticket->attendee_phone)
                                <p class="muted" style="font-size: 14px;">{{ $ticket->attendee_phone }}</p>
                            @endif
                        </div>
                        <div style="text-align: right;">
                            @if ($ticket->ticketType)
                                <p style="font-weight: 800; font-size: 16px;">
                                    {{ number_format($ticket->ticketType->price_cents, 0, ',', ' ') }}
                                    {{ $ticket->ticketType->currency }}</p>
                            @endif
                            @if ($order->status === 'paid')
                                <a href="{{ route('tickets.qr', $ticket) }}" target="_blank" rel="noopener noreferrer" class="muted" style="font-size: 13px; font-weight: 600; display: inline-block; margin-top: 8px;">
                                    QR SVG ↗
                                </a>
                            @endif
                        </div>
                    </div>
                    @if ($order->status === 'paid')
                        <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--we-border); font-size: 13px;">
                            @if ($ticket->checked_in_at)
                                <span style="font-weight: 700; color: #16a34a;">Scanné le {{ $ticket->checked_in_at->format('d/m/Y H:i:s') }}</span>
                            @else
                                <span style="font-weight: 600; color: var(--we-muted);">Pas encore scanné</span>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <p class="muted">Aucun billet sur cette commande.</p>
            @endforelse
        </div>
    </div>
@endsection
