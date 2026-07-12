@extends('layouts.manager')

@section('title', 'Manager Dashboard')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <x-card>
        <div class="flex items-center gap-4">
            <x-avatar :employee="$employee" color="blue" />
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white/90 sm:text-2xl">{{ $employee->full_name }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $department->name ?? '' }} Manager</p>
            </div>
        </div>
    </x-card>

    <div class="grid grid-cols-2 gap-3 sm:gap-4">
        <x-stat-card label="Employees" :value="$totalEmployees" color="blue" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>' />
        <x-stat-card label="Pending Leaves" :value="$pendingLeaves" color="amber" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
        <x-stat-card label="Approved" :value="$approvedLeaves" color="emerald" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
        <x-stat-card label="Rejected" :value="$rejectedLeaves" color="red" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
    </div>

    <x-card>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">Attendance Today</h3>
                @if ($todayAttendance)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Clocked in: <span class="font-medium text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('h:i A') }}</span>
                        @if ($todayAttendance->clock_out)
                            &bull; Clocked out: <span class="font-medium text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('h:i A') }}</span>
                        @endif
                    </p>
                @else
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You haven't clocked in today.</p>
                @endif
            </div>
            <div class="flex gap-2">
                @if (!$isClockedIn)
                    <form action="{{ route('hr.check-in.clock-in') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-btn type="submit" color="emerald">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Clock In
                        </x-btn>
                    </form>
                @else
                    <form action="{{ route('hr.check-in.clock-out') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-btn type="submit" color="red">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                            Clock Out
                        </x-btn>
                    </form>
                @endif
            </div>
        </div>

        @if ($todayAttendance && $todayAttendance->clock_in && $todayAttendance->clock_out)
            @php
                $clockIn = \Carbon\Carbon::parse($todayAttendance->clock_in);
                $clockOut = \Carbon\Carbon::parse($todayAttendance->clock_out);
                $worked = $clockIn->diff($clockOut);
                $hours = floor($worked->h + ($worked->i / 60));
                $mins = $worked->i;
            @endphp
            <div class="mt-3 rounded-xl bg-blue-50 p-3 text-sm text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">
                Total worked: <span class="font-semibold">{{ $hours }}h {{ $mins }}m</span>
            </div>
        @endif
    </x-card>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
        <a href="{{ route('hr.manager-panel.department-leaves') }}" class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 transition-colors hover:border-blue-300 hover:bg-blue-50 dark:border-gray-800 dark:bg-white/[0.03] sm:p-5">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800 dark:text-white/90">Review Department Leaves</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Approve or reject pending requests</p>
            </div>
        </a>
        <a href="{{ route('hr.manager-panel.request-leave') }}" class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 transition-colors hover:border-emerald-300 hover:bg-emerald-50 dark:border-gray-800 dark:bg-white/[0.03] sm:p-5">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800 dark:text-white/90">Request Leave</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Submit your own leave request</p>
            </div>
        </a>
    </div>

    <x-card :padding="false">
        <div class="border-b border-gray-200 dark:border-gray-800 px-4 py-3 sm:px-5 sm:py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">Recent Department Leaves</h3>
                <a href="{{ route('hr.manager-panel.department-leaves') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium sm:text-sm">View All</a>
            </div>
        </div>
        <div class="p-4 sm:p-5">
            @if ($recentLeaves->count())
                <div class="space-y-2 sm:space-y-3">
                    @foreach ($recentLeaves as $leave)
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 p-2.5 dark:bg-white/[0.02] sm:p-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white/90">{{ $leave->employee->full_name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($leave->type) }} &bull; {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</p>
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
        </div>
    </x-card>
</div>
@endsection
