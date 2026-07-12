@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<x-no-profile :no-profile="!$employee">
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <x-breadcrumb :items="[['label' => 'My Profile']]" />

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">
        <div class="space-y-4 lg:col-span-2 lg:space-y-6">
            <x-card>
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start sm:gap-6">
                    <div x-data="{ uploading: false }" class="relative group shrink-0">
                        <x-avatar :employee="$employee" size="lg" />
                        <label class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <svg class="h-5 w-5 text-white sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <input type="file" class="sr-only" accept="image/*" onchange="document.getElementById('avatar-form').submit()" x-ref="fileInput">
                        </label>
                        <form id="avatar-form" method="POST" action="{{ route('hr.employee-panel.update-avatar') }}" enctype="multipart/form-data" class="hidden">
                            @csrf
                            @method('PATCH')
                            <input type="file" name="avatar" accept="image/*" class="sr-only" onchange="this.form.submit()">
                        </form>
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white/90 sm:text-2xl">{{ $employee->full_name }}</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 sm:text-base">{{ $employee->position }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-500 sm:text-sm">{{ $employee->department->name ?? '' }} &bull; {{ $employee->employee_id }}</p>
                        <div class="mt-2">
                            <x-badge :color="$employee->status === 'active' ? 'emerald' : ($employee->status === 'on_leave' ? 'amber' : 'red')">
                                {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                            </x-badge>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h2 class="mb-3 text-base font-semibold text-gray-900 dark:text-white/90 sm:mb-4 sm:text-lg">Employment Details</h2>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Employee ID</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->employee_id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Position</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->position }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Department</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->department->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hire Date</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->hire_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Salary</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->salary ? '$' . number_format($employee->salary, 2) : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ ucfirst(str_replace('_', ' ', $employee->status)) }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h2 class="mb-3 text-base font-semibold text-gray-900 dark:text-white/90 sm:mb-4 sm:text-lg">Contact Information</h2>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Phone</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">City</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->city ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Country</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $employee->country ?? '-' }}</p>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="space-y-4 lg:space-y-6">
            <x-card>
                <div class="mb-3 flex items-center justify-between sm:mb-4">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white/90 sm:text-lg">Recent Leaves</h2>
                    <a href="{{ route('hr.employee-panel.my-leaves') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium sm:text-sm">View All</a>
                </div>
                @if ($employee->leaves->count())
                    <div class="space-y-2 sm:space-y-3">
                        @foreach ($employee->leaves as $leave)
                            <div class="flex items-center justify-between rounded-xl bg-gray-50 p-2.5 dark:bg-white/[0.02] sm:p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white/90">{{ ucfirst($leave->type) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</p>
                                </div>
                                <x-badge :color="$leave->status === 'approved' ? 'emerald' : ($leave->status === 'pending' ? 'amber' : 'red')">
                                    {{ ucfirst($leave->status) }}
                                </x-badge>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">No leave requests yet.</p>
                @endif
            </x-card>

            <x-card>
                <h2 class="mb-3 text-base font-semibold text-gray-900 dark:text-white/90 sm:mb-4 sm:text-lg">Recent Attendance</h2>
                @if ($employee->attendances->count())
                    <div class="space-y-2 sm:space-y-3">
                        @foreach ($employee->attendances as $att)
                            <div class="flex items-center justify-between rounded-xl bg-gray-50 p-2.5 dark:bg-white/[0.02] sm:p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white/90">{{ $att->date->format('D, M d') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $att->clock_in?->format('h:i A') ?? '-' }} - {{ $att->clock_out?->format('h:i A') ?? '-' }}</p>
                                </div>
                                <x-badge :color="$att->status === 'present' ? 'emerald' : ($att->status === 'late' ? 'amber' : ($att->status === 'absent' ? 'red' : 'blue'))">
                                    {{ ucfirst(str_replace('_', ' ', $att->status)) }}
                                </x-badge>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">No attendance records yet.</p>
                @endif
            </x-card>
        </div>
    </div>
</x-no-profile>
@endsection
