<x-layouts.app>
    <x-slot:title>Edit Pengiriman</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('deliveries.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Pengiriman
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Edit Pengiriman</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi pengiriman {{ $delivery->delivery_number }}</p>
    </div>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('deliveries.update', $delivery) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="order_id" class="block text-sm font-medium text-slate-700 mb-1.5">Order</label>
                    <select name="order_id" id="order_id" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Order</option>
                        @foreach ($orders as $orderOption)
                            <option value="{{ $orderOption->id }}" {{ old('order_id', $delivery->order_id) == $orderOption->id ? 'selected' : '' }}>
                                {{ $orderOption->order_number }} — {{ $orderOption->customer?->company_name ?? 'Tanpa Customer' }}
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delivery_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Pengiriman</label>
                    <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $delivery->delivery_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('delivery_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="driver_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Driver <span class="text-slate-400">(opsional)</span></label>
                        <input type="text" name="driver_name" id="driver_name" value="{{ old('driver_name', $delivery->driver_name) }}"
                               placeholder="Nama sopir"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('driver_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="vehicle_number" class="block text-sm font-medium text-slate-700 mb-1.5">No. Kendaraan <span class="text-slate-400">(opsional)</span></label>
                        <input type="text" name="vehicle_number" id="vehicle_number" value="{{ old('vehicle_number', $delivery->vehicle_number) }}"
                               placeholder="Contoh: B 1234 XYZ"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('vehicle_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="delivery_note_number" class="block text-sm font-medium text-slate-700 mb-1.5">No. Surat Jalan <span class="text-slate-400">(opsional)</span></label>
                    <input type="text" name="delivery_note_number" id="delivery_note_number" value="{{ old('delivery_note_number', $delivery->delivery_note_number) }}"
                           placeholder="Nomor surat jalan"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('delivery_note_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Barang</label>
                        <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $delivery->product_name) }}" required
                               placeholder="Contoh: Beras Premium 5kg"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('product_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $delivery->quantity) }}" required min="1"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @foreach (\App\Enums\DeliveryStatus::cases() as $s)
                            <option value="{{ $s->value }}" {{ old('status', $delivery->status->value) === $s->value ? 'selected' : '' }}>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Catatan <span class="text-slate-400">(opsional)</span></label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition resize-none"
                              placeholder="Catatan tambahan...">{{ old('notes', $delivery->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Update Pengiriman
                    </button>
                    <a href="{{ route('deliveries.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>

</x-layouts.app>
