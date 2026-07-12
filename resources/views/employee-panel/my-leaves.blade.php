@extends('layouts.app')

@section('title', 'My Leaves')

@section('content')
<x-no-profile :no-profile="!$employee">
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <div class="mb-4 flex flex-col gap-3 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
        <x-breadcrumb :items="[['label' => 'My Leaves']]" />
        <x-btn href="{{ route('hr.employee-panel.request-leave') }}" color="emerald" class="w-full sm:w-auto">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Request Leave
        </x-btn>
    </div>

    @php
        $pendingCount = \App\Models\Leave::where('employee_id', $employee->id)->where('status', 'pending')->count();
        $approvedCount = \App\Models\Leave::where('employee_id', $employee->id)->where('status', 'approved')->count();
        $rejectedCount = \App\Models\Leave::where('employee_id', $employee->id)->where('status', 'rejected')->count();
    @endphp

    <div class="mb-4 grid grid-cols-3 gap-2 sm:mb-6 sm:gap-4">
        <x-stat-card label="Pending" :value="$pendingCount" color="amber" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
        <x-stat-card label="Approved" :value="$approvedCount" color="emerald" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
        <x-stat-card label="Rejected" :value="$rejectedCount" color="red" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
    </div>

    <x-card :padding="false">
        <div class="border-b border-gray-200 dark:border-gray-800 px-4 py-3 sm:px-5 sm:py-4 lg:px-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">Leave Requests</h3>
        </div>
        <div class="p-4 sm:p-5 lg:p-6">
            @if ($leaves->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px]">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Type</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Duration</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Days</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Reason</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Submitted</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td class="py-3">
                                        @php
                                            $typeColors = [
                                                'sick' => 'red',
                                                'vacation' => 'emerald',
                                                'personal' => 'amber',
                                                'maternity' => 'purple',
                                                'paternity' => 'blue',
                                                'unpaid' => 'gray',
                                            ];
                                        @endphp
                                        <x-badge :color="$typeColors[$leave->type] ?? 'gray'">{{ ucfirst($leave->type) }}</x-badge>
                                    </td>
                                    <td class="py-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                    </td>
                                    <td class="py-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $leave->duration_days }} {{ Str::plural('day', $leave->duration_days) }}
                                    </td>
                                    <td class="max-w-[200px] truncate py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $leave->reason }}
                                    </td>
                                    <td class="py-3">
                                        <x-badge :color="$leave->status === 'approved' ? 'emerald' : ($leave->status === 'pending' ? 'amber' : 'red')">
                                            {{ ucfirst($leave->status) }}
                                        </x-badge>
                                    </td>
                                    <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $leave->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('hr.employee-panel.view-leave', $leave->id) }}" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-100 transition-colors">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                            @if ($leave->status === 'pending')
                                                <form method="POST" action="{{ route('hr.employee-panel.cancel-leave', $leave->id) }}" onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $leaves->links() }}
                </div>
            @else
                <p class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">No leave requests found.</p>
            @endif
        </div>
    </x-card>
</x-no-profile>
@endsection
