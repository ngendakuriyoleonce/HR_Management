<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Check-In')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-200">
    @if ($panel === 'manager')
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
    @elseif ($panel === 'hr')
        <x-navbar
            title="HR Panel"
            color="emerald"
            :logo-url="route('filament.admin.pages.dashboard')"
            :links="[
                'Dashboard' => route('filament.admin.pages.dashboard'),
                'Check-In' => route('hr.check-in.index'),
            ]"
            :mobile-links="[
                'Dashboard' => route('filament.admin.pages.dashboard'),
                'Check-In' => route('hr.check-in.index'),
            ]"
        />
    @else
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
    @endif

    <main class="max-w-5xl mx-auto py-4 px-4 sm:py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @if ($panel === 'manager')
        <x-footer text="Manager Panel" />
    @elseif ($panel === 'hr')
        <x-footer text="HR Panel" />
    @else
        <x-footer text="Employee Panel" />
    @endif
    @stack('scripts')
</body>
</html>
