# Configuration de l'envoi de mails (SMTP)

Ce guide explique comment configurer l'envoi de mails via SMTP pour l'application Win's Events.

## Variables d'environnement requises

Ajoutez les variables suivantes dans votre fichier `.env` :

```env
# Mailer par défaut (smtp pour utiliser SMTP)
MAIL_MAILER=smtp

# Configuration SMTP
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@example.com
MAIL_PASSWORD=votre_mot_de_passe
MAIL_ENCRYPTION=tls

# Adresse et nom de l'expéditeur
MAIL_FROM_ADDRESS=noreply@wins-events.com
MAIL_FROM_NAME="Win's Events"
```

## Configuration selon le fournisseur SMTP

### Gmail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre_email@gmail.com
MAIL_FROM_NAME="Win's Events"
```

**⚠️ Configuration Gmail - Étapes importantes :**

1. **Activer la validation en 2 étapes** (obligatoire) :
   - Allez sur : https://myaccount.google.com/security
   - Activez "Validation en deux étapes"

2. **Créer un mot de passe d'application** :
   - Allez sur : https://myaccount.google.com/apppasswords
   - Cliquez sur "Sélectionner une application" → "Autre (nom personnalisé)"
   - Entrez "Win's Events" comme nom
   - Cliquez sur "Générer"
   - **Copiez le mot de passe de 16 caractères** (sans espaces)

3. **Utiliser le mot de passe d'application dans `.env`** :
   - Collez les 16 caractères dans `MAIL_PASSWORD`
   - **Ne pas utiliser votre mot de passe Gmail normal !**

4. **Si vous obtenez l'erreur "BadCredentials"** :
   - Vérifiez que la validation en 2 étapes est bien activée
   - Vérifiez que vous utilisez bien le mot de passe d'application (16 caractères)
   - Assurez-vous qu'il n'y a pas d'espaces dans le mot de passe
   - Vous pouvez créer un nouveau mot de passe d'application si nécessaire

### Mailtrap (pour les tests)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username_mailtrap
MAIL_PASSWORD=votre_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@wins-events.com
MAIL_FROM_NAME="Win's Events"
```

### OVH

```env
MAIL_MAILER=smtp
MAIL_HOST=ssl0.ovh.net
MAIL_PORT=465
MAIL_USERNAME=votre_email@votre-domaine.com
MAIL_PASSWORD=votre_mot_de_passe
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="Win's Events"
```

### SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=votre_api_key_sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="Win's Events"
```

### Mailgun (SMTP)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=votre_username_mailgun
MAIL_PASSWORD=votre_password_mailgun
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine-mailgun.com
MAIL_FROM_NAME="Win's Events"
```

## Ports et chiffrement

- **Port 587** : Utilisez `MAIL_ENCRYPTION=tls` (recommandé)
- **Port 465** : Utilisez `MAIL_ENCRYPTION=ssl`
- **Port 25** : Non chiffré (non recommandé, souvent bloqué)

## Test de la configuration

Pour tester l'envoi de mails, vous pouvez :

1. **Via l'application** : Créez une réservation et effectuez un paiement
2. **Via Tinker** : 
   ```bash
   php artisan tinker
   ```
   Puis :
   ```php
   Mail::raw('Test d\'envoi de mail', function ($message) {
       $message->to('votre_email@example.com')
               ->subject('Test SMTP');
   });
   ```

## Emails envoyés par l'application

L'application envoie automatiquement :

1. **Confirmation de réservation** (`OrderPaidMail`)
   - Envoyé après le paiement d'une commande
   - Contient la facture PDF en pièce jointe
   - Destinataire : email du client

2. **Identifiants contrôleur** (`ControllerCredentialsNotification`)
   - Envoyé lors de la création d'un compte contrôleur
   - Envoyé lors de la réinitialisation du mot de passe d'un contrôleur
   - Destinataire : email du contrôleur

## Dépannage

### Les mails ne partent pas

1. Vérifiez que `MAIL_MAILER=smtp` dans votre `.env`
2. Vérifiez les logs : `storage/logs/laravel.log`
3. Testez avec Mailtrap pour isoler le problème
4. Vérifiez que le port n'est pas bloqué par un firewall

### Erreur "Connection refused"

- Vérifiez que le serveur SMTP est accessible
- Vérifiez le port (587 pour TLS, 465 pour SSL)
- Vérifiez que `MAIL_ENCRYPTION` correspond au port utilisé

### Erreur "Authentication failed"

- Vérifiez `MAIL_USERNAME` et `MAIL_PASSWORD`
- Pour Gmail, utilisez un mot de passe d'application
- Vérifiez que le compte email n'a pas de restrictions d'accès

## Mode développement (logs)

Pour ne pas envoyer de vrais mails pendant le développement, utilisez :

```env
MAIL_MAILER=log
```

Les mails seront enregistrés dans `storage/logs/laravel.log` au lieu d'être envoyés.
