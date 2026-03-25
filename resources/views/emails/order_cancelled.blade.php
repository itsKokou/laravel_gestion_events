<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réservation annulée</title>
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.5; color: #1e293b;">
    <p>Bonjour,</p>
    <p>Votre réservation <strong style="font-family: ui-monospace, monospace;">{{ $order->order_number }}</strong> a été annulée.</p>
    @if ($order->event)
        <p>Soirée : <strong>{{ $order->event->name }}</strong></p>
    @endif
    <p>Si vous avez des questions, contactez l’organisateur.</p>
</body>
</html>
