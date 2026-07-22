<x-layouts.app>
    <x-slot:title>Detail SP {{ $order->order_number }}</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('surat-pengiriman.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke SP
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-semibold text-slate-900">{{ $order->order_number }}</h1>
                <x-badge :label="$order->status->label()" :color="$order->status->color()" />
                @if ($order->category)
                    <x-badge :label="$order->category->label()" :color="$order->category->color()" />
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @php $transitions = \App\Enums\OrderStatus::allowedTransitions()[$order->status->value] ?? [] @endphp
                @if (count($transitions) > 0)
                    <form method="POST" action="{{ route('surat-pengiriman.update-status', $order) }}" class="flex items-center gap-2">
                        @csrf
                        <select name="status" required class="px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            <option value="">Update status ke...</option>
                            @foreach ($transitions as $t)
                                <option value="{{ $t->value }}">{{ $t->label() }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            Simpan
                        </button>
                    </form>
                    <div class="hidden sm:block h-6 w-px bg-slate-200 mx-1"></div>
                @endif

                <a href="{{ route('surat-pengiriman.edit', $order) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 text-sm font-medium rounded-lg border border-slate-200 hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Order
                </a>
                <a href="{{ route('surat-pengiriman.cetak', $order) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 text-sm font-medium rounded-lg border border-slate-200 hover:bg-slate-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Detail Order</h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Customer</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->customer->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Diterima Oleh</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->received_by ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. PO Customer</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->customer_po_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">No. SPB Customer</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->customer_spb_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Asal</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->origin_company ?? '-' }} ({{ $order->origin_city ?? '-' }})</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tujuan</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->destination_city ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sumber Kendaraan</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->vehicle_source?->label() ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</p>
                        <p class="text-sm text-slate-900 mt-0.5">{{ $order->category?->label() ?? '-' }}</p>
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-slate-900 mt-6 mb-3">Item Barang</h3>
                <x-table :headers="['Nama Barang', 'Unit', 'Kubikasi', 'Harga', 'Subtotal', 'Biaya Polisi', 'Max Slot']">
                    @foreach ($order->items as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-sm text-slate-900">{{ $item->product_name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->unit }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->kubikasi ? number_format($item->kubikasi, 2) : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-900 font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->police_fee ? 'Rp ' . number_format($item->police_fee, 0, ',', '.') : '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->max_slot ?? '-' }}</td>
                        </tr>
                    @endforeach
                </x-table>

                <div class="flex justify-end mt-4">
                    <div class="text-right">
                        <p class="text-sm text-slate-500">Total: <span class="text-lg font-semibold text-slate-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                    </div>
                </div>
            </x-card>

            <x-card x-data="{ vehicleSource: '{{ $order->vehicle_source?->value ?? 'OWNED' }}' }">
                <h2 class="text-base font-semibold text-slate-900 mb-4">Detail Pengiriman</h2>
                <form method="POST" action="{{ route('surat-pengiriman.update-delivery', $order) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Driver</label>
                            <input type="text" name="driver_name" value="{{ old('driver_name', $order->delivery?->driver_name) }}"
                                   placeholder="Nama driver"
                                   class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Sumber Kendaraan</label>
                            <select x-model="vehicleSource" class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                <option value="OWNED">Milik (Marhadi)</option>
                                <option value="RENTED">Sewa</option>
                            </select>
                        </div>
                    </div>
                    <div x-show="vehicleSource === 'OWNED'" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Kendaraan</label>
                            <select name="vehicle_id"
                                    class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                <option value="">Pilih Kendaraan</option>
                                @foreach ($vehicles as $v)
                                    <option value="{{ $v->id }}" {{ old('vehicle_id', $order->delivery?->vehicle_id) == $v->id ? 'selected' : '' }}>
                                        {{ $v->plate_number }} - {{ $v->brand }} {{ $v->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div x-show="vehicleSource === 'RENTED'" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Plat No</label>
                            <input type="text" name="vehicle_plate_manual" value="{{ old('vehicle_plate_manual', $order->delivery?->vehicle_plate_manual) }}"
                                   placeholder="Nomor plat kendaraan"
                                   class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipe Kendaraan</label>
                            <select name="vehicle_type_manual"
                                    class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                <option value="">Pilih Tipe</option>
                                @foreach (\App\Enums\VehicleType::cases() as $vt)
                                    <option value="{{ $vt->value }}" {{ old('vehicle_type_manual', $order->delivery?->vehicle_type_manual) === $vt->value ? 'selected' : '' }}>{{ $vt->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Uang Jalan</label>
                        <input type="number" name="uang_jalan" value="{{ old('uang_jalan', $order->delivery?->uang_jalan) }}" min="0"
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-slate-700">Rincian Biaya</label>
                            <button type="button" id="add-expense" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">+ Tambah</button>
                        </div>
                        <div id="expenses-container" class="space-y-2">
                            @forelse ($order->delivery?->expenses ?? [] as $i => $expense)
                                <div class="expense-row flex gap-2 items-start">
                                    <input type="text" name="expenses[{{ $i }}][description]" value="{{ $expense->description }}" placeholder="Keterangan"
                                           class="flex-1 px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg">
                                    <input type="number" name="expenses[{{ $i }}][amount]" value="{{ $expense->amount }}" placeholder="Jumlah" min="0"
                                           class="w-32 px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg">
                                    <button type="button" class="remove-expense text-xs text-red-500 hover:text-red-700 mt-2">Hapus</button>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400" id="no-expenses">Belum ada rincian biaya</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                            Simpan Pengiriman
                        </button>
                    </div>
                </form>

                <script>
                    let expenseIndex = {{ $order->delivery?->expenses->count() ?? 0 }};
                    document.getElementById('add-expense')?.addEventListener('click', function() {
                        const container = document.getElementById('expenses-container');
                        const noMsg = document.getElementById('no-expenses');
                        if (noMsg) noMsg.remove();
                        const div = document.createElement('div');
                        div.className = 'expense-row flex gap-2 items-start';
                        div.innerHTML = `
                            <input type="text" name="expenses[${expenseIndex}][description]" placeholder="Keterangan"
                                   class="flex-1 px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg">
                            <input type="number" name="expenses[${expenseIndex}][amount]" placeholder="Jumlah" min="0"
                                   class="w-32 px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg">
                            <button type="button" class="remove-expense text-xs text-red-500 hover:text-red-700 mt-2">Hapus</button>
                        `;
                        container.appendChild(div);
                        expenseIndex++;
                    });
                    document.getElementById('expenses-container')?.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-expense')) {
                            e.target.closest('.expense-row').remove();
                        }
                    });
                </script>
            </x-card>

            @if ($order->invoice)
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Invoice</h2>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">No. Invoice: <a href="{{ route('invoices.show', $order->invoice) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">{{ $order->invoice->invoice_number }}</a></p>
                        <p class="text-sm text-slate-600 mt-1">
                            Total: Rp {{ number_format($order->invoice->invoice_total, 0, ',', '.') }}
                            <x-badge :label="$order->invoice->status->label()" :color="$order->invoice->status->color()" class="ml-2" />
                        </p>
                    </div>
                </div>
            </x-card>
            @endif
        </div>

        <div class="lg:col-span-1 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Status Progres</h2>
                <x-timeline :status="$order->status" />
            </x-card>

            @if ($order->delivery)
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Dokumentasi</h2>
                <div class="space-y-3">
                    @php
                        $photoTypes = [
                            'photo_muat' => 'Muat',
                            'photo_bongkar' => 'Bongkar',
                            'photo_surat_jalan' => 'Surat Jalan',
                        ];
                        $icons = [
                            'photo_muat' => '⬆',
                            'photo_bongkar' => '⬇',
                            'photo_surat_jalan' => '📄',
                        ];
                    @endphp
                    @foreach ($photoTypes as $key => $label)
                        <div class="relative pl-8 pb-3 border-l-2 border-slate-200 last:border-l-2 last:pb-0">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-2 border-indigo-500 flex items-center justify-center">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-slate-700">{{ $label }}</h3>
                                <form method="POST" action="{{ route('surat-pengiriman.upload-photos', $order) }}" enctype="multipart/form-data" class="flex items-center gap-1.5">
                                    @csrf
                                    <input type="hidden" name="photo_type" value="{{ $key }}">
                                    <input type="file" name="photos[]" multiple accept="image/*"
                                           class="text-[10px] text-slate-500 file:mr-1 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer w-24">
                                    <button type="submit" class="px-2 py-0.5 text-[10px] font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 transition">
                                        Upload
                                    </button>
                                </form>
                            </div>
                            @if ($order->delivery->$key)
                                <div class="flex gap-2 flex-wrap">
                                    @foreach ($order->delivery->$key as $photo)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($photo) }}" target="_blank" class="block group overflow-hidden rounded-lg border border-slate-200 w-20 h-20">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $label }}">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[11px] text-slate-400 italic">Belum ada foto</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-card>
            @endif

            @if ($order->invoice)
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Ringkasan Keuangan</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tagihan (Invoice)</span>
                        <span class="font-medium text-slate-900">Rp {{ number_format($order->invoice->invoice_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Uang Jalan</span>
                        <span class="font-medium text-slate-900">Rp {{ number_format($order->delivery?->uang_jalan ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Biaya Operasional</span>
                        <span class="font-medium text-slate-900">Rp {{ number_format($order->delivery?->expenses->sum('amount') ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-slate-200">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-slate-700">Nett</span>
                        @php
                            $totalBiaya = ($order->delivery?->uang_jalan ?? 0) + ($order->delivery?->expenses->sum('amount') ?? 0);
                            $nett = ($order->invoice->invoice_total ?? 0) - $totalBiaya;
                        @endphp
                        <span class="{{ $nett >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            Rp {{ number_format($nett, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </x-card>
            @endif

            @if ($order->notes)
            <x-card>
                <h2 class="text-sm font-semibold text-slate-900 mb-2">Catatan</h2>
                <p class="text-sm text-slate-600">{{ $order->notes }}</p>
            </x-card>
            @endif
        </div>
    </div>

</x-layouts.app>
