<x-layouts.app>
    <x-slot:title>Edit Pembayaran</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Pembayaran
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Edit Pembayaran</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi pembayaran {{ $payment->payment_number }}</p>
    </div>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="invoice_id" class="block text-sm font-medium text-slate-700 mb-1.5">Invoice</label>
                    <select name="invoice_id" id="invoice_id" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @foreach ($invoices as $id => $label)
                            <option value="{{ $id }}" {{ $payment->invoice_id == $id ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Pembayaran</label>
                    <input type="date" name="payment_date" id="payment_date"
                           value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah</label>
                    <input type="number" name="amount" id="amount"
                           value="{{ old('amount', $payment->amount) }}" required min="0" step="0.01"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-1.5">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @foreach (\App\Enums\PaymentMethod::cases() as $method)
                            <option value="{{ $method->value }}" {{ $payment->payment_method === $method ? 'selected' : '' }}>
                                {{ $method->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reference_number" class="block text-sm font-medium text-slate-700 mb-1.5">No. Referensi <span class="text-slate-400">(opsional)</span></label>
                    <input type="text" name="reference_number" id="reference_number"
                           value="{{ old('reference_number', $payment->reference_number) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('reference_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Catatan <span class="text-slate-400">(opsional)</span></label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition resize-none">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Update Pembayaran
                    </button>
                    <a href="{{ route('payments.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>

</x-layouts.app>