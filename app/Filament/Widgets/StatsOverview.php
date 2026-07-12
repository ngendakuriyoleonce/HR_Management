<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $totalDepartments = Department::count();
        $pendingLeaves = Leave::where('status', 'pending')->count();

        $averageSalary = Employee::where('status', 'active')->avg('salary');

        return [
            Stat::make('Total Employees', $totalEmployees)
                ->description('All registered employees')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([$totalEmployees - 3, $totalEmployees - 1, $totalEmployees - 5, $totalEmployees - 2, $totalEmployees - 4, $totalEmployees]),

            Stat::make('Active Employees', $activeEmployees)
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Departments', $totalDepartments)
                ->description('Organizational units')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info'),

            Stat::make('Pending Leaves', $pendingLeaves)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingLeaves > 0 ? 'warning' : 'success'),

            Stat::make('Avg. Salary', filled($averageSalary) ? '$' . number_format($averageSalary, 2) : 'N/A')
                ->description('Active employees')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
