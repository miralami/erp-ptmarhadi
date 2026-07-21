<x-layouts.app>
    <x-slot:title>Invoice</x-slot:title>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Invoice</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola semua tagihan invoice</p>
        </div>
        <a href="{{ route('invoices.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Invoice
        </a>
    </div>

    <x-card class="!p-0">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari invoice atau customer..."
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
                <select name="status"
                        class="px-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Semua Status</option>
                    @foreach (\App\Enums\InvoiceStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
                <select name="customer_id"
                        class="px-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Semua Customer</option>
                    @foreach ($customers as $id => $name)
                        <option value="{{ $id }}" {{ request('customer_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 transition"
                           onchange="this.form.submit()">
                    <span class="text-sm text-slate-600">Overdue</span>
                </label>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">Cari</button>
                @if ($search || request('status') || request('customer_id') || request('overdue'))
                    <a href="{{ route('invoices.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Reset</a>
                @endif
            </form>
        </div>

        <x-table :headers="['No. Invoice', 'Customer', 'Order', 'Tgl Invoice', 'Jatuh Tempo', 'Total', 'Dibayar', 'Sisa', 'Status', 'Action']">
            @forelse ($invoices as $invoice)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        {{ $invoice->invoice_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $invoice->customer?->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $invoice->order?->order_number ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $invoice->invoice_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $invoice->due_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        Rp {{ number_format($invoice->invoice_total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $invoice->remaining > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        Rp {{ number_format($invoice->remaining, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :label="$invoice->status->label()" :color="$invoice->status->color()" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('invoices.show', $invoice) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                Detail
                            </a>
                            <a href="{{ route('invoices.edit', $invoice) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                Edit
                            </a>
                            <a href="{{ route('invoices.send', $invoice) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                Send
                            </a>
                            <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                                PDF
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada invoice.
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($invoices->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </x-card>

</x-layouts.app>