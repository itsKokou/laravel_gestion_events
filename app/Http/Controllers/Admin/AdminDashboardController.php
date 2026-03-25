<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        private AdminAnalyticsService $analytics,
    ) {}

    public function index(): View
    {
        $g = $this->analytics->getGlobalStats();

        return view('admin.dashboard', [
            'eventsCount' => $g['total_events'],
            'ordersCount' => $g['total_orders'],
            'paidOrdersCount' => $g['total_paid_orders'],
        ]);
    }

    /**
     * Statistiques globales (JSON) — prêt pour polling / futur Echo.
     */
    public function globalStats(): JsonResponse
    {
        return response()->json($this->analytics->getGlobalStats());
    }

    /**
     * Statistiques détaillées pour une soirée.
     */
    public function eventStats(int $id): JsonResponse
    {
        return response()->json($this->analytics->getEventStats($id));
    }

    /**
     * CA et série temporelle (query: period=7d|30d|90d|year).
     */
    public function revenue(Request $request): JsonResponse
    {
        $period = (string) $request->query('period', '30d');

        return response()->json($this->analytics->getRevenueStats($period));
    }

    /**
     * Ventes par tarif (query: event_id requis).
     */
    public function tickets(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,id'],
        ]);

        return response()->json(
            $this->analytics->getTicketSalesStats((int) $validated['event_id'])
        );
    }

    /**
     * Taux de conversion réservé → payé pour un événement.
     */
    public function conversion(int $id): JsonResponse
    {
        return response()->json($this->analytics->getConversionRate($id));
    }
}
