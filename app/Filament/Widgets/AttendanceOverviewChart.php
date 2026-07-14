<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AttendanceOverviewChart extends ChartWidget
{
    protected ?string $heading = 'Weekly Attendance Overview';

    protected ?string $description = 'Attendance trends for the current week';

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $days = [];
        $present = [];
        $late = [];
        $halfDay = [];

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $days[] = $date->format('D, M d');
            $dayAttendances = Attendance::where('date', $date->toDateString())->get();
            $present[] = $dayAttendances->where('status', 'present')->count();
            $late[] = $dayAttendances->where('status', 'late')->count();
            $halfDay[] = $dayAttendances->where('status', 'half_day')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $present,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
                [
                    'label' => 'Late',
                    'data' => $late,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
                [
                    'label' => 'Half Day',
                    'data' => $halfDay,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $days,
        ];
    }
}
