<x-layouts.app>
    <x-slot:title>Pengiriman</x-slot:title>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Pengiriman</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola semua pengiriman barang</p>
        </div>
        <a href="{{ route('deliveries.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengiriman
        </a>
    </div>

    <x-card class="!p-0">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" action="{{ route('deliveries.index') }}" class="flex flex-wrap gap-3">
                <div class="relative flex-1 min-w-[200px] max-w-xs">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari no. pengiriman, sopir, atau no. surat jalan..."
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>

                <select name="status"
                        class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s->value }}" {{ $status === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ $dateFrom }}"
                       class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">

                <input type="date" name="date_to" value="{{ $dateTo }}"
                       class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">

                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">Cari</button>

                @if ($search || $status || $dateFrom || $dateTo)
                    <a href="{{ route('deliveries.index') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Clear</a>
                @endif
            </form>
        </div>

        <x-table :headers="['No. Pengiriman', 'No. Order', 'Customer', 'Tanggal', 'Driver', 'Status', 'Action']">
            @forelse ($deliveries as $delivery)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        {{ $delivery->delivery_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $delivery->order?->order_number ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $delivery->order?->customer?->company_name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $delivery->delivery_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $delivery->driver_name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ match($delivery->status->value) {
                            'SCHEDULED' => 'bg-indigo-100 text-indigo-700 ring-indigo-700/10',
                            'IN_TRANSIT' => 'bg-amber-100 text-amber-700 ring-amber-700/10',
                            'DELIVERED' => 'bg-emerald-100 text-emerald-700 ring-emerald-700/10',
                            'PARTIALLY_DELIVERED' => 'bg-orange-100 text-orange-700 ring-orange-700/10',
                            'RETURNED' => 'bg-red-100 text-red-700 ring-red-700/10',
                            default => 'bg-slate-100 text-slate-700 ring-slate-700/10',
                        } }}">
                            {{ $delivery->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('deliveries.show', $delivery) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                Detail
                            </a>
                            <a href="{{ route('deliveries.edit', $delivery) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada pengiriman.
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($deliveries->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $deliveries->links() }}
            </div>
        @endif
    </x-card>

</x-layouts.app>
