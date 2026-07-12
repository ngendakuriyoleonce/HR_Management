@extends('layouts.app')

@section('title', 'Request Leave')

@section('content')
<x-no-profile :no-profile="!$employee">
    <x-breadcrumb :items="[
        ['label' => 'My Leaves', 'url' => route('hr.employee-panel.my-leaves')],
        ['label' => 'Request Leave'],
    ]" />

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <div class="mx-auto max-w-2xl">
        <x-card>
            <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white/90 sm:mb-6 sm:text-lg">Submit Leave Request</h3>

            <form method="POST" action="{{ route('hr.employee-panel.store-leave') }}" class="space-y-4 sm:space-y-5">
                @csrf

                <x-form-select name="type" label="Leave Type" :options="[
                    'sick' => 'Sick Leave',
                    'vacation' => 'Vacation',
                    'personal' => 'Personal',
                    'maternity' => 'Maternity',
                    'paternity' => 'Paternity',
                    'unpaid' => 'Unpaid',
                ]" :value="old('type')" required />

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form-input name="start_date" label="Start Date" type="date" :value="old('start_date')" required />
                    <x-form-input name="end_date" label="End Date" type="date" :value="old('end_date')" required />
                </div>

                <x-form-textarea name="reason" label="Reason" placeholder="Please provide a reason for your leave request..." :value="old('reason')" required />

                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
                    <x-btn type="submit" color="emerald" class="w-full sm:w-auto">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Submit Request
                    </x-btn>
                    <x-btn-outline href="{{ route('hr.employee-panel.my-leaves') }}" class="w-full sm:w-auto">
                        Cancel
                    </x-btn-outline>
                </div>
            </form>
        </x-card>
    </div>
</x-no-profile>
@endsection
