@extends('layouts.manager')

@section('title', 'Department Leaves')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('hr.manager-panel.index')],
                ['label' => 'Department Leaves'],
            ]" class="!mb-1" />
            <h1 class="text-xl font-bold text-gray-900 dark:text-white/90 sm:text-2xl">{{ $department->name ?? '' }} Leave Requests</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('hr.manager-panel.department-leaves') }}"
                class="inline-flex items-center rounded-xl px-3 py-2 text-xs font-medium transition-colors {{ !$status ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                All
            </a>
            <a href="{{ route('hr.manager-panel.department-leaves', ['status' => 'pending']) }}"
                class="inline-flex items-center rounded-xl px-3 py-2 text-xs font-medium transition-colors {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                Pending
            </a>
            <a href="{{ route('hr.manager-panel.department-leaves', ['status' => 'approved']) }}"
                class="inline-flex items-center rounded-xl px-3 py-2 text-xs font-medium transition-colors {{ $status === 'approved' ? 'bg-emerald-500 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                Approved
            </a>
            <a href="{{ route('hr.manager-panel.department-leaves', ['status' => 'rejected']) }}"
                class="inline-flex items-center rounded-xl px-3 py-2 text-xs font-medium transition-colors {{ $status === 'rejected' ? 'bg-red-500 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                Rejected
            </a>
        </div>
    </div>

    <x-card :padding="false">
        <div class="p-4 sm:p-5">
            @if ($leaves->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px]">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Employee</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Type</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Duration</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Reason</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td class="py-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white/90">{{ $leave->employee->full_name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $leave->employee->position ?? '' }}</p>
                                    </td>
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
                                        {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}
                                        <br>
                                        <span class="text-xs text-gray-500">{{ $leave->duration_days }} {{ Str::plural('day', $leave->duration_days) }}</span>
                                    </td>
                                    <td class="max-w-[150px] truncate py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $leave->reason }}
                                    </td>
                                    <td class="py-3">
                                        <x-badge :color="$leave->status === 'approved' ? 'emerald' : ($leave->status === 'pending' ? 'amber' : 'red')">
                                            {{ ucfirst($leave->status) }}
                                        </x-badge>
                                    </td>
                                    <td class="py-3">
                                        @if ($leave->status === 'pending')
                                            <div class="flex items-center gap-1.5">
                                                <form method="POST" action="{{ route('hr.manager-panel.approve-leave', $leave->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-600 hover:bg-emerald-100 transition-colors">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('hr.manager-panel.reject-leave', $leave->id) }}" onsubmit="return confirm('Reject this leave?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">&mdash;</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $leaves->links() }}</div>
            @else
                <p class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">No leave requests found.</p>
            @endif
        </div>
    </x-card>
</div>
@endsection
