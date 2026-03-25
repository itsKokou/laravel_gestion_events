@php($order->loadMissing(['event', 'tickets.ticketType']))
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de réservation — {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #faf8f6; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #faf8f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <!-- Header avec gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #ea580c 0%, #f5820d 100%); padding: 40px 32px; text-align: center;">
                            <div style="font-size: 32px; margin-bottom: 12px;">🎉</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 900; letter-spacing: -0.5px;">
                                Réservation confirmée !
                            </h1>
                            <p style="margin: 8px 0 0 0; color: rgba(255, 255, 255, 0.95); font-size: 16px;">
                                Votre paiement a été validé
                            </p>
                        </td>
                    </tr>

                    <!-- Contenu principal -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 24px 0; color: #1f1b18; font-size: 16px; line-height: 1.6;">
                                Bonjour,
                            </p>

                            <p style="margin: 0 0 24px 0; color: #1f1b18; font-size: 16px; line-height: 1.6;">
                                Merci pour votre réservation ! Votre paiement a bien été confirmé pour la réservation
                                <strong style="color: #ea580c;">{{ $order->order_number }}</strong>.
                            </p>

                            <!-- Carte événement -->
                            <div style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border: 2px solid rgba(234, 88, 12, 0.15); border-radius: 12px; padding: 24px; margin: 24px 0;">
                                <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
                                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(234, 88, 12, 0.15); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                                        🎉
                                    </div>
                                    <div style="flex: 1;">
                                        <h2 style="margin: 0 0 8px 0; color: #1f1b18; font-size: 20px; font-weight: 900;">
                                            {{ $order->event->name }}
                                        </h2>
                                        <div style="color: #8b7355; font-size: 14px; line-height: 1.6;">
                                            <div style="margin-bottom: 4px;">📅 {{ $order->event->starts_at->format('d/m/Y à H:i') }}</div>
                                            <div>📍 {{ $order->event->venue_name }}</div>
                                            <div style="margin-top: 4px;">{{ $order->event->venue_address }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations réservation -->
                            <div style="background: #fafafa; border-radius: 12px; padding: 20px; margin: 24px 0; border: 1px solid #f0e8e0;">
                                <div style="font-size: 12px; font-weight: 700; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                                    Détails de la réservation
                                </div>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 8px 0; color: #1f1b18;">
                                            <strong>Numéro de réservation :</strong>
                                        </td>
                                        <td style="padding: 8px 0; text-align: right; color: #ea580c; font-weight: 700;">
                                            {{ $order->order_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #1f1b18;">
                                            <strong>Nombre de billets :</strong>
                                        </td>
                                        <td style="padding: 8px 0; text-align: right; color: #1f1b18;">
                                            {{ $order->tickets->count() }} billet{{ $order->tickets->count() > 1 ? 's' : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #1f1b18;">
                                            <strong>Total payé :</strong>
                                        </td>
                                        <td style="padding: 8px 0; text-align: right; color: #ea580c; font-size: 18px; font-weight: 900;">
                                            {{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Boutons d'action -->
                            <div style="margin: 32px 0;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="padding: 0 8px;">
                                            <a href="{{ route('public.orders.show', $order) }}" style="display: inline-block; padding: 14px 28px; background-color: #ea580c; color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(234, 88, 12, 0.25);">
                                                📱 Voir mes billets
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="margin: 24px 0;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="padding: 0 8px;">
                                            <a href="{{ route('public.orders.invoice', $order) }}" style="display: inline-block; padding: 12px 24px; background-color: #ffffff; color: #ea580c; text-decoration: none; border: 2px solid #ea580c; border-radius: 12px; font-weight: 600; font-size: 14px;">
                                                📄 Télécharger la facture
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Informations importantes -->
                            <div style="background: rgba(251, 191, 36, 0.1); border-left: 4px solid #f59e0b; border-radius: 8px; padding: 16px; margin: 24px 0;">
                                <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.6;">
                                    <strong>💡 Important :</strong> Vos billets individuels (avec QR code) et votre facture sont joints a cet email en PDF.
                                    Vous pouvez egalement retrouver vos billets sur la page de reservation.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #fafafa; padding: 24px 32px; text-align: center; border-top: 1px solid #f0e8e0;">
                            <p style="margin: 0 0 8px 0; color: #8b7355; font-size: 14px; font-weight: 600;">
                                Win's Events
                            </p>
                            <p style="margin: 0; color: #8b7355; font-size: 12px;">
                                Email automatique — Ne pas répondre
                            </p>
                            <p style="margin: 12px 0 0 0; color: #8b7355; font-size: 12px;">
                                Si vous avez des questions, contactez-nous via notre site web.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
