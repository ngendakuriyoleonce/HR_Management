<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $userData = $data['user'] ?? [];
        unset($data['user']);

        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'role' => $userData['role'],
        ]);

        $data['user_id'] = $user->id;

        return static::getResource()::getModel()::create($data);
    }
}
