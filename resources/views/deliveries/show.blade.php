<x-layouts.app>
    <x-slot:title>Detail Pengiriman</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('deliveries.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Pengiriman
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-semibold text-slate-900">{{ $delivery->delivery_number }}</h1>
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
                </div>
                <p class="text-sm text-slate-500">
                    {{ $delivery->delivery_date->format('d F Y') }}
                </p>
            </div>
            <a href="{{ route('deliveries.edit', $delivery) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Pengiriman
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Pengiriman</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal Kirim</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $delivery->delivery_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Driver</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->driver_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Kendaraan</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->vehicle_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Surat Jalan</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->delivery_note_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Barang</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $delivery->product_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->quantity }}</p>
                    </div>
                    @if ($delivery->notes)
                        <div class="pt-3 border-t border-slate-100">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Catatan</p>
                            <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->notes }}</p>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Order</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Order</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $delivery->order?->order_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal Order</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->order?->order_date?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Customer</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $delivery->order?->customer?->company_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Alamat</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->order?->customer?->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Telepon</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $delivery->order?->customer?->phone ?? '-' }}</p>
                    </div>
                    @if ($delivery->order)
                        <div class="pt-3">
                            <a href="{{ route('orders.show', $delivery->order) }}"
                               class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition">
                                Lihat Detail Order
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </x-card>

            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Aktivitas</h2>
                <div class="space-y-3">
                    @php
                        $activities = \App\Models\ActivityLog::where('module', 'delivery')
                            ->where('record_id', $delivery->id)
                            ->latest()
                            ->take(10)
                            ->get();
                    @endphp
                    @forelse ($activities as $log)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full bg-indigo-400 mt-1.5 shrink-0"></div>
                            <div class="text-sm">
                                <p class="text-slate-900">{{ $log->description }}</p>
                                <p class="text-xs text-slate-500">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-4">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>

</x-layouts.app>
