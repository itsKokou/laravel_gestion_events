<?php

namespace App\Services;

use App\Models\Order;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Symfony\Component\Process\Process;

class TicketPdfService
{
    public function __construct(
        private ViewFactory $view,
    ) {}

    /**
     * @return array<int, array{filename: string, bytes: string}>
     */
    public function renderForOrder(Order $order): array
    {
        $order->loadMissing(['event', 'tickets.ticketType']);

        $attachments = [];
        $writer = new SvgWriter;

        foreach ($order->tickets as $index => $ticket) {
            $qrCode = new QrCode(
                data: route('scanner.scan.url', [
                    'event' => $ticket->event->slug,
                    'token' => $ticket->qr_token,
                ]),
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 240,
                margin: 8,
            );

            $qrSvg = $writer->write($qrCode)->getString();

            $html = $this->view->make('pdf.ticket', [
                'order' => $order,
                'ticket' => $ticket,
                'event' => $order->event,
                'index' => $index + 1,
                'qrSvg' => $qrSvg,
            ])->render();

            $safeTicketNumber = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $attachments[] = [
                'filename' => "billet-{$order->order_number}-{$safeTicketNumber}.pdf",
                'bytes' => $this->renderWithWkhtmltopdf($html),
            ];
        }

        return $attachments;
    }

    private function renderWithWkhtmltopdf(string $html): string
    {
        $binary = config('snappy.pdf.binary')
            ?? env('WKHTMLTOPDF_BINARY')
            ?? 'wkhtmltopdf';

        $args = [
            $binary,
            '--encoding', 'utf-8',
            '--page-size', 'A4',
            '--margin-top', '8mm',
            '--margin-bottom', '8mm',
            '--margin-left', '8mm',
            '--margin-right', '8mm',
            '-',
            '-',
        ];

        $process = new Process($args);
        $process->setInput($html);
        $process->setTimeout(30);
        $process->run();

        if (! $process->isSuccessful()) {
            $err = trim($process->getErrorOutput() ?: $process->getOutput());
            throw new \RuntimeException('Génération PDF billet échouée: '.($err ?: 'inconnu'));
        }

        return $process->getOutput();
    }
}
