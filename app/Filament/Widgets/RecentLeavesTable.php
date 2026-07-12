<?php

namespace App\Filament\Widgets;

use App\Models\Leave;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentLeavesTable extends TableWidget
{
    protected static ?string $heading = 'Recent Leave Requests';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Leave::with('employee')->latest('start_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->state(fn ($record): string => $record->employee?->full_name ?? '-'),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sick' => 'danger',
                        'vacation' => 'success',
                        'personal' => 'warning',
                        'maternity', 'paternity' => 'info',
                        'unpaid' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Days')
                    ->suffix(' days'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (Leave $record): string => $record->status_color),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->poll('60s');
    }
}
