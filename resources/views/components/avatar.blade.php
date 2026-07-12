@props(['employee', 'color' => 'emerald', 'size' => 'md'])

@php
    $sizeClass = match($size) {
        'sm' => 'h-9 w-9 text-xs',
        'md' => 'h-12 w-12 sm:h-14 sm:w-14 text-lg sm:text-xl',
        'lg' => 'h-20 w-20 sm:h-24 sm:w-24 text-2xl sm:text-3xl',
    };

    $bgColor = match($color) {
        'emerald' => 'bg-emerald-100',
        'blue' => 'bg-blue-100',
        'amber' => 'bg-amber-100',
        'red' => 'bg-red-100',
        default => 'bg-gray-100',
    };

    $textColor = match($color) {
        'emerald' => 'text-emerald-600',
        'blue' => 'text-blue-600',
        'amber' => 'text-amber-600',
        'red' => 'text-red-600',
        default => 'text-gray-600',
    };

    $initials = strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1));
@endphp

<div {{ $attributes->merge(['class' => "shrink-0 overflow-hidden rounded-full border border-gray-200 dark:border-gray-700 $bgColor flex items-center justify-center $sizeClass"]) }}>
    @if ($employee->avatar)
        <img src="{{ asset('storage/' . $employee->avatar) }}" alt="{{ $employee->full_name }}" class="h-full w-full object-cover">
    @else
        <span class="font-bold {{ $textColor }}">{{ $initials }}</span>
    @endif
</div>
