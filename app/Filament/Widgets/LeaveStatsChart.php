<?php

namespace App\Filament\Widgets;

use App\Models\Leave;
use Filament\Widgets\ChartWidget;

class LeaveStatsChart extends ChartWidget
{
    protected ?string $heading = 'Leave Requests by Status';

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $pending = Leave::where('status', 'pending')->count();
        $approved = Leave::where('status', 'approved')->count();
        $rejected = Leave::where('status', 'rejected')->count();

        return [
            'datasets' => [
                [
                    'data' => [$pending, $approved, $rejected],
                    'backgroundColor' => ['#f59e0b', '#10b981', '#ef4444'],
                ],
            ],
            'labels' => ['Pending', 'Approved', 'Rejected'],
        ];
    }
}
