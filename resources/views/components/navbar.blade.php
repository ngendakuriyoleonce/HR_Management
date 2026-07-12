@props(['title', 'color' => 'emerald', 'links' => [], 'mobileLinks' => [], 'logoUrl' => '#'])

@php
    $hoverColor = match($color) {
        'emerald' => 'hover:text-emerald-600',
        'blue' => 'hover:text-blue-600',
        default => 'hover:text-emerald-600',
    };

    $logoBg = match($color) {
        'emerald' => 'bg-emerald-500',
        'blue' => 'bg-blue-600',
        default => 'bg-emerald-500',
    };
@endphp

<nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileOpen: false }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ $logoUrl }}" class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $logoBg }}">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-800">{{ $title }}</span>
            </a>

            <div class="hidden sm:flex sm:items-center sm:gap-6">
                @auth
                    @foreach ($links as $label => $url)
                        <a href="{{ $url }}" class="text-sm font-medium text-gray-700 {{ $hoverColor }} transition-colors">
                            {{ $label }}
                        </a>
                    @endforeach
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-700 hover:text-red-600 transition-colors">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>

            <button @click="mobileOpen = !mobileOpen" class="sm:hidden inline-flex items-center justify-center rounded-lg p-2 text-gray-600 hover:bg-gray-100">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
        class="sm:hidden border-t border-gray-200 bg-white">
        @auth
            <div class="px-4 py-3 space-y-1">
                @foreach ($mobileLinks as $label => $url)
                    <a href="{{ $url }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        {{ $label }}
                    </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>
