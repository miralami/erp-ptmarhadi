@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => 'text-blue-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'icon' => 'text-indigo-600'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => 'text-amber-600'],
        'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'icon' => 'text-rose-600'],
        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'icon' => 'text-red-600'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => 'text-emerald-600'],
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'icon' => 'text-purple-600'],
        'teal' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'icon' => 'text-teal-600'],
    ];
    $c = $colors[$color] ?? $colors['indigo'];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} flex items-center justify-center">
            <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if ($icon === 'shopping-cart')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                @elseif ($icon === 'clock')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                @elseif ($icon === 'file-text')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                @elseif ($icon === 'alert-circle')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                @elseif ($icon === 'check-circle')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                @endif
            </svg>
        </div>
        <span class="text-3xl font-bold {{ $c['text'] }}">{{ $value }}</span>
    </div>
    <h3 class="text-sm font-medium text-slate-700">{{ $title }}</h3>
    @if ($description)
        <p class="text-xs text-slate-500 mt-1">{{ $description }}</p>
    @endif
</div>
