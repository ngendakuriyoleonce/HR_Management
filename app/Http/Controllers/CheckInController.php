<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckInController extends Controller
{
    public function index(Request $request): View
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        $todayAttendance = null;
        $isClockedIn = false;

        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', Carbon::today())
                ->first();

            $isClockedIn = $todayAttendance && $todayAttendance->clock_in && !$todayAttendance->clock_out;
        }

        $recentAttendances = [];
        if ($employee) {
            $recentAttendances = Attendance::where('employee_id', $employee->id)
                ->latest('date')
                ->limit(7)
                ->get();
        }

        return view('check-in.index', compact('employee', 'todayAttendance', 'isClockedIn', 'recentAttendances'));
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        if (!$employee) {
            return back()->withErrors(['error' => 'No employee profile found for your account.']);
        }

        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($existing && $existing->clock_in) {
            return back()->withErrors(['error' => 'You have already clocked in today.']);
        }

        $now = Carbon::now();
        $hour = $now->hour;
        $status = ($hour >= 9) ? 'late' : 'present';

        if ($existing) {
            $existing->update([
                'clock_in' => $now,
                'status' => $status,
            ]);
        } else {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => Carbon::today()->toDateString(),
                'clock_in' => $now,
                'status' => $status,
            ]);
        }

        return back()->with('success', 'Clocked in successfully at ' . $now->format('h:i A') . '.');
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        if (!$employee) {
            return back()->withErrors(['error' => 'No employee profile found for your account.']);
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$todayAttendance || !$todayAttendance->clock_in) {
            return back()->withErrors(['error' => 'You have not clocked in today.']);
        }

        if ($todayAttendance->clock_out) {
            return back()->withErrors(['error' => 'You have already clocked out today.']);
        }

        $now = Carbon::now();

        $todayAttendance->update([
            'clock_out' => $now,
        ]);

        return back()->with('success', 'Clocked out successfully at ' . $now->format('h:i A') . '.');
    }
}
