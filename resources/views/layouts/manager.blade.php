<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Manager Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-200">
    <x-navbar
        title="Manager Panel"
        color="blue"
        :logo-url="route('hr.manager-panel.index')"
        :links="[
            'Dashboard' => route('hr.manager-panel.index'),
            'Check-In' => route('hr.check-in.index'),
            'Department Leaves' => route('hr.manager-panel.department-leaves'),
            'My Leaves' => route('hr.manager-panel.my-leaves'),
        ]"
        :mobile-links="[
            'Dashboard' => route('hr.manager-panel.index'),
            'Check-In' => route('hr.check-in.index'),
            'Department Leaves' => route('hr.manager-panel.department-leaves'),
            'My Leaves' => route('hr.manager-panel.my-leaves'),
        ]"
    />

    <main class="max-w-5xl mx-auto py-4 px-4 sm:py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        @if ($errors->any())
            <x-alert type="error">{{ $errors->first() }}</x-alert>
        @endif

        @yield('content')
    </main>

    <x-footer text="Manager Panel" />
    @stack('scripts')
</body>
</html>
