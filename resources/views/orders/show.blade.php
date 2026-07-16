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
                    <x-badge :status="$order->status" />
                </div>
                <p class="text-sm text-slate-500">
                    {{ $order->date->format('d F Y') }} &middot; PIC: Admin
                </p>
            </div>
            <a href="{{ route('orders.edit', $order) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Order
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-6">Progress Transaksi</h2>
                <x-timeline :status="$order->status" />
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Customer</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Alamat</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->customer->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->customer->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Telepon</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->customer->phone ?? '-' }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Detail Barang</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Barang</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $order->product_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $order->quantity }} pcs</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Harga Satuan</p>
                        <p class="text-sm text-slate-700 mt-0.5">Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total</p>
                        <p class="text-lg font-semibold text-slate-900 mt-0.5">Rp {{ number_format($order->quantity * $order->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Dokumen</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Surat Jalan</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $order->delivery_note_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. PO</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $order->po_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Invoice</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $order->invoice_number ?? '-' }}</p>
                    </div>
                    @if ($order->notes)
                        <div class="pt-3 border-t border-slate-100">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Catatan</p>
                            <p class="text-sm text-slate-700 mt-0.5">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

</x-layouts.app>
