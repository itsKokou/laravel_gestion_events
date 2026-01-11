<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $invoicePdfBytes  PDF généré (wkhtmltopdf / snappy)
     */
    public function __construct(
        public Order $order,
        private string $invoicePdfBytes,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Confirmation de réservation — Commande {$this->order->order_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_paid',
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->invoicePdfBytes, "facture-{$this->order->order_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}

