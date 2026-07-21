<x-layouts.app>
    <x-slot:title>Pengaturan Perusahaan</x-slot:title>

    <x-page-header title="Pengaturan Perusahaan" description="Kelola data perusahaan untuk dokumen dan laporan" />

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('company-settings.update') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="company_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Perusahaan</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings['company_name']) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="npwp" class="block text-sm font-medium text-slate-700 mb-1.5">NPWP Perusahaan</label>
                    <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $settings['npwp']) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('npwp')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition resize-none">{{ old('address', $settings['address']) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $settings['phone']) }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $settings['email']) }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-slate-200">

                <h3 class="text-lg font-medium text-slate-900">Informasi Bank</h3>

                <div>
                    <label for="bank_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Bank</label>
                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $settings['bank_name']) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('bank_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="bank_account" class="block text-sm font-medium text-slate-700 mb-1.5">No. Rekening</label>
                        <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $settings['bank_account']) }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('bank_account')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="bank_branch" class="block text-sm font-medium text-slate-700 mb-1.5">Cabang</label>
                        <input type="text" name="bank_branch" id="bank_branch" value="{{ old('bank_branch', $settings['bank_branch']) }}"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('bank_branch')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-slate-200">

                <h3 class="text-lg font-medium text-slate-900">Penandatangan</h3>

                <div>
                    <label for="signature_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Penandatangan</label>
                    <input type="text" name="signature_name" id="signature_name" value="{{ old('signature_name', $settings['signature_name']) }}"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('signature_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </x-card>
    </div>

</x-layouts.app>
