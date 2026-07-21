@php
$colors = [
    'blue' => 'bg-blue-100 text-blue-700 ring-blue-700/10',
    'indigo' => 'bg-indigo-100 text-indigo-700 ring-indigo-700/10',
    'cyan' => 'bg-cyan-100 text-cyan-700 ring-cyan-700/10',
    'purple' => 'bg-purple-100 text-purple-700 ring-purple-700/10',
    'amber' => 'bg-amber-100 text-amber-700 ring-amber-700/10',
    'teal' => 'bg-teal-100 text-teal-700 ring-teal-700/10',
    'sky' => 'bg-sky-100 text-sky-700 ring-sky-700/10',
    'red' => 'bg-red-100 text-red-700 ring-red-700/10',
    'emerald' => 'bg-emerald-100 text-emerald-700 ring-emerald-700/10',
    'orange' => 'bg-orange-100 text-orange-700 ring-orange-700/10',
    'slate' => 'bg-slate-100 text-slate-700 ring-slate-700/10',
    'gray' => 'bg-gray-100 text-gray-700 ring-gray-700/10',
];
$class = $colors[$color] ?? 'bg-slate-100 text-slate-700 ring-slate-700/10';
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $class }}">
    {{ $label }}
</span>
