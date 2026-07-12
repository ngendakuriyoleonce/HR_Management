<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeePanelController extends Controller
{
    public function profile(Request $request): View
    {
        $employee = Employee::where('user_id', $request->user()->id)
            ->with(['department', 'attendances' => function ($q) {
                $q->latest('date')->limit(7);
            }, 'leaves' => function ($q) {
                $q->latest()->limit(5);
            }])
            ->first();

        return view('employee-panel.profile', compact('employee'));
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $employee = Employee::where('user_id', $request->user()->id)->first();

        if (!$employee) {
            return back()->withErrors(['error' => 'No employee profile found.']);
        }

        if ($employee->avatar) {
            Storage::disk('public')->delete($employee->avatar);
        }

        $path = $request->file('avatar')->store('employees/avatars', 'public');

        $employee->update(['avatar' => $path]);

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function myLeaves(Request $request): View
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        $leaves = [];
        if ($employee) {
            $leaves = Leave::where('employee_id', $employee->id)
                ->latest()
                ->paginate(10);
        }

        return view('employee-panel.my-leaves', compact('employee', 'leaves'));
    }

    public function requestLeave(Request $request): View
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        return view('employee-panel.request-leave', compact('employee'));
    }

    public function storeLeave(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:sick,vacation,personal,maternity,paternity,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $employee = Employee::where('user_id', $request->user()->id)->first();

        if (!$employee) {
            return back()->withErrors(['error' => 'No employee profile found.']);
        }

        Leave::create([
            'employee_id' => $employee->id,
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('hr.employee-panel.my-leaves')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function cancelLeave(Request $request, int $leaveId): RedirectResponse
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        if (!$employee) {
            return back()->withErrors(['error' => 'No employee profile found.']);
        }

        $leave = Leave::where('id', $leaveId)
            ->where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->first();

        if (!$leave) {
            return back()->withErrors(['error' => 'Leave request not found or cannot be cancelled.']);
        }

        $leave->delete();

        return redirect()->route('hr.employee-panel.my-leaves')
            ->with('success', 'Leave request cancelled successfully.');
    }

    public function viewLeave(Request $request, int $leaveId): View
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        if (!$employee) {
            abort(404);
        }

        $leave = Leave::where('id', $leaveId)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        return view('employee-panel.view-leave', compact('employee', 'leave'));
    }
}
