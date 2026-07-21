<div class="relative">
    @foreach ($steps as $index => $step)
        <div class="flex items-start gap-4 pb-8 last:pb-0 relative">
            <div class="flex flex-col items-center">
                <div @class([
                    'w-9 h-9 rounded-full flex items-center justify-center ring-4 ring-white z-10 transition-all duration-300',
                    'bg-emerald-500' => $step['status'] === 'completed',
                    'bg-indigo-500' => $step['status'] === 'active',
                    'bg-slate-200' => $step['status'] === 'pending',
                ])>
                    @if ($step['status'] === 'completed')
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    @elseif ($step['status'] === 'active')
                        <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                    @else
                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                    @endif
                </div>
                @if (!$loop->last)
                    <div @class([
                        'w-0.5 h-8 -mt-0.5',
                        'bg-emerald-300' => $step['status'] === 'completed',
                        'bg-indigo-300' => $step['status'] === 'active',
                        'bg-slate-200' => $step['status'] === 'pending',
                    ])></div>
                @endif
            </div>
            <div class="pt-1.5">
                <p @class([
                    'text-sm font-medium transition-colors',
                    'text-slate-900' => $step['status'] === 'completed' || $step['status'] === 'active',
                    'text-slate-400' => $step['status'] === 'pending',
                ])>
                    {{ $step['label'] }}
                </p>
                @if ($step['status'] === 'active')
                    <span class="inline-flex items-center gap-1 mt-1 text-xs font-medium text-indigo-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        Sedang berlangsung
                    </span>
                @endif
            </div>
        </div>
    @endforeach
</div>
