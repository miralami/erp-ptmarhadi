<x-layouts.app>
    <x-slot:title>Edit Order</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Order
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Edit Order</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi order {{ $order->order_number }}</p>
    </div>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('orders.update', $order) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-1.5">Customer</label>
                    <select name="customer_id" id="customer_id" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Customer</option>
                        @foreach ($customers as $id => $name)
                            <option value="{{ $id }}" {{ old('customer_id', $order->customer_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Order</label>
                    <input type="date" name="order_date" id="order_date" value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('order_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Catatan <span class="text-slate-400">(opsional)</span></label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition resize-none"
                              placeholder="Catatan tambahan...">{{ old('notes', $order->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Update Order
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
