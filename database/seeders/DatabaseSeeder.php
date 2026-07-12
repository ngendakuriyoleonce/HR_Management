<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(EmployeeLoginSeeder::class);

        $admin = User::where('email', 'hr@example.com')->first();

        $departments = Department::factory()->count(6)->create();

        $employees = Employee::factory()
            ->count(25)
            ->create([
                'department_id' => fn () => $departments->random()->id,
            ]);

        $employees->each(function ($employee) {
            Attendance::factory()
                ->count(rand(5, 15))
                ->create(['employee_id' => $employee->id]);

            Leave::factory()
                ->count(rand(0, 3))
                ->create(['employee_id' => $employee->id]);
        });
    }
}
