<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string | \UnitEnum | null $navigationGroup = 'HR Management';

    protected static ?string $navigationLabel = 'Leaves';

    protected static ?string $modelLabel = 'Leave Request';

    protected static ?string $pluralModelLabel = 'Leave Requests';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Components\Section::make('Employee & Leave Details')
                    ->icon('heroicon-o-user')
                    ->description('Select the employee and leave information')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->relationship('employee')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name . ' - ' . $record->department?->name)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select an employee'),

                        Forms\Components\Select::make('type')
                            ->label('Leave Type')
                            ->options([
                                'sick' => 'Sick Leave',
                                'vacation' => 'Vacation',
                                'personal' => 'Personal',
                                'maternity' => 'Maternity',
                                'paternity' => 'Paternity',
                                'unpaid' => 'Unpaid',
                            ])
                            ->required()
                            ->placeholder('Select leave type'),
                    ])->columns(2),

                Components\Section::make('Duration')
                    ->icon('heroicon-o-calendar')
                    ->description('Specify the leave period')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->required()
                            ->afterOrEqual('start_date'),
                    ])->columns(2),

                Components\Section::make('Reason')
                    ->icon('heroicon-o-document-text')
                    ->description('Provide a reason for this leave request')
                    ->schema([
                        Forms\Components\Textarea::make('reason')
                            ->required()
                            ->rows(3)
                            ->placeholder('Please describe the reason for this leave request...')
                            ->columnSpanFull(),
                    ]),

                Components\Section::make('Review & Approval')
                    ->icon('heroicon-o-check-circle')
                    ->description('Status and approval details')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),

                        Forms\Components\Select::make('approved_by')
                            ->label('Reviewed By')
                            ->relationship('approver')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Select reviewer'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Reviewer Notes')
                            ->rows(3)
                            ->placeholder('Add any notes or comments about this leave request...')
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

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'sick' => 'heroicon-o-heart',
                        'vacation' => 'heroicon-o-sun',
                        'personal' => 'heroicon-o-user',
                        'maternity', 'paternity' => 'heroicon-o-heart',
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
                    ->date('M d, Y')
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

                Tables\Columns\TextColumn::make('approver.full_name')
                    ->label('Reviewed By')
                    ->state(fn ($record): string => $record->approver?->full_name ?? '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Leave Type')
                    ->options([
                        'sick' => 'Sick',
                        'vacation' => 'Vacation',
                        'personal' => 'Personal',
                        'maternity' => 'Maternity',
                        'paternity' => 'Paternity',
                        'unpaid' => 'Unpaid',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'first_name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('start_date')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From')
                            ->placeholder('Start date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until')
                            ->placeholder('End date'),
                    ])
                    ->query(function ($query, array $data): void {
                        $query
                            ->when($data['from'], fn ($q, $date) => $q->where('start_date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->where('start_date', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = 'From ' . $data['from'];
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = 'Until ' . $data['until'];
                        }
                        return $indicators;
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
            ->emptyStateHeading('No leave requests')
            ->emptyStateDescription('Leave requests from employees will appear here.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}
