<x-layouts.app>
    <x-slot:title>Detail Invoice</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Invoice
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-semibold text-slate-900">{{ $invoice->invoice_number }}</h1>
                    <x-badge :label="$invoice->status->label()" :color="$invoice->status->color()" />
                </div>
                <div class="flex items-center gap-4 text-sm text-slate-500 mt-1">
                    <span>Tgl Invoice: {{ $invoice->invoice_date->format('d F Y') }}</span>
                    <span>Jatuh Tempo: {{ $invoice->due_date->format('d F Y') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('invoices.send', $invoice) }}" class="inline">
                    @csrf
                    <button type="submit"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim
                    </button>
                </form>
                <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 text-white text-sm font-medium rounded-xl hover:bg-amber-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Print PDF
                </a>
                <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Record Payment
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Item Order</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                <th class="pb-3 pr-4">Barang</th>
                                <th class="pb-3 pr-4">Qty</th>
                                <th class="pb-3 pr-4">Harga</th>
                                <th class="pb-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($invoice->order->items as $item)
                                <tr>
                                    <td class="py-3 pr-4 font-medium text-slate-900">{{ $item->product_name }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $item->quantity }}</td>
                                    <td class="py-3 pr-4 text-slate-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-3 text-right font-medium text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-slate-500">Tidak ada item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            @if ($invoice->payments->count())
                <x-card>
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Riwayat Pembayaran</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                    <th class="pb-3 pr-4">No. Pembayaran</th>
                                    <th class="pb-3 pr-4">Tanggal</th>
                                    <th class="pb-3 pr-4">Metode</th>
                                    <th class="pb-3 pr-4">Referensi</th>
                                    <th class="pb-3 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($invoice->payments as $payment)
                                    <tr>
                                        <td class="py-3 pr-4 font-medium text-slate-900">{{ $payment->payment_number }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td class="py-3 pr-4">
                                            <x-badge :label="$payment->payment_method->label()" :color="$payment->payment_method->color()" />
                                        </td>
                                        <td class="py-3 pr-4 text-slate-600">{{ $payment->reference_number ?? '-' }}</td>
                                        <td class="py-3 text-right font-medium text-emerald-600">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Customer</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $invoice->customer?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Alamat</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $invoice->customer->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $invoice->customer->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Telepon</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $invoice->customer->phone ?? '-' }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Referensi Order</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Order</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $invoice->order->order_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tgl Order</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $invoice->order?->order_date?->format('d F Y') ?? '-' }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Invoice</p>
                        <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($invoice->invoice_total, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Dibayar</p>
                        <p class="text-sm font-semibold text-emerald-600">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sisa</p>
                        <p class="text-base font-semibold {{ $invoice->remaining > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                            Rp {{ number_format($invoice->remaining, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </x-card>

            @if ($invoice->notes)
                <x-card>
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Catatan</h2>
                    <p class="text-sm text-slate-700">{{ $invoice->notes }}</p>
                </x-card>
            @endif
        </div>
    </div>

</x-layouts.app>