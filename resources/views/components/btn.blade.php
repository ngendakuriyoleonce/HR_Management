@props(['color' => 'emerald', 'type' => 'button', 'icon' => null])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-theme-xs transition-colors';

    $colors = match($color) {
        'emerald' => 'bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700',
        'red' => 'bg-red-500 hover:bg-red-600 active:bg-red-700',
        'blue' => 'bg-blue-500 hover:bg-blue-600 active:bg-blue-700',
        'amber' => 'bg-amber-500 hover:bg-amber-600 active:bg-amber-700',
        default => 'bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$base $colors"]) }}>
    @if ($icon)
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif
    {{ $slot }}
</button>
