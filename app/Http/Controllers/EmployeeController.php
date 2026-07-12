<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with('department')
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        $departments = \App\Models\Department::withCount('employees')->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function show(Employee $employee): View
    {
        $employee->load(['department', 'attendances' => function ($q) {
            $q->latest('date')->limit(30);
        }, 'leaves' => function ($q) {
            $q->latest()->limit(10);
        }]);

        return view('employees.show', compact('employee'));
    }
}
