@props(['title', 'color' => 'emerald', 'links' => [], 'mobileLinks' => [], 'logoUrl' => '#'])

@php
    $hoverColor = match($color) {
        'emerald' => 'hover:text-emerald-600 dark:hover:text-emerald-400',
        'blue' => 'hover:text-blue-600 dark:hover:text-blue-400',
        default => 'hover:text-emerald-600 dark:hover:text-emerald-400',
    };

    $logoBg = match($color) {
        'emerald' => 'bg-emerald-500 dark:bg-emerald-600',
        'blue' => 'bg-blue-600 dark:bg-blue-500',
        default => 'bg-emerald-500 dark:bg-emerald-600',
    };

    $activeColor = match($color) {
        'emerald' => 'text-emerald-600 dark:text-emerald-400',
        'blue' => 'text-blue-600 dark:text-blue-400',
        default => 'text-emerald-600 dark:text-emerald-400',
    };
@endphp

<nav class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-800" x-data="{ mobileOpen: false }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 items-center sm:h-16">
            {{-- Logo --}}
            <a href="{{ $logoUrl }}" class="flex items-center gap-2.5 shrink-0">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $logoBg }}">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-800 dark:text-white hidden sm:inline">{{ $title }}</span>
                <span class="text-base font-bold text-gray-800 dark:text-white sm:hidden">{{ $title }}</span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden sm:flex sm:items-center sm:gap-1">
                @auth
                    @foreach ($links as $label => $url)
                        <a href="{{ $url }}" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 {{ $hoverColor }} transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                            {{ $label }}
                        </a>
                    @endforeach

                    {{-- Dark mode toggle --}}
                    <button
                        @click="$store.theme.toggle()"
                        class="ml-1 inline-flex items-center justify-center rounded-lg p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        title="Toggle dark mode"
                    >
                        <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <div class="ml-1 h-5 w-px bg-gray-200 dark:bg-gray-700"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ml-1 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>

            {{-- Mobile: dark mode toggle + hamburger --}}
            <div class="flex items-center gap-1 sm:hidden">
                <button
                    @click="$store.theme.toggle()"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    title="Toggle dark mode"
                >
                    <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="$store.theme.dark" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="sm:hidden border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900"
        @click.outside="mobileOpen = false"
    >
        @auth
            <div class="px-3 py-2 space-y-0.5">
                @foreach ($mobileLinks as $label => $url)
                    <a href="{{ $url }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        {{ $label }}
                    </a>
                @endforeach

                <div class="my-1 border-t border-gray-200 dark:border-gray-800"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>
