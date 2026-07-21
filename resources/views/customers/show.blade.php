<x-layouts.app>
    <x-slot:title>Detail Customer</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Customer
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $customer->company_name }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $customer->contact_person ?? '-' }}</p>
            </div>
            <a href="{{ route('customers.edit', $customer) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Customer
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-1">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Kontak</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">NPWP</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $customer->npwp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $customer->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Telepon</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $customer->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Alamat</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $customer->address ?? '-' }}</p>
                    </div>
                    @if ($customer->notes)
                        <div class="pt-3 border-t border-slate-100">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Catatan</p>
                            <p class="text-sm text-slate-700 mt-0.5">{{ $customer->notes }}</p>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-slate-900">Order Terbaru</h2>
                    <a href="{{ route('orders.index', ['customer_id' => $customer->id]) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Lihat semua</a>
                </div>
                <x-table :headers="['No. Order', 'Tanggal', 'Status', 'Total']">
                    @forelse ($customer->orders()->latest()->limit(5)->get() as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-700">{{ $order->order_number }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $order->order_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badge :label="$order->status->label()" :color="$order->status->color()" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">
                                Belum ada order.
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>

            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-slate-900">Invoice Terbaru</h2>
                    <a href="{{ route('invoices.index', ['customer_id' => $customer->id]) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Lihat semua</a>
                </div>
                <x-table :headers="['No. Invoice', 'Tanggal', 'Jatuh Tempo', 'Status', 'Total']">
                    @forelse ($customer->invoices()->latest()->limit(5)->get() as $invoice)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $invoice->invoice_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $invoice->due_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badge :label="$invoice->status->label()" :color="$invoice->status->color()" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                Rp {{ number_format($invoice->invoice_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                Belum ada invoice.
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>

            <x-card>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-slate-900">Pembayaran Terbaru</h2>
                    <a href="{{ route('payments.index', ['customer_id' => $customer->id]) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Lihat semua</a>
                </div>
                <x-table :headers="['No. Pembayaran', 'Tanggal', 'Metode', 'Jumlah']">
                    @forelse ($customer->payments()->latest()->limit(5)->get() as $payment)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                {{ $payment->payment_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $payment->payment_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $payment->payment_method->label() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-emerald-600">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">
                                Belum ada pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>

</x-layouts.app>
