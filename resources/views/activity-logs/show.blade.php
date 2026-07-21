<x-layouts.app>
    <x-slot:title>Detail Activity Log</x-slot:title>

    <div class="mb-8">
        <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Activity Logs
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Detail Activity Log</h1>
        <p class="mt-1 text-sm text-slate-500">Informasi lengkap perubahan data</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Informasi Aktivitas</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">User</p>
                        <p class="text-sm font-medium text-slate-900 mt-0.5">{{ $log->user?->name ?? 'System' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Module</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $log->module }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Action</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $log->action }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Deskripsi</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $log->description ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $log->created_at->format('d F Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Record ID</p>
                        <p class="text-sm text-slate-700 mt-0.5">{{ $log->record_id ?? '-' }}</p>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-3 space-y-6">
            @if ($log->old_value)
                <x-card>
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Data Lama</h2>
                    <div class="space-y-2">
                        @foreach ($log->old_value as $key => $value)
                            <div class="flex justify-between items-start py-1.5 border-b border-slate-100 last:border-0">
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[120px]">{{ $key }}</span>
                                <span class="text-sm text-slate-900 text-right ml-4 break-all">
                                    @if (is_null($value))
                                        <span class="text-slate-400 italic">null</span>
                                    @elseif (is_bool($value))
                                        {{ $value ? 'true' : 'false' }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @endif

            @if ($log->new_value)
                <x-card>
                    <h2 class="text-base font-semibold text-slate-900 mb-4">Data Baru</h2>
                    <div class="space-y-2">
                        @foreach ($log->new_value as $key => $value)
                            <div class="flex justify-between items-start py-1.5 border-b border-slate-100 last:border-0">
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider min-w-[120px]">{{ $key }}</span>
                                <span class="text-sm text-slate-900 text-right ml-4 break-all">
                                    @if (is_null($value))
                                        <span class="text-slate-400 italic">null</span>
                                    @elseif (is_bool($value))
                                        {{ $value ? 'true' : 'false' }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @endif

            @if (!$log->old_value && !$log->new_value)
                <x-card>
                    <p class="text-sm text-slate-500 text-center py-4">Tidak ada data perubahan yang tersedia.</p>
                </x-card>
            @endif
        </div>
    </div>

</x-layouts.app>