<?php

namespace App\Console\Commands;

use App\Services\ReservationService;
use Illuminate\Console\Command;

class ExpirePendingOrdersCommand extends Command
{
    protected $signature = 'orders:expire-pending';

    protected $description = 'Expire les commandes en attente de paiement dont la session a expiré et libère la capacité';

    public function handle(ReservationService $reservations): int
    {
        $count = $reservations->expireAllDuePendingOrders();
        $this->info("Commandes expirées : {$count}.");

        return self::SUCCESS;
    }
}
