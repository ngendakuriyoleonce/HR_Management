@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]' . ($padding ? ' p-4 sm:p-6' : '')]) }}>
    {{ $slot }}
</div>
