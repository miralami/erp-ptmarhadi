<x-layouts.app>
    <x-slot:title>Tambah Pembayaran</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Pembayaran
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Tambah Pembayaran Baru</h1>
        <p class="mt-1 text-sm text-slate-500">Catat penerimaan pembayaran dari invoice</p>
    </div>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="invoice_id" class="block text-sm font-medium text-slate-700 mb-1.5">Invoice</label>
                    <select name="invoice_id" id="invoice_id" required
                            x-data
                            x-on:change="
                                let opt = $event.target.selectedOptions[0];
                                $refs.remainingAmount.textContent = opt.dataset.remaining || 'Rp 0';
                            "
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Invoice</option>
                        @foreach ($invoices as $id => $label)
                            <option value="{{ $id }}" data-remaining="Rp {{ number_format($remaining[$id] ?? 0, 0, ',', '.') }}" {{ request('invoice_id') == $id ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-slate-500">
                        Sisa tagihan: <span x-ref="remainingAmount" class="font-medium text-slate-700">
                            @if (request('invoice_id') && isset($remaining[request('invoice_id')]))
                                Rp {{ number_format($remaining[request('invoice_id')], 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </span>
                    </p>
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Pembayaran</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="0.01"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-1.5">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">Pilih Metode</option>
                        @foreach (\App\Enums\PaymentMethod::cases() as $method)
                            <option value="{{ $method->value }}" {{ old('payment_method') === $method->value ? 'selected' : '' }}>
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
                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number') }}"
                           placeholder="Contoh: BCA/0123456"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('reference_number')
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
                        Simpan Pembayaran
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