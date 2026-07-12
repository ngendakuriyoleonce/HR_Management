<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $userData = $data['user'] ?? [];
        unset($data['user']);

        $this->record->update($data);

        if ($this->record->user) {
            $updateData = [
                'email' => $userData['email'] ?? $this->record->user->email,
                'role' => $userData['role'] ?? $this->record->user->role,
            ];

            if (!empty($userData['password'])) {
                $updateData['password'] = $userData['password'];
            }

            $this->record->user->update($updateData);
        }

        return $data;
    }
}
