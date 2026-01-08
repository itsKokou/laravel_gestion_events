<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with('event')
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('admin.orders.index', [
            'orders' => $orders,
        ]);
    }
}
