<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    <x-page-header title="Dashboard" description="Overview operasional dan keuangan" />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-4 mb-8">
        <x-stat-card title="Total Order" :value="(string)($kpis['totalOrders'] ?? 0)" icon="shopping-cart" color="blue" description="Seluruh transaksi" />
        <x-stat-card title="Pengiriman Aktif" :value="(string)($kpis['activeDeliveries'] ?? 0)" icon="truck" color="indigo" description="Dalam proses kirim" />
        <x-stat-card title="Outstanding Invoice" :value="(string)($kpis['outstandingInvoices'] ?? 0)" icon="file-text" color="amber" description="Belum dibayar" />
        <x-stat-card title="Piutang" :value="'Rp ' . number_format($kpis['totalReceivables'] ?? 0, 0, ',', '.')" icon="credit-card" color="red" description="Total outstanding" />
        <x-stat-card title="Invoice Overdue" :value="(string)($kpis['overdueInvoices'] ?? 0)" icon="alert-circle" color="rose" description="Melewati jatuh tempo" />
        <x-stat-card title="Dibayar Bulan Ini" :value="'Rp ' . number_format($kpis['paidThisMonth'] ?? 0, 0, ',', '.')" icon="check-circle" color="emerald" description="Total penerimaan" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-card>
            <h3 class="text-base font-semibold text-slate-900 mb-4">Revenue Bulanan</h3>
            <div class="relative w-full" style="min-height: 200px;"><canvas id="revenueChart"></canvas></div>
        </x-card>
        <x-card>
            <h3 class="text-base font-semibold text-slate-900 mb-4">Distribusi Status Invoice</h3>
            <div class="relative w-full" style="min-height: 200px;"><canvas id="invoiceChart"></canvas></div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-card>
            <h3 class="text-base font-semibold text-slate-900 mb-4">Top 5 Customers</h3>
            <div class="relative w-full" style="min-height: 200px;"><canvas id="topCustomersChart"></canvas></div>
        </x-card>
        <x-card>
            <h3 class="text-base font-semibold text-slate-900 mb-4">Tren Pembayaran (30 Hari)</h3>
            <div class="relative w-full" style="min-height: 200px;"><canvas id="paymentTrendChart"></canvas></div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-slate-900">Order Terbaru</h3>
                <a href="{{ route('orders.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentOrders as $order)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $order['order_number'] }}</p>
                            <p class="text-xs text-slate-500">{{ $order['customer']['company_name'] ?? '-' }}</p>
                        </div>
                        <x-badge :label="\App\Enums\OrderStatus::from($order['status'])->label()" :color="\App\Enums\OrderStatus::from($order['status'])->color()" />
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">Belum ada order.</p>
                @endforelse
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-slate-900">Pembayaran Terbaru</h3>
                <a href="{{ route('payments.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentPayments as $payment)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $payment['payment_number'] }}</p>
                            <p class="text-xs text-slate-500">{{ $payment['invoice']['order']['customer']['company_name'] ?? '-' }}</p>
                        </div>
                        <span class="text-sm font-semibold text-emerald-600">Rp {{ number_format($payment['amount'], 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">Belum ada pembayaran.</p>
                @endforelse
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-slate-900">Aktivitas Terbaru</h3>
                <a href="{{ route('activity-logs.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentActivities as $log)
                    <div class="text-sm">
                        <p class="text-slate-900">{{ $log['description'] ?? '-' }}</p>
                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-4">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </x-card>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const charts = [];

        function initChart(id, config) {
            const el = document.getElementById(id);
            if (el) charts.push(new Chart(el, config));
        }

        initChart('revenueChart', {
            type: 'bar',
            data: {
                labels: @json(array_map(fn($r) => $r['month'], $monthlyRevenue)),
                datasets: [{
                    label: 'Revenue',
                    data: @json(array_map(fn($r) => (float)$r['total'], $monthlyRevenue)),
                    backgroundColor: '#6366f1',
                    borderRadius: 6,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        initChart('invoiceChart', {
            type: 'doughnut',
            data: {
                labels: @json(array_map(fn($r) => \App\Enums\InvoiceStatus::from($r['status'])->label(), $invoiceDistribution)),
                datasets: [{
                    data: @json(array_map(fn($r) => (int)$r['count'], $invoiceDistribution)),
                    backgroundColor: ['#94a3b8', '#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#6b7280'],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        initChart('topCustomersChart', {
            type: 'bar',
            data: {
                labels: @json(array_map(fn($r) => $r['company_name'], $topCustomers)),
                datasets: [{
                    label: 'Total (Rp)',
                    data: @json(array_map(fn($r) => (float)$r['total'], $topCustomers)),
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, indexAxis: 'y' }
        });

        initChart('paymentTrendChart', {
            type: 'line',
            data: {
                labels: @json(array_map(fn($r) => $r['date'], $paymentTrend)),
                datasets: [{
                    label: 'Pembayaran',
                    data: @json(array_map(fn($r) => (float)$r['total'], $paymentTrend)),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    </script>
    @endpush
</x-layouts.app>
