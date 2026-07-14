@extends('layouts.check-in')

@section('title', 'Check-In')

@section('content')
<div x-data="checkInApp()" class="min-h-[calc(100vh-8rem)]">

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <x-alert type="success">{{ session('success') }}</x-alert>
        </div>
    @endif

    @if ($errors->any())
        <x-alert type="error">{{ $errors->first() }}</x-alert>
    @endif

    @if (!$employee)
        <x-card class="p-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">No Employee Profile Found</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Your account is not linked to an employee profile. Please contact your administrator.</p>
        </x-card>
    @else
    <div class="grid grid-cols-1 gap-4 md:gap-6 xl:grid-cols-12">

        <div class="xl:col-span-5">
            <x-card>
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90 sm:mb-7">Attendance</h3>

                <div class="mb-5 flex items-center gap-3 sm:mb-6 sm:gap-4">
                    <x-avatar :employee="$employee" />
                    <div>
                        <h4 class="font-semibold text-gray-800 dark:text-white/90">{{ $employee->full_name }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 sm:text-sm">{{ $employee->position }} &bull; {{ $employee->department->name ?? '' }}</p>
                    </div>
                </div>

                <div class="mb-5 text-center sm:mb-6">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 sm:text-sm">Current Time</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white/90 tabular-nums sm:text-4xl" x-text="currentTime">--:--:-- --</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="currentDate">Loading...</p>
                </div>

                <div class="mb-5 text-center sm:mb-6">
                    @if ($todayAttendance && $todayAttendance->clock_in)
                        @if ($isClockedIn)
                            <x-badge color="emerald" :dot="true" :pulse="true">Clocked In</x-badge>
                        @else
                            <x-badge color="gray" :dot="true">Clocked Out</x-badge>
                        @endif
                    @else
                        <x-badge color="amber" :dot="true">Not Clocked In</x-badge>
                    @endif
                </div>

                @if ($isClockedIn)
                    <form method="POST" action="{{ route('hr.check-in.clock-out') }}">
                        @csrf
                        @method('PATCH')
                        <x-btn type="submit" color="red" class="w-full sm:py-3.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Clock Out
                        </x-btn>
                    </form>
                @else
                    <form method="POST" action="{{ route('hr.check-in.clock-in') }}">
                        @csrf
                        @method('PATCH')
                        <x-btn type="submit" color="emerald" class="w-full sm:py-3.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Clock In
                        </x-btn>
                    </form>
                @endif

                @if ($todayAttendance && $todayAttendance->clock_in)
                    <div class="mt-4 grid grid-cols-2 gap-2 sm:mt-5 sm:gap-3">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-2.5 text-center dark:border-gray-800 dark:bg-white/[0.02] sm:p-3">
                            <p class="mb-0.5 text-xs text-gray-500 dark:text-gray-400">Clock In</p>
                            <p class="text-xs font-semibold text-gray-800 dark:text-white/90 sm:text-sm">{{ $todayAttendance->clock_in->format('h:i A') }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-2.5 text-center dark:border-gray-800 dark:bg-white/[0.02] sm:p-3">
                            <p class="mb-0.5 text-xs text-gray-500 dark:text-gray-400">Clock Out</p>
                            @if ($isClockedIn)
                                <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 sm:text-sm" x-text="liveTime">--:-- --</p>
                            @else
                                <p class="text-xs font-semibold text-gray-800 dark:text-white/90 sm:text-sm">{{ $todayAttendance->clock_out ? $todayAttendance->clock_out->format('h:i A') : '--' }}</p>
                            @endif
                        </div>
                    </div>
                    @if ($todayAttendance->clock_out)
                        @php
                            $diff = $todayAttendance->clock_in->diff($todayAttendance->clock_out);
                            $hours = $diff->h + ($diff->i / 60);
                        @endphp
                        <div class="mt-2 rounded-xl border border-gray-200 bg-gray-50 p-2.5 text-center dark:border-gray-800 dark:bg-white/[0.02] sm:mt-3 sm:p-3">
                            <p class="mb-0.5 text-xs text-gray-500 dark:text-gray-400">Hours Worked</p>
                            <p class="text-xs font-semibold text-gray-800 dark:text-white/90 sm:text-sm">{{ number_format($hours, 1) }} hours</p>
                        </div>
                    @elseif ($isClockedIn)
                        <div class="mt-2 rounded-xl border border-emerald-200 bg-emerald-50 p-2.5 text-center dark:border-emerald-800 dark:bg-emerald-900/20 sm:mt-3 sm:p-3">
                            <p class="mb-0.5 text-xs text-emerald-600 dark:text-emerald-400">Time Elapsed</p>
                            <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-300 sm:text-sm" x-text="elapsed">--:--:--</p>
                        </div>
                    @endif
                @endif
            </x-card>
        </div>

        <div class="space-y-4 xl:col-span-7 sm:space-y-6">
            @php
                $weekStart = \Carbon\Carbon::now()->startOfWeek();
                $weekEnd = \Carbon\Carbon::now()->endOfWeek();
                $weekAttendances = \App\Models\Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                    ->get();
                $weekPresent = $weekAttendances->where('status', 'present')->count();
                $weekLate = $weekAttendances->where('status', 'late')->count();
                $weekAbsent = $weekAttendances->where('status', 'absent')->count();
            @endphp

            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <x-stat-card label="Present This Week" :value="$weekPresent" color="emerald" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
                <x-stat-card label="Late This Week" :value="$weekLate" color="amber" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
                <x-stat-card label="Absent This Week" :value="$weekAbsent" color="red" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
                <x-stat-card label="Days Tracked" :value="$weekAttendances->count()" color="blue" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>' />
            </div>

            <x-card :padding="false">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800 sm:px-5 sm:py-4 lg:px-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">Recent Attendance</h3>
                </div>
                <div class="p-4 sm:p-5 lg:p-6">
                    @if ($recentAttendances->count())
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[480px]">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Date</th>
                                        <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Clock In</th>
                                        <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Clock Out</th>
                                        <th class="pb-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach ($recentAttendances as $att)
                                        <tr>
                                            <td class="py-3 text-sm text-gray-700 dark:text-gray-300">{{ $att->date->format('D, M d') }}</td>
                                            <td class="py-3 text-sm text-gray-600 dark:text-gray-400">{{ $att->clock_in?->format('h:i A') ?? '-' }}</td>
                                            <td class="py-3 text-sm text-gray-600 dark:text-gray-400">{{ $att->clock_out?->format('h:i A') ?? '-' }}</td>
                                            <td class="py-3">
                                                <x-badge :color="$att->status === 'present' ? 'emerald' : ($att->status === 'late' ? 'amber' : ($att->status === 'absent' ? 'red' : 'blue'))">
                                                    {{ ucfirst(str_replace('_', ' ', $att->status)) }}
                                                </x-badge>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">No attendance records yet.</p>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function checkInApp() {
        return {
            currentTime: '',
            currentDate: '',
            liveTime: '',
            elapsed: '',
            clockInAt: @json($todayAttendance && $todayAttendance->clock_in ? $todayAttendance->clock_in->timestamp : null),
            isClockedIn: @json($isClockedIn),
            updateClock() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
                this.currentDate = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                this.liveTime = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
                if (this.isClockedIn && this.clockInAt) {
                    const diff = Math.floor((now.getTime() / 1000) - this.clockInAt);
                    if (diff >= 0) {
                        const h = Math.floor(diff / 3600);
                        const m = Math.floor((diff % 3600) / 60);
                        const s = diff % 60;
                        this.elapsed = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                    }
                }
            },
            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            }
        }
    }
</script>
@endpush
@endsection
