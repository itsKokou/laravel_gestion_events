<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Billet {{ $order->order_number }} #{{ $index }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f1b18; margin: 0; }
        .card { border: 1px solid #e7ddd3; border-radius: 14px; overflow: hidden; }
        .header { background: #ea580c; color: #fff; padding: 18px 22px; }
        .content { padding: 18px 22px; }
        .muted { color: #7b6d60; font-size: 12px; }
        .label { font-size: 11px; color: #7b6d60; text-transform: uppercase; font-weight: 700; letter-spacing: .04em; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1 style="margin:0; font-size: 20px;">Billet #{{ $index }} - {{ $order->order_number }}</h1>
        </div>
        <div class="content">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align: top;">
                        <p class="label">Evenement</p>
                        <p style="margin: 4px 0 10px 0; font-size: 18px; font-weight: 700;">{{ $event->name }}</p>

                        <p class="label">Participant</p>
                        <p style="margin: 4px 0 10px 0; font-size: 15px; font-weight: 700;">
                            {{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}
                        </p>
                        <p class="muted" style="margin: 0 0 10px 0;">
                            {{ $ticket->attendee_email }}
                            @if($ticket->attendee_phone)
                                • {{ $ticket->attendee_phone }}
                            @endif
                        </p>

                        <p class="label">Type de billet</p>
                        <p style="margin: 4px 0 10px 0; font-size: 15px; font-weight: 700;">{{ $ticket->ticketType->name }}</p>

                        <p class="label">Date et lieu</p>
                        <p class="muted" style="margin: 4px 0;">
                            {{ optional($event->starts_at)->format('d/m/Y H:i') }} - {{ $event->venue_name }}
                        </p>
                    </td>
                    <td width="220" align="center" style="vertical-align: top;">
                        <div style="border:1px solid #eee3d8; border-radius: 10px; padding: 8px;">
                            {!! $qrSvg !!}
                        </div>
                        <p class="muted" style="margin-top: 8px;">Presentez ce QR code a l'entree</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

