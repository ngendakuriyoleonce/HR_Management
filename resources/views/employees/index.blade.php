@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Our Team</h1>
    <p class="mt-2 text-gray-600">{{ $employees->total() }} active employees</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach ($employees as $employee)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-emerald-100 flex items-center justify-center overflow-hidden">
                    @if ($employee->avatar)
                        <img src="{{ asset('storage/' . $employee->avatar) }}" alt="{{ $employee->full_name }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-emerald-600">
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <h3 class="text-lg font-semibold text-gray-900">
                    <a href="{{ route('hr.employees.show', $employee) }}" class="hover:text-emerald-600 transition-colors">
                        {{ $employee->full_name }}
                    </a>
                </h3>
                <p class="text-sm text-gray-500 mt-1">{{ $employee->position }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $employee->department->name }}</p>
                <div class="mt-3">
                    @php
                        $statusColors = [
                            'active' => 'bg-emerald-100 text-emerald-700',
                            'inactive' => 'bg-red-100 text-red-700',
                            'on_leave' => 'bg-amber-100 text-amber-700',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$employee->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-10">
    {{ $employees->links() }}
</div>
@endsection
