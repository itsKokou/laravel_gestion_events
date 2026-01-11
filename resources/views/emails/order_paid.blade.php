@php($order->loadMissing(['event', 'tickets.ticketType']))
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de réservation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.45;">
    <p>Bonjour,</p>

    <p>
        Merci, votre paiement a bien été confirmé pour la commande
        <strong>{{ $order->order_number }}</strong>.
    </p>

    <p>
        Soirée : <strong>{{ $order->event->name }}</strong><br>
        Date : {{ $order->event->starts_at->format('d/m/Y H:i') }}<br>
        Lieu : {{ $order->event->venue_name }} — {{ $order->event->venue_address }}
    </p>

    <p>
        Vous pouvez retrouver vos billets (QR codes) ici :
        <a href="{{ route('public.orders.show', $order) }}">{{ route('public.orders.show', $order) }}</a>
    </p>

    <p>
        Votre facture est jointe à cet email (PDF).
        Vous pouvez aussi la télécharger ici :
        <a href="{{ route('public.orders.invoice', $order) }}">{{ route('public.orders.invoice', $order) }}</a>
    </p>

    <p style="margin-top: 18px; font-size: 12px; color: #6b7280;">
        Win's Events — email automatique
    </p>
</body>
</html>

