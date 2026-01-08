<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class PaytechService
{
    /**
     * NOTE:
     * Cette classe est un squelette "safe" (sans dépendre d'un format exact PayTech).
     * On centralise ici la construction des URLs et des références.
     *
     * À adapter selon la doc officielle PayTech (champs attendus, signature, etc.).
     */
    public function createPayment(Order $order): array
    {
        $reference = $order->payment_reference ?: ('pt_' . Str::uuid()->toString());

        // TODO(PayTech): remplacer par la vraie URL PayTech + paramètres.
        // Pour l'instant, on redirige vers une page de retour interne.
        $redirectUrl = route('paytech.return', [
            'order' => $order->order_number,
            'reference' => $reference,
            'status' => 'success',
        ]);

        return [
            'reference' => $reference,
            'redirect_url' => $redirectUrl,
        ];
    }
}

