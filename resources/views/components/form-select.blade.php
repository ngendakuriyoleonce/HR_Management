@props(['name', 'label' => null, 'required' => false, 'options' => [], 'value' => null])

@php
    $errorClass = $errors->has($name) ? 'border-red-500' : '';
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => "w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 $errorClass"]) }}
    >
        <option value="">Select...</option>
        @foreach ($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" {{ ($value ?? old($name)) == $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
