<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Facture {{ $order->order_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            margin: 20mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f1b18;
            font-size: 11px;
            line-height: 1.5;
            background: #ffffff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }

        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .section {
            page-break-inside: avoid;
        }

        .qr-container {
            page-break-inside: avoid;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #ea580c;
        }

        .logo-section h1 {
            font-size: 28px;
            font-weight: 900;
            color: #ea580c;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .logo-section .subtitle {
            color: #8b7355;
            font-size: 12px;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info .invoice-number {
            font-size: 20px;
            font-weight: 900;
            color: #1f1b18;
            margin-bottom: 8px;
        }

        .invoice-info .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .invoice-info .date {
            color: #8b7355;
            font-size: 11px;
        }

        .section {
            margin-bottom: 24px;
            padding: 20px;
            background: #fafafa;
            border-radius: 12px;
            border: 1px solid #f0e8e0;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14px;
            font-weight: 900;
            color: #1f1b18;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ea580c;
        }

        .event-info {
            display: table;
            width: 100%;
        }

        .event-icon-wrapper {
            display: table-cell;
            width: 52px;
            vertical-align: top;
            padding-right: 12px;
        }

        .event-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(234, 88, 12, 0.15);
            text-align: center;
            font-size: 14px;
            font-weight: 900;
            color: #ea580c;
            line-height: 40px;
            display: block;
        }

        .event-details-wrapper {
            display: table-cell;
            vertical-align: top;
        }

        .event-details h3 {
            font-size: 16px;
            font-weight: 900;
            color: #1f1b18;
            margin-bottom: 6px;
        }

        .event-details .muted {
            color: #8b7355;
            font-size: 11px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0e8e0;
        }

        th {
            background: rgba(234, 88, 12, 0.05);
            font-weight: 700;
            color: #1f1b18;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #1f1b18;
            font-size: 11px;
        }

        .right {
            text-align: right;
        }

        .total-row {
            background: rgba(234, 88, 12, 0.05);
            font-weight: 700;
        }

        .total-row td {
            padding: 16px 12px;
            font-size: 14px;
            color: #ea580c;
        }

        .qr-section {
            margin-top: 30px;
        }

        .qr-container {
            display: table;
            width: 100%;
            padding: 12px;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #f0e8e0;
        }

        .qr-code-wrapper {
            display: table-cell;
            width: 136px;
            vertical-align: middle;
            padding-right: 16px;
        }

        .qr-code {
            width: 120px;
            height: 120px;
            border: 2px solid #f0e8e0;
            border-radius: 8px;
            padding: 8px;
            background: #ffffff;
            box-sizing: border-box;
            display: table;
            margin: 0 auto;
        }

        .qr-code-inner {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 104px;
            height: 104px;
        }

        .qr-code svg {
            width: 104px;
            height: 104px;
            display: block;
            margin: 0 auto;
        }

        .qr-info {
            display: table-cell;
            vertical-align: middle;
        }

        .qr-info {
            flex: 1;
        }

        .qr-info .ticket-type {
            font-weight: 700;
            color: #1f1b18;
            margin-bottom: 4px;
        }

        .qr-info .attendee {
            color: #8b7355;
            font-size: 10px;
            margin-bottom: 8px;
        }

        .qr-info .token {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
            font-size: 9px;
            color: #8b7355;
            word-break: break-all;
        }

        .summary-table {
            margin-top: 16px;
        }

        .summary-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0e8e0;
        }

        .summary-table .total-row td {
            border-top: 2px solid #ea580c;
            border-bottom: none;
            padding-top: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <h1>Win's Events</h1>
                <div class="subtitle">Facture</div>
            </div>
            <div class="invoice-info">
                <div class="invoice-number">{{ $order->order_number }}</div>
                <div>
                    <span class="badge">Payée</span>
                </div>
                <div class="date">{{ now()->format('d/m/Y à H:i') }}</div>
            </div>
        </div>

        <!-- Événement -->
        <div class="section">
            <div class="section-title">Événement</div>
            <div class="event-info">
                <div class="event-icon-wrapper">
                    <div class="event-icon">EV</div>
                </div>
                <div class="event-details-wrapper">
                    <div class="event-details">
                        <h3>{{ $event->name }}</h3>
                        <div class="muted">
                            <div><strong>Lieu:</strong> {{ $event->venue_name }}</div>
                            <div>{{ $event->venue_address }}</div>
                            <div style="margin-top: 4px;">
                                <strong>Date:</strong> {{ $event->starts_at->format('d/m/Y à H:i') }} →
                                {{ $event->ends_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client -->
        <div class="section">
            <div class="section-title">Client</div>
            <table>
                <tr>
                    <td style="width: 120px; color: #8b7355;">Email :</td>
                    <td><strong>{{ $order->customer_email }}</strong></td>
                </tr>
                @if ($order->customer_phone)
                    <tr>
                        <td style="color: #8b7355;">Téléphone :</td>
                        <td><strong>{{ $order->customer_phone }}</strong></td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Détail des billets -->
        <div class="section">
            <div class="section-title">Détail de la réservation</div>
            <table>
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Type de billet</th>
                        <th class="right">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->tickets as $ticket)
                        <tr>
                            <td>
                                <div style="font-weight: 700; margin-bottom: 2px;">
                                    {{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}
                                </div>
                                <div style="color: #8b7355; font-size: 10px;">
                                    {{ $ticket->attendee_email }}
                                </div>
                            </td>
                            <td>{{ $ticket->ticketType->name }}</td>
                            <td class="right">
                                <strong>{{ number_format($ticket->ticketType->price_cents, 0, ',', ' ') }}
                                    {{ $order->currency }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Récapitulatif -->
            <table class="summary-table">
                <tbody>
                    <tr>
                        <td style="color: #8b7355;">Sous-total billets</td>
                        <td class="right"><strong>{{ number_format($order->subtotal_cents, 0, ',', ' ') }}
                                {{ $order->currency }}</strong></td>
                    </tr>
                    @if($order->addons_total_cents > 0)
                        <tr>
                            <td style="color: #8b7355;">Options supplémentaires</td>
                            <td class="right"><strong>{{ number_format($order->addons_total_cents, 0, ',', ' ') }}
                                    {{ $order->currency }}</strong></td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td class="right" style="font-size: 18px;">{{ number_format($order->total_cents, 0, ',', ' ') }}
                            {{ $order->currency }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- QR Codes -->
        <div class="section qr-section avoid-break">
            <div class="section-title">QR Codes — Contrôle d'accès</div>
            <div style="color: #8b7355; font-size: 10px; margin-bottom: 16px;">
                Présentez ces QR codes à l'entrée de l'événement.
            </div>
            @foreach ($order->tickets as $ticket)
                <div class="qr-container avoid-break" style="margin-bottom: 16px;">
                    <div class="qr-code-wrapper">
                        <div class="qr-code">
                            <div class="qr-code-inner">
                                {!! $qrSvgs[$ticket->id] ?? '' !!}
                            </div>
                        </div>
                    </div>
                    <div class="qr-info">
                        <div class="ticket-type">{{ $ticket->ticketType->name }}</div>
                        <div class="attendee">{{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}</div>
                        <div class="token">{{ $ticket->qr_token }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div
            style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #f0e8e0; text-align: center; color: #8b7355; font-size: 10px;">
            <p>Win's Events — Facture générée automatiquement</p>
            <p style="margin-top: 4px;">Merci pour votre confiance !</p>
        </div>
    </div>
</body>

</html>