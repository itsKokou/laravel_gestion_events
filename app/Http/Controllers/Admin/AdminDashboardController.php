<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $eventsCount = Event::count();
        $ordersCount = Order::count();
        $paidOrdersCount = Order::where('status', 'paid')->count();

        return view('admin.dashboard', [
            'eventsCount' => $eventsCount,
            'ordersCount' => $ordersCount,
            'paidOrdersCount' => $paidOrdersCount,
        ]);
    }
}
