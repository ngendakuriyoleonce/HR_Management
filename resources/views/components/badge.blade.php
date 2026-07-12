@props(['color' => 'gray', 'dot' => false, 'pulse' => false])

@php
    $colors = match($color) {
        'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
        'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
        'red' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400',
    };

    $dotColor = match($color) {
        'emerald' => 'bg-emerald-500',
        'amber' => 'bg-amber-500',
        'red' => 'bg-red-500',
        'blue' => 'bg-blue-500',
        'purple' => 'bg-purple-500',
        default => 'bg-gray-400',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium $colors"]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }} {{ $pulse ? 'animate-pulse' : '' }}"></span>
    @endif
    {{ $slot }}
</span>
