<?php

namespace App\Services;

use App\Enums\DeliveryStatus;
use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getKpis(): array
    {
        $paidThisMonth = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        $totalInvoiceThisMonth = Invoice::whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('invoice_total');

        $totalUangJalanThisMonth = Delivery::whereMonth('delivery_date', now()->month)
            ->whereYear('delivery_date', now()->year)
            ->sum('uang_jalan');

        $totalExpensesThisMonth = DB::table('delivery_expenses')
            ->join('deliveries', 'delivery_expenses.delivery_id', '=', 'deliveries.id')
            ->whereMonth('deliveries.delivery_date', now()->month)
            ->whereYear('deliveries.delivery_date', now()->year)
            ->sum('delivery_expenses.amount');

        $nettThisMonth = $totalInvoiceThisMonth - $totalUangJalanThisMonth - $totalExpensesThisMonth;

        return [
            'totalOrders' => Order::count(),
            'activeDeliveries' => Order::whereIn('status', [
                OrderStatus::PERJALANAN_MUAT,
                OrderStatus::PERJALANAN_BONGKAR,
            ])->count(),
            'outstandingInvoices' => Invoice::whereIn('status', [
                InvoiceStatus::SENT,
                InvoiceStatus::OVERDUE,
                InvoiceStatus::PARTIALLY_PAID,
            ])->count(),
            'totalReceivables' => Invoice::whereIn('status', [
                InvoiceStatus::SENT,
                InvoiceStatus::OVERDUE,
                InvoiceStatus::PARTIALLY_PAID,
            ])->sum(DB::raw('invoice_total - paid_amount')),
            'overdueInvoices' => Invoice::where('status', InvoiceStatus::OVERDUE)->count(),
            'paidThisMonth' => $paidThisMonth,
            'totalInvoiceThisMonth' => $totalInvoiceThisMonth,
            'totalUangJalanThisMonth' => $totalUangJalanThisMonth,
            'nettThisMonth' => $nettThisMonth,
        ];
    }

    public function getMonthlyRevenue(int $months = 6): array
    {
        $results = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = Payment::whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->sum('amount');

            $results[] = [
                'month' => $date->format('Y-m'),
                'total' => (float) $total,
            ];
        }
        return $results;
    }

    public function getInvoiceStatusDistribution(): array
    {
        return Invoice::select(
            'status',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(invoice_total) as total'),
        )
            ->groupBy('status')
            ->get()
            ->toArray();
    }

    public function getTopCustomers(int $limit = 5): array
    {
        return DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->select(
                'customers.company_name',
                DB::raw('SUM(invoices.invoice_total) as total'),
                DB::raw('COUNT(invoices.id) as invoice_count'),
            )
            ->groupBy('customers.id', 'customers.company_name')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn($row) => (array) $row)
            ->toArray();
    }

    public function getPaymentTrend(int $days = 30): array
    {
        $results = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $total = Payment::whereDate('payment_date', $date)->sum('amount');

            $results[] = [
                'date' => $date,
                'total' => (float) $total,
            ];
        }
        return $results;
    }

    public function getRecentOrders(int $limit = 5): array
    {
        return Order::with('customer')
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getRecentPayments(int $limit = 5): array
    {
        return Payment::with('invoice.order.customer')
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getRecentActivities(int $limit = 5): array
    {
        return \App\Models\ActivityLog::with('user')
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
