<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AttendanceOverviewChart extends ChartWidget
{
    protected ?string $heading = 'Attendance This Week';

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
        $absent = [];
        $late = [];
        $halfDay = [];

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $days[] = $date->format('D');
            $dayAttendances = Attendance::where('date', $date->toDateString())->get();
            $present[] = $dayAttendances->where('status', 'present')->count();
            $absent[] = $dayAttendances->where('status', 'absent')->count();
            $late[] = $dayAttendances->where('status', 'late')->count();
            $halfDay[] = $dayAttendances->where('status', 'half_day')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $present,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Late',
                    'data' => $late,
                    'backgroundColor' => '#f59e0b',
                ],
                [
                    'label' => 'Half Day',
                    'data' => $halfDay,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Absent',
                    'data' => $absent,
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $days,
        ];
    }
}
