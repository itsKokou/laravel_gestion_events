<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ControllerCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword,
        public string $purpose = 'created', // created|reset
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->purpose === 'reset'
            ? "Win's Events — Réinitialisation du mot de passe"
            : "Win's Events — Accès contrôleur";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.controller_credentials',
            with: [
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
                'loginUrl' => route('login'),
                'purpose' => $this->purpose,
            ],
        );
    }
}
