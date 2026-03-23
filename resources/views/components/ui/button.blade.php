@props([
    'type'    => 'submit',
    'label'   => 'Submit',
    'variant' => 'primary',
    'size'    => 'md',
    'href'    => null,
])

@php
$base = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'primary'   => 'bg-primary text-white hover:bg-green-900 focus:ring-primary',
    'accent'    => 'bg-accent text-white hover:bg-amber-700 focus:ring-accent',
    'secondary' => 'bg-white text-textmain border border-slate-200 hover:bg-slate-50 focus:ring-slate-300',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'ghost'     => 'text-primary hover:bg-primary/5 focus:ring-primary',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-2.5 text-sm',
];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot->isNotEmpty() ? $slot : $label }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot->isNotEmpty() ? $slot : $label }}
    </button>
@endif
