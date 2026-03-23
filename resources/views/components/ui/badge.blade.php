@props(['color' => 'gray', 'label' => ''])

@php
$colors = [
    'green'  => 'bg-green-100 text-green-800',
    'amber'  => 'bg-amber-100 text-amber-800',
    'red'    => 'bg-red-100 text-red-800',
    'blue'   => 'bg-blue-100 text-blue-800',
    'gray'   => 'bg-slate-100 text-slate-700',
    'purple' => 'bg-purple-100 text-purple-800',
];
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $colors[$color] ?? $colors['gray'] }}">
    {{ $label ?: $slot }}
</span>
