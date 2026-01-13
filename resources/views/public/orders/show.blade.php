@extends('layouts.app')

@section('title', 'Réservation ' . $order->order_number . " · Win's Events")

@section('content')
    @php
        $statusConfig = [
            'pending_payment' => ['bg' => 'rgba(251, 191, 36, 0.1)', 'text' => '#d97706', 'label' => 'En attente de paiement', 'icon' => '⏳'],
            'paid' => ['bg' => 'rgba(34, 197, 94, 0.1)', 'text' => '#16a34a', 'label' => 'Payée', 'icon' => '✅'],
            'cancelled' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Annulée', 'icon' => '❌'],
            'failed' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Échouée', 'icon' => '⚠️'],
        ];
        $status = $statusConfig[$order->status] ?? $statusConfig['pending_payment'];
    @endphp

    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Réservation</div>
                <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 12px; letter-spacing: -0.5px;">
                    {{ $order->order_number }}
                </h1>
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; letter-spacing: 0.5px;">
                        <span>{{ $status['icon'] }}</span>
                        {{ $status['label'] }}
                    </span>
                    @if($order->paid_at)
                        <span class="muted" style="font-size: 14px;">
                            Payée le {{ $order->paid_at->format('d/m/Y à H:i') }}
                        </span>
                    @endif
                </div>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a class="btn secondary" href="{{ route('public.events.show', $order->event) }}" style="padding: 12px 20px;">
                    ← Retour à l'événement
                </a>
                @auth
                    @php(auth()->user()?->loadMissing('roles'))
                    @if (auth()->user()?->hasAnyRole(['admin', 'controller']))
                        <a class="btn secondary" href="{{ route('scanner.event', $order->event) }}" style="padding: 12px 20px;">
                            📱 Scanner
                        </a>
                    @endif
                @endauth
                @if ($order->status === 'paid')
                    <a class="btn secondary" href="{{ route('public.orders.invoice', $order) }}" style="padding: 12px 20px;">
                        📄 Facture PDF
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($order->status !== 'paid')
        <div class="card" style="margin-bottom: 24px; padding: 20px; background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(251, 191, 36, 0.05)); border: 2px solid rgba(251, 191, 36, 0.3);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(251, 191, 36, 0.2); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                    ⏳
                </div>
                <div style="font-weight: 700; color: #d97706; font-size: 16px;">Paiement requis</div>
            </div>
            <p class="muted" style="margin-left: 44px; font-size: 14px; line-height: 1.6;">
                Les billets ne sont valides au scan qu'après paiement. Veuillez procéder au paiement pour activer vos billets.
            </p>
            <form method="POST" action="{{ route('public.orders.pay', $order) }}" style="margin-left: 44px; margin-top: 16px;">
                @csrf
                <button class="btn" type="submit" style="padding: 12px 24px;">
                    💳 Payer maintenant
                </button>
            </form>
        </div>
    @endif

    <div class="grid grid2" style="gap: 32px; margin-bottom: 32px;">
        <!-- Colonne principale -->
        <div style="flex: 1;">
            <!-- Informations de l'événement -->
            <div class="card" style="margin-bottom: 24px; padding: 32px;">
                <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.3px;">Événement</h2>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            🎉
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 6px;">{{ $order->event->name }}</div>
                            <div style="font-size: 14px; color: var(--we-muted);">
                                {{ optional($order->event->starts_at)->format('d/m/Y') }} · {{ optional($order->event->starts_at)->format('H:i') }} - {{ optional($order->event->ends_at)->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(96, 165, 250, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            📍
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 6px;">{{ $order->event->venue_name }}</div>
                            <div style="font-size: 14px; color: var(--we-muted);">{{ $order->event->venue_address }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billets -->
            <div class="card" style="padding: 32px;">
                <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.3px;">Billets</h2>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach ($order->tickets as $ticket)
                        <div style="padding: 24px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                                <div style="flex: 1;">
                                    <div style="display: inline-block; padding: 4px 12px; border-radius: 8px; background: var(--we-primary); color: #fff; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                                        Billet #{{ $loop->iteration }}
                                    </div>
                                    <div style="font-weight: 700; font-size: 18px; color: var(--we-text); margin-bottom: 6px;">
                                        {{ $ticket->ticketType->name }}
                                    </div>
                                    <div style="font-size: 14px; color: var(--we-text); margin-bottom: 4px;">
                                        <strong>{{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}</strong>
                                    </div>
                                    <div style="font-size: 13px; color: var(--we-muted);">
                                        {{ $ticket->attendee_email }}
                                        @if($ticket->attendee_phone)
                                            · {{ $ticket->attendee_phone }}
                                        @endif
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 20px; font-weight: 800; color: var(--we-primary);">
                                        {{ number_format($ticket->ticketType->price_cents, 0, ',', ' ') }} {{ $ticket->ticketType->currency }}
                                    </div>
                                </div>
                            </div>

                            @if ($order->status === 'paid')
                                <div style="margin-bottom: 16px;">
                                    <div style="display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap;">
                                        <div style="flex: 1; min-width: 200px;">
                                            <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                                                QR Code
                                            </div>
                                            <div style="padding: 16px; background: #fff; border-radius: 12px; border: 2px solid var(--we-border); display: flex; align-items: center; justify-content: center;">
                                                <img src="{{ route('tickets.qr', $ticket) }}" alt="QR Code"
                                                    style="width: 200px; height: 200px; border-radius: 8px;" />
                                            </div>
                                        </div>
                                        <div style="flex: 1; min-width: 200px;">
                                            <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                                                Token (support)
                                            </div>
                                            <div style="padding: 16px; background: #fff; border-radius: 12px; border: 2px solid var(--we-border);">
                                                <div style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 12px; word-break: break-all; color: var(--we-text); line-height: 1.6;">
                                                    {{ $ticket->qr_token }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div style="padding: 16px; background: #fafafa; border-radius: 12px; border: 1px dashed var(--we-border); text-align: center;">
                                    <div style="font-size: 48px; margin-bottom: 8px;">🔒</div>
                                    <div class="muted" style="font-size: 14px;">QR Code disponible après paiement</div>
                                </div>
                            @endif

                            <div style="padding-top: 16px; border-top: 1px solid var(--we-border);">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 14px; color: var(--we-muted);">Statut check-in :</span>
                                    @if ($ticket->checked_in_at)
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; background: rgba(34, 197, 94, 0.1); color: #16a34a;">
                                            ✅ Vérifié
                                        </span>
                                        <span class="muted" style="font-size: 12px;">
                                            le {{ $ticket->checked_in_at->format('d/m/Y à H:i:s') }}
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; background: rgba(148, 163, 184, 0.1); color: #64748b;">
                                            ⏸️ Non scanné
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div style="flex: 0 0 380px;">
            <!-- Récapitulatif -->
            <div class="card" style="margin-bottom: 24px; padding: 32px;">
                <h3 style="font-size: 20px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.3px;">Récapitulatif</h3>
                
                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid var(--we-border);">
                        <div>
                            <div style="font-weight: 600; color: var(--we-text); margin-bottom: 4px;">Billets</div>
                            <div style="font-size: 13px; color: var(--we-muted);">{{ $order->tickets->count() }} billet{{ $order->tickets->count() > 1 ? 's' : '' }}</div>
                        </div>
                        <div style="font-weight: 700; color: var(--we-text);">
                            {{ number_format($order->subtotal_cents, 0, ',', ' ') }} {{ $order->currency }}
                        </div>
                    </div>
                    
                    @if($order->addons_total_cents > 0)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid var(--we-border);">
                            <div>
                                <div style="font-weight: 600; color: var(--we-text); margin-bottom: 4px;">Options</div>
                                <div style="font-size: 13px; color: var(--we-muted);">
                                    @if($order->metadata && isset($order->metadata['addons']))
                                        {{ count($order->metadata['addons']) }} option{{ count($order->metadata['addons']) > 1 ? 's' : '' }}
                                    @endif
                                </div>
                            </div>
                            <div style="font-weight: 700; color: var(--we-text);">
                                {{ number_format($order->addons_total_cents, 0, ',', ' ') }} {{ $order->currency }}
                            </div>
                        </div>
                    @endif
                </div>

                <div style="padding-top: 20px; border-top: 2px solid var(--we-border);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 18px; font-weight: 800; color: var(--we-text);">Total</div>
                        <div style="font-size: 28px; font-weight: 900; color: var(--we-primary);">
                            {{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="card" style="padding: 32px;">
                <h3 style="font-size: 20px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.3px;">Contact</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                            Email
                        </div>
                        <div style="font-weight: 600; color: var(--we-text);">{{ $order->customer_email }}</div>
                    </div>
                    @if($order->customer_phone)
                        <div>
                            <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                Téléphone
                            </div>
                            <div style="font-weight: 600; color: var(--we-text);">{{ $order->customer_phone }}</div>
                        </div>
                    @endif
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                            Date de réservation
                        </div>
                        <div style="font-weight: 600; color: var(--we-text);">
                            {{ $order->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
