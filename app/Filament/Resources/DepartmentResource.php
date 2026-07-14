<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static string | \UnitEnum | null $navigationGroup = 'HR Management';

    protected static ?string $navigationLabel = 'Departments';

    protected static ?string $modelLabel = 'Department';

    protected static ?string $pluralModelLabel = 'Departments';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Components\Section::make('Department Information')
                    ->icon('heroicon-o-information-circle')
                    ->description('Basic details about the department')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Department Name')
                            ->required()
                            ->maxLength(255)
                            ->unique(Department::class, 'name', ignoreRecord: true)
                            ->placeholder('e.g. Engineering'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Brief description of the department\'s responsibilities...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Components\Section::make('Management')
                    ->icon('heroicon-o-user-group')
                    ->description('Assign a manager to this department')
                    ->schema([
                        Forms\Components\Select::make('manager_id')
                            ->label('Department Manager')
                            ->relationship('manager')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Select a manager')
                            ->helperText('The manager responsible for this department'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-building-office-2'),

                Tables\Columns\TextColumn::make('employees_count')
                    ->counts('employees')
                    ->label('Employees')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('manager.full_name')
                    ->label('Manager')
                    ->state(fn ($record): string => $record->manager?->full_name ?? 'Unassigned')
                    ->icon('heroicon-o-user')
                    ->color(fn ($record) => $record->manager ? null : 'gray'),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
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
            ->emptyStateHeading('No departments yet')
            ->emptyStateDescription('Create your first department to get started.')
            ->emptyStateIcon('heroicon-o-building-office');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
