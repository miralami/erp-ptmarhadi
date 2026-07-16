<x-layouts.app>
    <x-slot:title>Order</x-slot:title>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Order</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola semua transaksi order</p>
        </div>
        <a href="{{ route('orders.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Order
        </a>
    </div>

    <x-card class="!p-0">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" action="{{ route('orders.index') }}" class="flex gap-3">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nomor order atau customer..."
                        class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                    >
                </div>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">Cari</button>
                @if ($search)
                    <a href="{{ route('orders.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Reset</a>
                @endif
            </form>
        </div>

        <x-table :headers="['No. Order', 'Customer', 'Tanggal', 'Status', 'Total', 'Action']">
            @forelse ($orders as $order)
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('orders.show', $order) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                Detail
                            </a>
                            <a href="{{ route('orders.edit', $order) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada order.
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($orders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </x-card>

</x-layouts.app>
