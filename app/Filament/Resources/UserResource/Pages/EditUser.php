<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $employeeData = $data['employee'] ?? [];
        unset($data['employee']);

        $this->record->update($data);

        if (!empty($employeeData)) {
            if ($this->record->employee) {
                $this->record->employee->update($employeeData);
            } else {
                $this->record->employee()->create($employeeData);
            }
        }

        return $data;
    }
}
