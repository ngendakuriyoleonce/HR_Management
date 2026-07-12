<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

class EmployeesByDepartmentChart extends ChartWidget
{
    protected ?string $heading = 'Employees by Department';

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $departments = Department::withCount('employees')->get();

        return [
            'datasets' => [
                [
                    'data' => $departments->pluck('employees_count')->toArray(),
                    'backgroundColor' => [
                        '#f59e0b',
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6',
                        '#ef4444',
                        '#06b6d4',
                        '#ec4899',
                        '#84cc16',
                    ],
                ],
            ],
            'labels' => $departments->pluck('name')->toArray(),
        ];
    }
}
