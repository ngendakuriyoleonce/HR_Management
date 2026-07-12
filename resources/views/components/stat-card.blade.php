@props(['label', 'value', 'color' => 'gray', 'icon' => null])

@php
    $iconBg = match($color) {
        'emerald' => 'bg-emerald-100 dark:bg-emerald-900/30',
        'amber' => 'bg-amber-100 dark:bg-amber-900/30',
        'red' => 'bg-red-100 dark:bg-red-900/30',
        'blue' => 'bg-blue-100 dark:bg-blue-900/30',
        'purple' => 'bg-purple-100 dark:bg-purple-900/30',
        default => 'bg-gray-100 dark:bg-gray-800',
    };

    $iconColor = match($color) {
        'emerald' => 'text-emerald-600 dark:text-emerald-400',
        'amber' => 'text-amber-600 dark:text-amber-400',
        'red' => 'text-red-600 dark:text-red-400',
        'blue' => 'text-blue-600 dark:text-blue-400',
        'purple' => 'text-purple-600 dark:text-purple-400',
        default => 'text-gray-600 dark:text-gray-400',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-white/[0.03] sm:p-4']) }}>
    <div class="flex items-center gap-2 sm:gap-3">
        @if ($icon)
            <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $iconBg }} sm:h-11 sm:w-11">
                <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
        @endif
        <div>
            <p class="text-xs text-gray-500 dark:text-gray-400 sm:text-sm">{{ $label }}</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white/90 sm:text-2xl">{{ $value }}</p>
        </div>
    </div>
</div>
