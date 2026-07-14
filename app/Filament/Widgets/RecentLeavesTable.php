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

    protected ?string $description = 'Latest leave requests from employees';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Leave::with('employee.department')->latest('start_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->state(fn ($record): string => $record->employee?->full_name ?? '-')
                    ->searchable()
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('employee.department.name')
                    ->label('Department')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'sick' => 'heroicon-o-heart',
                        'vacation' => 'heroicon-o-sun',
                        'personal' => 'heroicon-o-user',
                        default => 'heroicon-o-calendar',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'sick' => 'danger',
                        'vacation' => 'success',
                        'personal' => 'warning',
                        'maternity', 'paternity' => 'info',
                        'unpaid' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('From')
                    ->date('M d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('To')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Days')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->duration_days > 5 => 'danger',
                        $record->duration_days > 2 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (Leave $record): string => $record->status_color),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->poll('60s');
    }
}
