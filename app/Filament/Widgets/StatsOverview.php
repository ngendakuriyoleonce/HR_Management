<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $inactiveEmployees = Employee::where('status', 'inactive')->count();
        $onLeaveEmployees = Employee::where('status', 'on_leave')->count();
        $totalDepartments = Department::count();

        $pendingLeaves = Leave::where('status', 'pending')->count();
        $approvedLeaves = Leave::where('status', 'approved')->count();
        $rejectedLeaves = Leave::where('status', 'rejected')->count();

        $todayPresent = Attendance::whereDate('date', Carbon::today())
            ->where('status', 'present')
            ->count();
        $todayLate = Attendance::whereDate('date', Carbon::today())
            ->where('status', 'late')
            ->count();
        $todayAbsent = $activeEmployees - ($todayPresent + $todayLate);

        $averageSalary = Employee::where('status', 'active')->avg('salary');

        return [
            Stat::make('Total Employees', $totalEmployees)
                ->description("{$activeEmployees} active, {$onLeaveEmployees} on leave")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([$totalEmployees - 3, $totalEmployees - 1, $totalEmployees - 5, $totalEmployees - 2, $totalEmployees - 4, $totalEmployees]),

            Stat::make('Departments', $totalDepartments)
                ->description('Organizational units')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),

            Stat::make('Today\'s Attendance', $todayPresent . '/' . $activeEmployees)
                ->description($todayLate . ' late, ' . max(0, $todayAbsent) . ' absent')
                ->descriptionIcon('heroicon-m-clock')
                ->color($todayPresent > 0 ? 'success' : 'warning')
                ->chart([$todayPresent, $todayLate, max(0, $todayAbsent)]),

            Stat::make('Pending Leaves', $pendingLeaves)
                ->description("{$approvedLeaves} approved, {$rejectedLeaves} rejected")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($pendingLeaves > 0 ? 'warning' : 'success')
                ->chart([$pendingLeaves, $approvedLeaves, $rejectedLeaves]),

            Stat::make('Avg. Salary', filled($averageSalary) ? '$' . number_format($averageSalary, 0) : 'N/A')
                ->description('Active employees only')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
