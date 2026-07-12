@props(['text' => ''])

<footer class="bg-white border-t border-gray-200 mt-8 sm:mt-16">
    <div class="max-w-5xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ $text }}
    </div>
</footer>
