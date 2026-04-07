<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $purpose === 'reset' ? 'Réinitialisation du mot de passe' : 'Accès contrôleur' }} — Win's Events</title>
</head>
<body style="margin: 0; padding: 0; background-color: #faf8f6; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #faf8f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #ea580c 0%, #f5820d 100%); padding: 40px 32px; text-align: center;">
                            <div style="font-size: 32px; margin-bottom: 12px;">🔐</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 900; letter-spacing: -0.5px;">
                                {{ $purpose === 'reset' ? 'Mot de passe réinitialisé' : 'Accès contrôleur' }}
                            </h1>
                            <p style="margin: 8px 0 0 0; color: rgba(255, 255, 255, 0.95); font-size: 16px;">
                                Win's Events
                            </p>
                        </td>
                    </tr>

                    <!-- Contenu principal -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 24px 0; color: #1f1b18; font-size: 16px; line-height: 1.6;">
                                Bonjour,
                            </p>

                            @if($purpose === 'reset')
                                <p style="margin: 0 0 24px 0; color: #1f1b18; font-size: 16px; line-height: 1.6;">
                                    Votre mot de passe contrôleur a été réinitialisé sur Win's Events.
                                </p>
                            @else
                                <p style="margin: 0 0 24px 0; color: #1f1b18; font-size: 16px; line-height: 1.6;">
                                    Un accès contrôleur vous a été attribué sur Win's Events. Vous pouvez maintenant scanner les billets lors des événements.
                                </p>
                            @endif

                            <!-- Carte avec identifiants -->
                            <div style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border: 2px solid rgba(234, 88, 12, 0.15); border-radius: 12px; padding: 24px; margin: 24px 0;">
                                <div style="font-size: 12px; font-weight: 700; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                                    Vos identifiants de connexion
                                </div>
                                
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
                                    <tr>
                                        <td style="padding: 12px; background: #ffffff; border-radius: 8px; border: 1px solid #f0e8e0;">
                                            <div style="font-size: 11px; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                                Email
                                            </div>
                                            <div style="font-size: 16px; font-weight: 700; color: #1f1b18;">
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 12px; background: #ffffff; border-radius: 8px; border: 1px solid #f0e8e0;">
                                            <div style="font-size: 11px; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                                Mot de passe
                                            </div>
                                            <div style="font-size: 18px; font-weight: 900; color: #ea580c; font-family: ui-monospace, monospace; letter-spacing: 2px;">
                                                {{ $plainPassword }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Bouton de connexion -->
                            <div style="margin: 32px 0;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $loginUrl }}" style="display: inline-block; padding: 14px 28px; background-color: #ea580c; color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(234, 88, 12, 0.25);">
                                                🔑 Se connecter
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Avertissement sécurité -->
                            <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #dc2626; border-radius: 8px; padding: 16px; margin: 24px 0;">
                                <p style="margin: 0; color: #991b1b; font-size: 14px; line-height: 1.6;">
                                    <strong>⚠️ Sécurité :</strong> Pour des raisons de sécurité, veuillez changer votre mot de passe dès votre première connexion.
                                </p>
                            </div>

                            @if($purpose === 'created')
                                <div style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; border-radius: 8px; padding: 16px; margin: 24px 0;">
                                    <p style="margin: 0; color: #1e40af; font-size: 14px; line-height: 1.6;">
                                        <strong>💡 Astuce :</strong> Une fois connecté, vous pourrez accéder au scanner de billets pour vérifier les entrées lors des événements.
                                    </p>
                                </div>
                            @endif
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
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
