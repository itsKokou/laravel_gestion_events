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
        $qrCode = new QrCode(
            data: $ticket->qr_token,
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
