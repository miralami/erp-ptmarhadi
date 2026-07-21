<x-layouts.app>
    <x-slot:title>Surat Pengiriman</x-slot:title>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Surat Pengiriman</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola surat pengiriman barang</p>
        </div>
        <a href="{{ route('surat-pengiriman.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat SP Baru
        </a>
    </div>

    <x-card class="!p-0">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" action="{{ route('surat-pengiriman.index') }}" class="flex flex-wrap gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari SP, customer, atau kota..."
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
                <select name="status" class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s->value }}" {{ $status === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <select name="category" class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->value }}" {{ $category === $c->value ? 'selected' : '' }}>{{ $c->label() }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl" placeholder="Dari">
                <input type="date" name="date_to" value="{{ $dateTo }}" class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl" placeholder="Sampai">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">Cari</button>
                @if ($search || $status || $category || $dateFrom || $dateTo)
                    <a href="{{ route('surat-pengiriman.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Reset</a>
                @endif
            </form>
        </div>

        <x-table :headers="['No. SP', 'Customer', 'Rute', 'Kategori', 'Driver', 'Kendaraan', 'Status', 'Uang Jalan', 'Aksi']">
            @forelse ($orders as $order)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        <a href="{{ route('surat-pengiriman.show', $order) }}" class="text-indigo-600 hover:text-indigo-700">{{ $order->order_number }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $order->customer->company_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $order->origin_city ?? '?' }} → {{ $order->destination_city ?? '?' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($order->category)
                            <x-badge :label="$order->category->label()" :color="$order->category->color()" />
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $order->delivery->driver_name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        @if ($order->delivery?->vehicle)
                            {{ $order->delivery->vehicle->plate_number }}
                        @elseif ($order->delivery?->vehicle_plate_manual)
                            {{ $order->delivery->vehicle_plate_manual }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :label="$order->status->label()" :color="$order->status->color()" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        @if ($order->delivery?->uang_jalan)
                            Rp {{ number_format($order->delivery->uang_jalan, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('surat-pengiriman.show', $order) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada surat pengiriman.
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
