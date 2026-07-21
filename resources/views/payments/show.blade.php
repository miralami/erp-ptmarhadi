<x-layouts.app>
    <x-slot:title>Detail Pembayaran</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Pembayaran
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-semibold text-slate-900">{{ $payment->payment_number }}</h1>
                    <x-badge :label="$payment->payment_method->label()" :color="$payment->payment_method->color()" />
                </div>
                <p class="text-sm text-slate-500">
                    {{ $payment->payment_date->format('d F Y') }}
                </p>
            </div>
            <a href="{{ route('payments.edit', $payment) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Pembayaran
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Detail Pembayaran</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $payment->payment_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Metode</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $payment->payment_method->label() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah</p>
                        <p class="text-lg font-semibold text-emerald-600 mt-0.5">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Referensi</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $payment->reference_number ?? '-' }}</p>
                    </div>
                </div>
                @if ($payment->notes)
                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Catatan</p>
                        <p class="text-sm text-slate-700">{{ $payment->notes }}</p>
                    </div>
                @endif
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Invoice</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. Invoice</p>
                        <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 mt-0.5 block">
                            {{ $payment->invoice->invoice_number ?? '-' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Customer</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $payment->invoice->customer?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Status</p>
                        <div class="mt-0.5">
                            <x-badge :label="$payment->invoice->status->label()" :color="$payment->invoice->status->color()" />
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Invoice</p>
                        <p class="text-sm font-semibold text-slate-900 mt-0.5">Rp {{ number_format($payment->invoice->invoice_total, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sisa Tagihan</p>
                        <p class="text-sm font-semibold {{ $payment->invoice->remaining > 0 ? 'text-red-600' : 'text-emerald-600' }} mt-0.5">
                            Rp {{ number_format($payment->invoice->remaining, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

</x-layouts.app>