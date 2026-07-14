<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clock';

    protected static string | \UnitEnum | null $navigationGroup = 'HR Management';

    protected static ?string $navigationLabel = 'Attendance';

    protected static ?string $modelLabel = 'Attendance';

    protected static ?string $pluralModelLabel = 'Attendance Records';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Components\Section::make('Employee Information')
                    ->icon('heroicon-o-user')
                    ->description('Select the employee for this attendance record')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->relationship('employee')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name . ' - ' . $record->department?->name)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select an employee'),
                    ]),

                Components\Section::make('Attendance Details')
                    ->icon('heroicon-o-calendar-days')
                    ->description('Date and clock in/out times')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Date')
                            ->required()
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('clock_in')
                            ->label('Clock In Time')
                            ->seconds(false)
                            ->format('h:i A'),

                        Forms\Components\DateTimePicker::make('clock_out')
                            ->label('Clock Out Time')
                            ->seconds(false)
                            ->format('h:i A'),
                    ])->columns(3),

                Components\Section::make('Status & Notes')
                    ->icon('heroicon-o-information-circle')
                    ->description('Attendance status and additional notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'present' => 'Present',
                                'absent' => 'Absent',
                                'late' => 'Late',
                                'half_day' => 'Half Day',
                            ])
                            ->required()
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'present' => 'success',
                                'late' => 'warning',
                                'half_day' => 'info',
                                'absent' => 'danger',
                                default => 'gray',
                            }),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Any additional notes about this attendance record...')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->state(fn ($record): string => $record->employee?->full_name ?? '-')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('employee.department.name')
                    ->label('Department')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date('D, M d, Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),

                Tables\Columns\TextColumn::make('clock_in')
                    ->label('Clock In')
                    ->dateTime('h:i A')
                    ->sortable()
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success'),

                Tables\Columns\TextColumn::make('clock_out')
                    ->label('Clock Out')
                    ->dateTime('h:i A')
                    ->sortable()
                    ->icon('heroicon-o-arrow-left-circle')
                    ->color(fn ($record) => $record->clock_out ? 'danger' : 'gray'),

                Tables\Columns\TextColumn::make('hours_worked')
                    ->label('Hours')
                    ->badge()
                    ->suffix('h')
                    ->color(fn ($record) => match (true) {
                        $record->hours_worked >= 8 => 'success',
                        $record->hours_worked >= 4 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'present' => 'heroicon-o-check-circle',
                        'late' => 'heroicon-o-clock',
                        'half_day' => 'heroicon-o-minus-circle',
                        'absent' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (Attendance $record): string => $record->status_color),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recorded')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                        'half_day' => 'Half Day',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'first_name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('date')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('From')
                            ->placeholder('Start date'),
                        Forms\Components\DatePicker::make('date_to')
                            ->label('Until')
                            ->placeholder('End date'),
                    ])
                    ->query(function ($query, array $data): void {
                        $query
                            ->when($data['date_from'], fn ($q, $date) => $q->where('date', '>=', $date))
                            ->when($data['date_to'], fn ($q, $date) => $q->where('date', '<=', $date));
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
                Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No attendance records')
            ->emptyStateDescription('Attendance records will appear here as employees clock in and out.')
            ->emptyStateIcon('heroicon-o-clock');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
