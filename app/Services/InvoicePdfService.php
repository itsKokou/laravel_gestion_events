<?php

namespace App\Services;

use App\Models\Order;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Arr;
use Symfony\Component\Process\Process;

class InvoicePdfService
{
    public function __construct(
        private ViewFactory $view,
    ) {}

    /**
     * Rend la facture en PDF (bytes).
     *
     * - Si laravel-snappy est installé, on l'utilise.
     * - Sinon, on appelle wkhtmltopdf directement.
     */
    public function render(Order $order): string
    {
        $order->loadMissing(['event', 'tickets.ticketType']);

        $qrSvgs = [];
        $writer = new SvgWriter();

        foreach ($order->tickets as $ticket) {
            $qrCode = new QrCode(
                data: $ticket->qr_token,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 220,
                margin: 8,
            );
            $qrSvgs[$ticket->id] = $writer->write($qrCode)->getString();
        }

        $html = $this->view->make('pdf.invoice', [
            'order' => $order,
            'event' => $order->event,
            'qrSvgs' => $qrSvgs,
        ])->render();

        if (app()->bound('snappy.pdf.wrapper')) {
            /** @var mixed $snappy */
            $snappy = app('snappy.pdf.wrapper');

            // Options wkhtmltopdf courantes (tu peux ajuster ensuite).
            $snappy->setOption('encoding', 'UTF-8');
            $snappy->setOption('margin-top', '10mm');
            $snappy->setOption('margin-bottom', '10mm');
            $snappy->setOption('margin-left', '10mm');
            $snappy->setOption('margin-right', '10mm');

            return (string) $snappy->getOutputFromHtml($html);
        }

        return $this->renderWithWkhtmltopdf($html);
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
            '--margin-top', '10mm',
            '--margin-bottom', '10mm',
            '--margin-left', '10mm',
            '--margin-right', '10mm',
            '-', // stdin
            '-', // stdout
        ];

        $process = new Process($args);
        $process->setInput($html);
        $process->setTimeout(30);
        $process->run();

        if (! $process->isSuccessful()) {
            $err = trim($process->getErrorOutput() ?: $process->getOutput());
            throw new \RuntimeException("wkhtmltopdf a échoué. Vérifie l'installation/chemin. Détail: " . ($err ?: 'inconnu'));
        }

        return $process->getOutput();
    }
}

