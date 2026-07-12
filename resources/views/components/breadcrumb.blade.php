@props(['items' => []])

<nav class="mb-4 text-sm text-gray-500 sm:mb-8">
    @foreach ($items as $item)
        @if ($loop->last)
            <span class="text-gray-900 font-medium">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}" class="hover:text-emerald-600 transition-colors">{{ $item['label'] }}</a>
            <span class="mx-2">/</span>
        @endif
    @endforeach
</nav>
