<x-layouts.app>
    <x-slot:title>Tambah Order</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Order
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Tambah Order Baru</h1>
        <p class="mt-1 text-sm text-slate-500">Isi form berikut untuk menambahkan order baru</p>
    </div>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('orders.store') }}" class="space-y-6" x-data="{
                items: [{ product_name: '', quantity: 1, price: 0 }],
                get total() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0), 0);
                }
            }">
                @csrf

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-1.5">Customer</label>
                    <select name="customer_id" id="customer_id" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Customer</option>
                        @foreach ($customers as $id => $name)
                            <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Order</label>
                    <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('order_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-slate-700">Item Barang</label>
                        <button type="button" @click="items.push({ product_name: '', quantity: 1, price: 0 })"
                                class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                <div class="flex-1">
                                    <input type="text" :name="`items[${index}][product_name]`" x-model="item.product_name" required
                                           placeholder="Nama barang"
                                           class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div class="w-24">
                                    <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" required min="1"
                                           placeholder="Qty"
                                           class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div class="w-32">
                                    <input type="number" :name="`items[${index}][price]`" x-model="item.price" required min="0" step="0.01"
                                           placeholder="Harga"
                                           class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div class="w-24 pt-2 text-sm font-medium text-slate-700 text-right">
                                    <span x-text="'Rp ' + ((parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0)).toLocaleString('id-ID')"></span>
                                </div>
                                <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1"
                                        class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end pt-2 text-sm font-semibold text-slate-900">
                        Total: <span class="ml-2" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                    </div>

                    @error('items')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error('items.*.product_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
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
                        Simpan Order
                    </button>
                    <a href="{{ route('orders.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>

</x-layouts.app>
