@props(['color' => 'gray'])

@php
    $colors = match($color) {
        'gray' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
        'emerald' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
        default => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
    };
@endphp

<a {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-medium transition-colors $colors"]) }}>
    {{ $slot }}
</a>
