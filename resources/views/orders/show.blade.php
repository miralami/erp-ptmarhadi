<x-layouts.app>
    <x-slot:title>Detail Order</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Order
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-semibold text-slate-900">{{ $order->order_number }}</h1>
                    <x-badge :label="$order->status->label()" :color="$order->status->color()" />
                </div>
                <p class="text-sm text-slate-500">
                    {{ $order->order_date->format('d F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('orders.edit', $order) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Order
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Item Barang</h2>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Barang</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Unit</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse ($order->items as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $item->product_name }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700 text-right">{{ $item->unit }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Tidak ada item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-sm font-semibold text-slate-900 text-right">Total</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-900 text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-card>

            @if ($order->delivery)
                <x-card>
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Pengiriman</h2>
                    <a href="{{ route('deliveries.show', $order->delivery) }}"
                       class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-indigo-50 transition group">
                        <div>
                            <p class="text-sm font-medium text-slate-900 group-hover:text-indigo-700 transition">{{ $order->delivery->delivery_number }}</p>
                            <p class="text-xs text-slate-500">{{ $order->delivery->delivery_date->format('d/m/Y') }} &middot; {{ $order->delivery->driver_name ?? '-' }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ match($order->delivery->status->value) {
                            'SCHEDULED' => 'bg-indigo-100 text-indigo-700 ring-indigo-700/10',
                            'IN_TRANSIT' => 'bg-amber-100 text-amber-700 ring-amber-700/10',
                            'DELIVERED' => 'bg-emerald-100 text-emerald-700 ring-emerald-700/10',
                            'PARTIALLY_DELIVERED' => 'bg-orange-100 text-orange-700 ring-orange-700/10',
                            default => 'bg-slate-100 text-slate-600 ring-slate-700/10',
                        } }}">
                            {{ $order->delivery->status->label() }}
                        </span>
                    </a>
                </x-card>
            @endif

            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Aktivitas</h2>
                <div class="space-y-3">
                    @php
                        $activities = \App\Models\ActivityLog::where('module', 'order')
                            ->where('record_id', $order->id)
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

        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Customer</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $order->customer?->company_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Kontak</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->customer?->contact_person ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Alamat</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->customer?->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->customer?->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Telepon</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->customer?->phone ?? '-' }}</p>
                    </div>
                </div>
            </x-card>

            @if ($order->invoice)
                <x-card>
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Invoice</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Invoice</p>
                            <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $order->invoice->invoice_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Status</p>
                            <p class="text-sm text-slate-700 mt-0.5">{{ $order->invoice->status->label() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5">Rp {{ number_format($order->invoice->invoice_total, 0, ',', '.') }}</p>
                        </div>
                        @if ($order->invoice->paid_amount > 0)
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Terbayar</p>
                                <p class="text-sm font-semibold text-emerald-600 mt-0.5">Rp {{ number_format($order->invoice->paid_amount, 0, ',', '.') }}</p>
                            </div>
                        @endif
                    </div>
                </x-card>
            @endif

            @if ($order->notes)
                <x-card>
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Catatan</h2>
                    <p class="text-sm text-slate-700">{{ $order->notes }}</p>
                </x-card>
            @endif

            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Ubah Status</h2>
                @php $transitions = \App\Enums\OrderStatus::allowedTransitions()[$order->status->value] ?? [] @endphp
                @if (count($transitions) > 0)
                    <form method="POST" action="{{ route('orders.update-status', $order) }}" class="space-y-3">
                        @csrf
                        <select name="status" required
                                class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="">Pilih status baru</option>
                            @foreach ($transitions as $t)
                                <option value="{{ $t->value }}">{{ $t->label() }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                            Update Status
                        </button>
                    </form>
                @else
                    <p class="text-sm text-slate-500">Tidak ada transisi status yang tersedia.</p>
                @endif
            </x-card>
        </div>
    </div>

</x-layouts.app>
