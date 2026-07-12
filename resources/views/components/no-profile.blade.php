@props(['noProfile' => false])

@if ($noProfile)
    <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center dark:border-gray-800 dark:bg-white/[0.03] sm:p-8">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 sm:h-16 sm:w-16">
            <svg class="h-7 w-7 text-gray-400 sm:h-8 sm:w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">No Employee Profile Found</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Your account is not linked to an employee profile. Please contact your administrator.</p>
    </div>
@else
    {{ $slot }}
@endif
