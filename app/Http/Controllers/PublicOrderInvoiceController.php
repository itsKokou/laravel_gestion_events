<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InvoicePdfService;

class PublicOrderInvoiceController extends Controller
{
    public function download(Order $order, InvoicePdfService $invoicePdf)
    {
        $order->loadMissing(['event', 'tickets.ticketType']);

        abort_unless($order->status === 'paid', 404);

        $pdfBytes = $invoicePdf->render($order);

        $filename = "facture-{$order->order_number}.pdf";

        return response($pdfBytes, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}

