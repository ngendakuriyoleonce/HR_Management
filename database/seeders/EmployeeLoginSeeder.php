<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeLoginSeeder extends Seeder
{
    public function run(): void
    {
        $engineering = Department::firstOrCreate(
            ['name' => 'Engineering'],
            ['description' => 'Engineering department']
        );

        $marketing = Department::firstOrCreate(
            ['name' => 'Marketing'],
            ['description' => 'Marketing department']
        );

        // HR Admin
        $hrUser = User::create([
            'name' => 'HR Admin',
            'email' => 'hr@example.com',
            'password' => bcrypt('password'),
            'role' => 'hr',
        ]);

        Employee::create([
            'user_id' => $hrUser->id,
            'employee_id' => 'EMP000',
            'first_name' => 'HR',
            'last_name' => 'Admin',
            'email' => 'hr@example.com',
            'phone' => '555-0000',
            'department_id' => $engineering->id,
            'position' => 'HR Administrator',
            'salary' => 85000.00,
            'hire_date' => now()->subYears(3),
            'status' => 'active',
        ]);

        // Employee
        $empUser = User::create([
            'name' => 'John Employee',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        Employee::create([
            'user_id' => $empUser->id,
            'employee_id' => 'EMP001',
            'first_name' => 'John',
            'last_name' => 'Employee',
            'email' => 'employee@example.com',
            'phone' => '555-0100',
            'department_id' => $engineering->id,
            'position' => 'Software Developer',
            'salary' => 75000.00,
            'hire_date' => now()->subYear(),
            'date_of_birth' => '1990-05-15',
            'status' => 'active',
        ]);

        // Manager
        $mgrUser = User::create([
            'name' => 'Jane Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        $manager = Employee::create([
            'user_id' => $mgrUser->id,
            'employee_id' => 'MGR001',
            'first_name' => 'Jane',
            'last_name' => 'Manager',
            'email' => 'manager@example.com',
            'phone' => '555-0200',
            'department_id' => $engineering->id,
            'position' => 'Engineering Manager',
            'salary' => 95000.00,
            'hire_date' => now()->subYears(2),
            'date_of_birth' => '1988-03-22',
            'status' => 'active',
        ]);

        $engineering->update(['manager_id' => $manager->id]);

        $this->command->info('HR Admin login:');
        $this->command->info('  Email: hr@example.com');
        $this->command->info('  Password: password');
        $this->command->info('  Role: hr');
        $this->command->info('');
        $this->command->info('Employee login:');
        $this->command->info('  Email: employee@example.com');
        $this->command->info('  Password: password');
        $this->command->info('  Role: employee');
        $this->command->info('');
        $this->command->info('Manager login:');
        $this->command->info('  Email: manager@example.com');
        $this->command->info('  Password: password');
        $this->command->info('  Role: manager');
    }
}
