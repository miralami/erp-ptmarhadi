<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $kpis = $this->dashboardService->getKpis();
        $monthlyRevenue = $this->dashboardService->getMonthlyRevenue();
        $invoiceDistribution = $this->dashboardService->getInvoiceStatusDistribution();
        $topCustomers = $this->dashboardService->getTopCustomers();
        $paymentTrend = $this->dashboardService->getPaymentTrend();
        $recentOrders = $this->dashboardService->getRecentOrders();
        $recentPayments = $this->dashboardService->getRecentPayments();
        $recentActivities = $this->dashboardService->getRecentActivities();

        return view('dashboard.index', compact(
            'kpis',
            'monthlyRevenue',
            'invoiceDistribution',
            'topCustomers',
            'paymentTrend',
            'recentOrders',
            'recentPayments',
            'recentActivities',
        ));
    }
}
