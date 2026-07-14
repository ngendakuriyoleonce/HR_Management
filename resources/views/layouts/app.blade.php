<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Employee Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-200">
    <x-navbar
        title="Employee Panel"
        color="emerald"
        :logo-url="route('hr.check-in.index')"
        :links="[
            'Check-In' => route('hr.check-in.index'),
            'My Leaves' => route('hr.employee-panel.my-leaves'),
            'Profile' => route('hr.employee-panel.profile'),
        ]"
        :mobile-links="[
            'Check-In' => route('hr.check-in.index'),
            'My Leaves' => route('hr.employee-panel.my-leaves'),
            'Profile' => route('hr.employee-panel.profile'),
        ]"
    />

    <main class="max-w-5xl mx-auto py-4 px-4 sm:py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <x-footer text="Employee Panel" />
    @stack('scripts')
</body>
</html>
