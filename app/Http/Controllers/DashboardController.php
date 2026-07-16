<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $waitingPo = Order::where('status', OrderStatus::WAITING_PO)->count();
        $noInvoice = Order::whereIn('status', [
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::DELIVERY_SCHEDULED,
            OrderStatus::DELIVERED,
            OrderStatus::DELIVERY_NOTE_RETURNED,
            OrderStatus::WAITING_PO,
        ])->count();
        $unpaid = Order::where('status', OrderStatus::UNPAID)->count();

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'totalOrders', 'waitingPo', 'noInvoice', 'unpaid', 'recentOrders'
        ));
    }
}
