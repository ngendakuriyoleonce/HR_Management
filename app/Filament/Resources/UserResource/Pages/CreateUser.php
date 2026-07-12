<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Employee;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $employeeData = $data['employee'] ?? [];
        unset($data['employee']);

        $user = static::getResource()::getModel()::create($data);

        if (!empty($employeeData)) {
            $user->employee()->create($employeeData);
        }

        return $user;
    }
}
