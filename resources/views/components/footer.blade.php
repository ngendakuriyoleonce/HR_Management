@props(['text' => ''])

<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 mt-8 sm:mt-16">
    <div class="max-w-5xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} {{ $text }}
    </div>
</footer>
