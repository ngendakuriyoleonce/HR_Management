@extends('layouts.app')

@section('title', $employee->full_name)

@section('content')
<nav class="mb-8 text-sm text-gray-500">
    <a href="{{ route('hr.employees.index') }}" class="hover:text-emerald-600 transition-colors">Employees</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900">{{ $employee->full_name }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start gap-6">
                <div class="w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center overflow-hidden shrink-0">
                    @if ($employee->avatar)
                        <img src="{{ asset('storage/' . $employee->avatar) }}" alt="{{ $employee->full_name }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold text-emerald-600">
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $employee->full_name }}</h1>
                    <p class="text-gray-600">{{ $employee->position }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $employee->department->name }} &bull; {{ $employee->employee_id }}</p>
                    <div class="mt-2">
                        @php
                            $statusColors = [
                                'active' => 'bg-emerald-100 text-emerald-700',
                                'inactive' => 'bg-red-100 text-red-700',
                                'on_leave' => 'bg-amber-100 text-amber-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$employee->status] }}">
                            {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Attendance</h2>
            @if ($employee->attendances->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock In</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clock Out</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($employee->attendances as $attendance)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $attendance->date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->clock_in?->format('h:i A') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->clock_out?->format('h:i A') ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $attColors = [
                                                'present' => 'bg-emerald-100 text-emerald-700',
                                                'absent' => 'bg-red-100 text-red-700',
                                                'late' => 'bg-amber-100 text-amber-700',
                                                'half_day' => 'bg-blue-100 text-blue-700',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $attColors[$attendance->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm">No attendance records yet.</p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Info</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $employee->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Phone</dt>
                    <dd class="text-sm text-gray-900">{{ $employee->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">City</dt>
                    <dd class="text-sm text-gray-900">{{ $employee->city ?? '-' }}, {{ $employee->country ?? '' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Employment</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Hire Date</dt>
                    <dd class="text-sm text-gray-900">{{ $employee->hire_date->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase">Salary</dt>
                    <dd class="text-sm text-gray-900">{{ $employee->salary ? '$' . number_format($employee->salary, 2) : '-' }}</dd>
                </div>
            </dl>
        </div>

        @if ($employee->leaves->count())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Leaves</h2>
                <div class="space-y-3">
                    @foreach ($employee->leaves as $leave)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($leave->type) }}</p>
                                <p class="text-xs text-gray-500">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</p>
                            </div>
                            @php
                                $leaveColors = [
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $leaveColors[$leave->status] }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
