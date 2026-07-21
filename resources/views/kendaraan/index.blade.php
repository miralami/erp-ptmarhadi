<x-layouts.app>
    <x-slot:title>Kendaraan</x-slot:title>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kendaraan</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data kendaraan perusahaan</p>
        </div>
        <a href="{{ route('kendaraan.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kendaraan
        </a>
    </div>

    <x-card class="!p-0">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" action="{{ route('kendaraan.index') }}" class="flex gap-3">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari plat, brand, atau model..."
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
                <select name="status" class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Semua Status</option>
                    <option value="ACTIVE" {{ $status === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                    <option value="INACTIVE" {{ $status === 'INACTIVE' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <select name="type" class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Semua Tipe</option>
                    @foreach ($types as $t)
                        <option value="{{ $t->value }}" {{ $type === $t->value ? 'selected' : '' }}>{{ $t->label() }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">Cari</button>
                @if ($search || $status || $type)
                    <a href="{{ route('kendaraan.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Reset</a>
                @endif
            </form>
        </div>

        <x-table :headers="['Plat Nomor', 'Brand', 'Model', 'Tipe', 'Status', 'Aksi']">
            @forelse ($vehicles as $vehicle)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        {{ $vehicle->plate_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $vehicle->brand }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $vehicle->model }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :label="$vehicle->type->label()" :color="$vehicle->type->color()" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :label="$vehicle->status === 'ACTIVE' ? 'Aktif' : 'Nonaktif'" :color="$vehicle->status === 'ACTIVE' ? 'emerald' : 'slate'" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('kendaraan.edit', $vehicle) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                                Edit
                            </a>
                            @if ($vehicle->status === 'ACTIVE')
                                <form method="POST" action="{{ route('kendaraan.destroy', $vehicle) }}" onsubmit="return confirm('Nonaktifkan kendaraan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                        Nonaktifkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada kendaraan.
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($vehicles->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $vehicles->links() }}
            </div>
        @endif
    </x-card>

</x-layouts.app>
