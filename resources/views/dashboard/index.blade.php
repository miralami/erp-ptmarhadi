<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    <x-page-header
        title="Dashboard"
        description="Overview status transaksi terkini"
    />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <x-stat-card
            title="Total Order"
            :value="(string)$totalOrders"
            icon="shopping-cart"
            color="blue"
            description="Seluruh transaksi"
        />
        <x-stat-card
            title="Menunggu PO"
            :value="(string)$waitingPo"
            icon="clock"
            color="amber"
            description="Menunggu dokumen PO"
        />
        <x-stat-card
            title="Belum Invoice"
            :value="(string)$noInvoice"
            icon="file-text"
            color="rose"
            description="Belum dibuatkan invoice"
        />
        <x-stat-card
            title="Belum Bayar"
            :value="(string)$unpaid"
            icon="alert-circle"
            color="red"
            description="Menunggu pembayaran"
        />
    </div>

    <x-card>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-slate-900">Transaksi Terbaru</h2>
            <a href="{{ route('orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition">
                Lihat semua &rarr;
            </a>
        </div>

        <x-table :headers="['No. Order', 'Customer', 'Tanggal', 'Status', 'Total', '']">
            @forelse ($recentOrders as $order)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        {{ $order->order_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $order->customer?->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $order->date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :status="$order->status" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        Rp {{ number_format($order->quantity * $order->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-700 font-medium transition">
                            Detail
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada transaksi.
                    </td>
                </tr>
            @endforelse
        </x-table>
    </x-card>

</x-layouts.app>
