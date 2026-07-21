<x-layouts.app>
    <x-slot:title>Buat SP Baru</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('surat-pengiriman.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke SP
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Buat Surat Pengiriman Baru</h1>
        <p class="mt-1 text-sm text-slate-500">Isi form berikut untuk membuat surat pengiriman baru</p>
    </div>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('surat-pengiriman.store') }}" class="space-y-6">
                @csrf

                <h3 class="text-lg font-medium text-slate-900">Informasi Order</h3>

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-1.5">Customer</label>
                    <select name="customer_id" id="customer_id" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Customer</option>
                        @foreach (\App\Models\Customer::orderBy('company_name')->get() as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->company_name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal SP</label>
                        <input type="date" name="order_date" id="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}" required
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('order_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="received_by" class="block text-sm font-medium text-slate-700 mb-1.5">Diterima Oleh</label>
                        <input type="text" name="received_by" id="received_by" value="{{ old('received_by') }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('received_by')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="origin_company" class="block text-sm font-medium text-slate-700 mb-1.5">Asal (Perusahaan)</label>
                        <input type="text" name="origin_company" id="origin_company" value="{{ old('origin_company') }}"
                               placeholder="PT Marhadi"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('origin_company')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="origin_city" class="block text-sm font-medium text-slate-700 mb-1.5">Kota Asal</label>
                        <input type="text" name="origin_city" id="origin_city" value="{{ old('origin_city') }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('origin_city')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="destination_city" class="block text-sm font-medium text-slate-700 mb-1.5">Kota Tujuan</label>
                        <input type="text" name="destination_city" id="destination_city" value="{{ old('destination_city') }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('destination_city')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                        <select name="category" id="category"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->value }}" {{ old('category') === $c->value ? 'selected' : '' }}>{{ $c->label() }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="customer_po_number" class="block text-sm font-medium text-slate-700 mb-1.5">No. PO Customer</label>
                        <input type="text" name="customer_po_number" id="customer_po_number" value="{{ old('customer_po_number') }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('customer_po_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="customer_spb_number" class="block text-sm font-medium text-slate-700 mb-1.5">No. SPB Customer</label>
                        <input type="text" name="customer_spb_number" id="customer_spb_number" value="{{ old('customer_spb_number') }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('customer_spb_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="vehicle_source" class="block text-sm font-medium text-slate-700 mb-1.5">Sumber Kendaraan</label>
                    <select name="vehicle_source" id="vehicle_source"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Sumber</option>
                        @foreach ($vehicleSources as $vs)
                            <option value="{{ $vs->value }}" {{ old('vehicle_source') === $vs->value ? 'selected' : '' }}>{{ $vs->label() }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_source')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-slate-200">

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-slate-900">Item Barang</h3>
                    <button type="button" id="add-item"
                            class="px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                        + Tambah Item
                    </button>
                </div>

                <div id="items-container" class="space-y-4">
                    <div class="item-row border border-slate-200 rounded-xl p-4 space-y-3">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-4">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Nama Barang</label>
                                <input type="text" name="items[0][product_name]" required
                                       class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Unit</label>
                                <input type="number" name="items[0][unit]" min="0"
                                       class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Kubikasi</label>
                                <input type="number" name="items[0][kubikasi]" min="0" step="0.01"
                                       class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Harga</label>
                                <input type="number" name="items[0][price]" required min="0"
                                       class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Max Slot</label>
                                <input type="number" name="items[0][max_slot]" min="0"
                                       class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-4">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Biaya Polisi</label>
                                <input type="number" name="items[0][police_fee]" min="0" value="0"
                                       class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            </div>
                            <div class="col-span-8 flex items-end">
                                <button type="button" class="remove-item text-xs text-red-500 hover:text-red-700">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Catatan</label>
                    <textarea name="notes" id="notes" rows="2"
                              class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition resize-none">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Simpan SP
                    </button>
                    <a href="{{ route('surat-pengiriman.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const template = container.querySelector('.item-row').cloneNode(true);
            template.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name').replace(/\[\d+\]/, `[${itemIndex}]`);
                input.setAttribute('name', name);
                input.value = '';
            });
            container.appendChild(template);
            itemIndex++;
        });

        document.getElementById('items-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                const row = e.target.closest('.item-row');
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                }
            }
        });
    </script>

</x-layouts.app>
