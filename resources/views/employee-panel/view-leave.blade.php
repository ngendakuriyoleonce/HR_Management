@extends('layouts.app')

@section('title', 'Leave Details')

@section('content')
<x-no-profile :no-profile="!$employee">
    <x-breadcrumb :items="[
        ['label' => 'My Leaves', 'url' => route('hr.employee-panel.my-leaves')],
        ['label' => 'Details'],
    ]" />

    <div class="mx-auto max-w-2xl">
        <x-card>
            <div class="mb-4 flex items-center justify-between sm:mb-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">Leave Details</h3>
                <x-badge :color="$leave->status === 'approved' ? 'emerald' : ($leave->status === 'pending' ? 'amber' : 'red')">
                    {{ ucfirst($leave->status) }}
                </x-badge>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Leave Type</p>
                        <p class="mt-1">
                            <x-badge :color="$leave->type === 'sick' ? 'red' : ($leave->type === 'vacation' ? 'emerald' : ($leave->type === 'personal' ? 'amber' : ($leave->type === 'maternity' ? 'purple' : ($leave->type === 'paternity' ? 'blue' : 'gray'))))">
                                {{ ucfirst($leave->type) }}
                            </x-badge>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Duration</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $leave->duration_days }} {{ Str::plural('day', $leave->duration_days) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Start Date</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $leave->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">End Date</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $leave->end_date->format('M d, Y') }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Reason</p>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $leave->reason }}</p>
                </div>

                @if ($leave->notes)
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notes</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $leave->notes }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Submitted</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $leave->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    @if ($leave->approved_by)
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Reviewed By</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white/90">{{ $leave->approver->full_name ?? '-' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                @if ($leave->status === 'pending')
                    <form method="POST" action="{{ route('hr.employee-panel.cancel-leave', $leave->id) }}" onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                        @csrf
                        @method('DELETE')
                        <x-btn type="submit" color="red" class="w-full sm:w-auto">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Request
                        </x-btn>
                    </form>
                @endif
                <x-btn-outline href="{{ route('hr.employee-panel.my-leaves') }}" class="w-full sm:w-auto">
                    Back to Leaves
                </x-btn-outline>
            </div>
        </x-card>
    </div>
</x-no-profile>
@endsection
