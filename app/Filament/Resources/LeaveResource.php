<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Filament\Forms;
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
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('type')
                    ->options([
                        'sick' => 'Sick Leave',
                        'vacation' => 'Vacation',
                        'personal' => 'Personal',
                        'maternity' => 'Maternity',
                        'paternity' => 'Paternity',
                        'unpaid' => 'Unpaid',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('start_date')
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->afterOrEqual('start_date'),

                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),

                Forms\Components\Select::make('approved_by')
                    ->relationship('approver')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

                Tables\Columns\TextColumn::make('approver.full_name')
                    ->label('Reviewed By')
                    ->state(fn ($record): string => $record->approver?->full_name ?? '-'),

                Tables\Columns\TextColumn::make('employee.department.name')
                    ->label('Department'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'sick' => 'Sick',
                        'vacation' => 'Vacation',
                        'personal' => 'Personal',
                        'maternity' => 'Maternity',
                        'paternity' => 'Paternity',
                        'unpaid' => 'Unpaid',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
