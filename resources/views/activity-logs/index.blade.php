<x-layouts.app>
    <x-slot:title>Activity Logs</x-slot:title>

    <x-page-header title="Activity Logs" description="Riwayat aktivitas dan perubahan data" />

    <x-card class="!p-0">
        <div class="p-4 border-b border-slate-100">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari aktivitas..."
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
                <select name="module"
                        class="px-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Semua Module</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                            {{ $module }}
                        </option>
                    @endforeach
                </select>
                <select name="action"
                        class="px-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">Semua Aksi</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">Cari</button>
                @if ($search || request('module') || request('action'))
                    <a href="{{ route('activity-logs.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Reset</a>
                @endif
            </form>
        </div>

        <x-table :headers="['User', 'Module', 'Action', 'Deskripsi', 'Waktu']">
            @forelse ($logs as $log)
                <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="window.location='{{ route('activity-logs.show', $log) }}'">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        {{ $log->user?->name ?? 'System' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $log->module }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset
                            @php
                                $actionColors = [
                                    'created' => 'bg-emerald-100 text-emerald-700 ring-emerald-700/10',
                                    'updated' => 'bg-blue-100 text-blue-700 ring-blue-700/10',
                                    'deleted' => 'bg-red-100 text-red-700 ring-red-700/10',
                                ];
                            @endphp
                            {{ $actionColors[strtolower($log->action)] ?? 'bg-slate-100 text-slate-700 ring-slate-700/10' }}
                        ">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 max-w-xs truncate">
                        {{ $log->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ $log->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                        Belum ada aktivitas.
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </x-card>

</x-layouts.app>