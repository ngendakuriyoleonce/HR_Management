<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static string | \UnitEnum | null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Account Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->options([
                                'employee' => 'Employee',
                                'manager' => 'Manager',
                                'hr' => 'HR Admin',
                            ])
                            ->required()
                            ->default('employee'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Employee Profile')
                    ->schema([
                        Forms\Components\TextInput::make('employee.first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('employee.last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('employee.employee_id')
                            ->label('Employee ID')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => 'EMP-' . str_pad(Employee::max('id') + 1, 4, '0', STR_PAD_LEFT))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('employee.phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\Select::make('employee.department_id')
                            ->label('Department')
                            ->options(fn () => Department::pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('employee.position')
                            ->label('Position')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('employee.salary')
                            ->label('Salary')
                            ->numeric()
                            ->prefix('$'),

                        Forms\Components\DatePicker::make('employee.hire_date')
                            ->label('Hire Date')
                            ->required(),

                        Forms\Components\Select::make('employee.status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'on_leave' => 'On Leave',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hr' => 'danger',
                        'manager' => 'warning',
                        'employee' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('employee.employee_id')
                    ->label('Employee ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('employee.department.name')
                    ->label('Department'),

                Tables\Columns\TextColumn::make('employee.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'on_leave' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'employee' => 'Employee',
                        'manager' => 'Manager',
                        'hr' => 'HR Admin',
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
