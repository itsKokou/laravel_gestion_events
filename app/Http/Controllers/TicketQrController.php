<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

class TicketQrController extends Controller
{
    public function show(Ticket $ticket)
    {
        // Le QR code contient une URL qui permet de scanner automatiquement le billet
        $scanUrl = route('scanner.scan.url', [
            'event' => $ticket->event->slug,
            'token' => $ticket->qr_token,
        ]);

        $qrCode = new QrCode(
            data: $scanUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 260,
            margin: 10,
        );

        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        return response($result->getString(), 200)
            ->header('Content-Type', 'image/svg+xml; charset=UTF-8')
            ->header('Cache-Control', 'private, max-age=300');
    }
}
