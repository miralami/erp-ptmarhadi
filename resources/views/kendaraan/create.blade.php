<x-layouts.app>
    <x-slot:title>Tambah Kendaraan</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('kendaraan.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Kendaraan
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Tambah Kendaraan Baru</h1>
        <p class="mt-1 text-sm text-slate-500">Isi form berikut untuk menambahkan data kendaraan</p>
    </div>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('kendaraan.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="brand" class="block text-sm font-medium text-slate-700 mb-1.5">Brand</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}" required
                               placeholder="Contoh: Mitsubishi"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('brand')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="model" class="block text-sm font-medium text-slate-700 mb-1.5">Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}" required
                               placeholder="Contoh: Colt Diesel"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('model')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="plate_number" class="block text-sm font-medium text-slate-700 mb-1.5">Plat Nomor</label>
                    <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number') }}" required
                           placeholder="Contoh: B 1234 ABC"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('plate_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1.5">Tipe Kendaraan</label>
                        <select name="type" id="type" required
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="">Pilih Tipe</option>
                            @foreach ($types as $t)
                                <option value="{{ $t->value }}" {{ old('type') === $t->value ? 'selected' : '' }}>{{ $t->label() }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="ACTIVE" {{ old('status', 'ACTIVE') === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                            <option value="INACTIVE" {{ old('status') === 'INACTIVE' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
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
                        Simpan Kendaraan
                    </button>
                    <a href="{{ route('kendaraan.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>

</x-layouts.app>
