<x-layouts.app>
    <x-slot:title>Tambah Invoice</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Invoice
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Tambah Invoice Baru</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih order untuk membuat invoice</p>
    </div>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('invoices.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="order_id" class="block text-sm font-medium text-slate-700 mb-1.5">Order</label>
                    <select name="order_id" id="order_id" required
                            x-data
                            x-on:change="
                                fetch(`/orders/${$event.target.value}/items`)
                                    .then(r => r.json())
                                    .then(data => $refs.itemsPreview.innerHTML = renderItems(data))
                                    .catch(() => $refs.itemsPreview.innerHTML = '')
                            "
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Order</option>
                        @foreach ($orders as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div x-ref="itemsPreview" class="hidden"></div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="invoice_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tgl Invoice</label>
                        <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('invoice_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-slate-700 mb-1.5">Jatuh Tempo</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Catatan <span class="text-slate-400">(opsional)</span></label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition resize-none"
                              placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Simpan Invoice
                    </button>
                    <a href="{{ route('invoices.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
    <script>
        function renderItems(data) {
            if (!data.items || !data.items.length) return '';
            let html = `
                <div class="bg-slate-50 rounded-xl p-4 mb-2">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Preview Item Order</p>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-slate-500 uppercase">
                                <th class="pb-2 pr-2">Barang</th>
                                <th class="pb-2 pr-2">Qty</th>
                                <th class="pb-2 pr-2">Harga</th>
                                <th class="pb-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
            `;
            data.items.forEach(item => {
                html += `
                    <tr>
                        <td class="py-2 pr-2 font-medium text-slate-900">${item.product_name}</td>
                        <td class="py-2 pr-2 text-slate-600">${item.quantity}</td>
                        <td class="py-2 pr-2 text-slate-600">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                        <td class="py-2 text-right font-medium text-slate-900">Rp ${Number(item.quantity * item.price).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
            html += `
                        </tbody>
                    </table>
                    <p class="text-right text-sm font-semibold text-slate-900 mt-3 pt-3 border-t border-slate-200">
                        Total: Rp ${Number(data.total).toLocaleString('id-ID')}
                    </p>
                </div>
            `;
            return html;
        }

        document.addEventListener('alpine:init', () => {
            const sel = document.getElementById('order_id');
            if (sel.value) {
                fetch(`/orders/${sel.value}/items`)
                    .then(r => r.json())
                    .then(data => {
                        if (document.querySelector('[x-ref="itemsPreview"]')) {
                            document.querySelector('[x-ref="itemsPreview"]').innerHTML = renderItems(data);
                            document.querySelector('[x-ref="itemsPreview"]').classList.remove('hidden');
                        }
                    })
                    .catch(() => {});
            }
        });
    </script>
    @endpush

</x-layouts.app>