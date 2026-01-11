<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture {{ $order->order_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111827; font-size: 12px; }
        .muted { color: #6b7280; }
        .row { display: flex; justify-content: space-between; gap: 16px; }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; margin-top: 12px; }
        h1 { font-size: 18px; margin: 0; }
        h2 { font-size: 14px; margin: 0 0 8px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { text-align: left; font-weight: 700; }
        .right { text-align: right; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; background: #f3f4f6; font-weight: 700; }
        .qr { width: 140px; height: 140px; border: 1px solid #e5e7eb; border-radius: 10px; padding: 8px; background: #fff; }
    </style>
</head>
<body>
    <div class="row">
        <div>
            <h1>Facture</h1>
            <div class="muted">Win's Events</div>
        </div>
        <div class="right">
            <div><strong>Commande</strong> {{ $order->order_number }}</div>
            <div class="muted">Statut: <span class="badge">{{ $order->status }}</span></div>
            <div class="muted">Date: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Soirée</h2>
        <div><strong>{{ $event->name }}</strong></div>
        <div class="muted">{{ $event->venue_name }} — {{ $event->venue_address }}</div>
        <div class="muted">{{ $event->starts_at->format('d/m/Y H:i') }} → {{ $event->ends_at->format('d/m/Y H:i') }}</div>
    </div>

    <div class="card">
        <h2>Client</h2>
        <div>Email: <strong>{{ $order->customer_email }}</strong></div>
        @if ($order->customer_phone)
            <div>Téléphone: <strong>{{ $order->customer_phone }}</strong></div>
        @endif
    </div>

    <div class="card">
        <h2>Détail</h2>
        <table>
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Type</th>
                    <th class="right">Prix</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->tickets as $ticket)
                    <tr>
                        <td>
                            <div><strong>{{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}</strong></div>
                            <div class="muted">{{ $ticket->attendee_email }}</div>
                        </td>
                        <td>{{ $ticket->ticketType->name }}</td>
                        <td class="right">
                            {{ number_format($ticket->ticketType->price_cents, 0, ',', ' ') }} {{ $order->currency }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="margin-top: 12px;">
            <tbody>
                <tr>
                    <td class="muted">Sous-total billets</td>
                    <td class="right"><strong>{{ number_format($order->subtotal_cents, 0, ',', ' ') }} {{ $order->currency }}</strong></td>
                </tr>
                <tr>
                    <td class="muted">Options</td>
                    <td class="right"><strong>{{ number_format($order->addons_total_cents, 0, ',', ' ') }} {{ $order->currency }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="right"><strong>{{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>QR Codes (contrôle d'accès)</h2>
        <div class="muted">Présente ces QR codes à l'entrée.</div>

        <table>
            <thead>
                <tr>
                    <th>Billet</th>
                    <th>QR</th>
                    <th>Token (support)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->tickets as $ticket)
                    <tr>
                        <td>
                            <div><strong>{{ $ticket->ticketType->name }}</strong></div>
                            <div class="muted">{{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}</div>
                        </td>
                        <td>
                            <div class="qr">
                                {!! $qrSvgs[$ticket->id] ?? '' !!}
                            </div>
                        </td>
                        <td style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 10px;">
                            {{ $ticket->qr_token }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

