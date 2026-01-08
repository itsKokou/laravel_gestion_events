<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ControllerCredentialsNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $plainPassword,
        private readonly string $purpose = 'created', // created|reset
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = route('login');
        $subject = $this->purpose === 'reset'
            ? "Win’s Events — Réinitialisation du mot de passe"
            : "Win’s Events — Accès contrôleur";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour,')
            ->line("Un accès contrôleur vous a été attribué sur Win’s Events.")
            ->line("Email : {$notifiable->email}")
            ->line("Mot de passe : {$this->plainPassword}")
            ->action('Se connecter', $loginUrl)
            ->line("Pour des raisons de sécurité, changez votre mot de passe dès votre première connexion.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'purpose' => $this->purpose,
        ];
    }
}
