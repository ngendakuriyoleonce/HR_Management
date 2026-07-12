<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManagerPanelController extends Controller
{
    protected function getManager(Request $request): ?Employee
    {
        $employee = Employee::where('user_id', $request->user()->id)->first();

        if (!$employee) {
            return null;
        }

        $isManager = Department::where('manager_id', $employee->id)->exists();

        return $isManager ? $employee : null;
    }

    protected function getDepartmentId(int $employeeId): ?int
    {
        $dept = Department::where('manager_id', $employeeId)->first();

        return $dept?->id;
    }

    public function index(Request $request): View
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            abort(403, 'You are not a manager.');
        }

        $departmentId = $this->getDepartmentId($employee->id);
        $department = Department::find($departmentId);

        $totalEmployees = Employee::where('department_id', $departmentId)->count();
        $pendingLeaves = Leave::whereHas('employee', fn ($q) => $q->where('department_id', $departmentId))
            ->where('status', 'pending')
            ->count();
        $approvedLeaves = Leave::whereHas('employee', fn ($q) => $q->where('department_id', $departmentId))
            ->where('status', 'approved')
            ->count();
        $rejectedLeaves = Leave::whereHas('employee', fn ($q) => $q->where('department_id', $departmentId))
            ->where('status', 'rejected')
            ->count();

        $recentLeaves = Leave::whereHas('employee', fn ($q) => $q->where('department_id', $departmentId))
            ->with('employee')
            ->latest()
            ->limit(5)
            ->get();

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $isClockedIn = $todayAttendance && $todayAttendance->clock_in && !$todayAttendance->clock_out;

        $recentAttendances = Attendance::where('employee_id', $employee->id)
            ->latest('date')
            ->limit(7)
            ->get();

        return view('manager-panel.index', compact(
            'employee',
            'department',
            'totalEmployees',
            'pendingLeaves',
            'approvedLeaves',
            'rejectedLeaves',
            'recentLeaves',
            'todayAttendance',
            'isClockedIn',
            'recentAttendances'
        ));
    }

    public function myLeaves(Request $request): View
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            abort(403);
        }

        $leaves = Leave::where('employee_id', $employee->id)
            ->latest()
            ->paginate(10);

        return view('manager-panel.my-leaves', compact('employee', 'leaves'));
    }

    public function requestLeave(Request $request): View
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            abort(403);
        }

        return view('manager-panel.request-leave', compact('employee'));
    }

    public function storeLeave(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:sick,vacation,personal,maternity,paternity,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $employee = $this->getManager($request);

        if (!$employee) {
            return back()->withErrors(['error' => 'Unauthorized.']);
        }

        Leave::create([
            'employee_id' => $employee->id,
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('hr.manager-panel.my-leaves')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function viewLeave(Request $request, int $leaveId): View
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            abort(403);
        }

        $leave = Leave::where('id', $leaveId)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        return view('manager-panel.view-leave', compact('employee', 'leave'));
    }

    public function cancelLeave(Request $request, int $leaveId): RedirectResponse
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            return back()->withErrors(['error' => 'Unauthorized.']);
        }

        $leave = Leave::where('id', $leaveId)
            ->where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->first();

        if (!$leave) {
            return back()->withErrors(['error' => 'Leave request not found or cannot be cancelled.']);
        }

        $leave->delete();

        return redirect()->route('hr.manager-panel.my-leaves')
            ->with('success', 'Leave request cancelled successfully.');
    }

    public function departmentLeaves(Request $request): View
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            abort(403);
        }

        $departmentId = $this->getDepartmentId($employee->id);
        $department = Department::find($departmentId);

        $status = $request->get('status');

        $leaves = Leave::whereHas('employee', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->with('employee');

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $leaves->where('status', $status);
        }

        $leaves = $leaves->latest()->paginate(15);

        return view('manager-panel.department-leaves', compact('employee', 'department', 'leaves', 'status'));
    }

    public function approveLeave(Request $request, int $leaveId): RedirectResponse
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            return back()->withErrors(['error' => 'Unauthorized.']);
        }

        $departmentId = $this->getDepartmentId($employee->id);

        $leave = Leave::where('id', $leaveId)
            ->whereHas('employee', fn ($q) => $q->where('department_id', $departmentId))
            ->where('status', 'pending')
            ->first();

        if (!$leave) {
            return back()->withErrors(['error' => 'Leave request not found or already processed.']);
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => $employee->id,
        ]);

        return back()->with('success', 'Leave request approved successfully.');
    }

    public function rejectLeave(Request $request, int $leaveId): RedirectResponse
    {
        $employee = $this->getManager($request);

        if (!$employee) {
            return back()->withErrors(['error' => 'Unauthorized.']);
        }

        $departmentId = $this->getDepartmentId($employee->id);

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $leave = Leave::where('id', $leaveId)
            ->whereHas('employee', fn ($q) => $q->where('department_id', $departmentId))
            ->where('status', 'pending')
            ->first();

        if (!$leave) {
            return back()->withErrors(['error' => 'Leave request not found or already processed.']);
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => $employee->id,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Leave request rejected.');
    }
}
