<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'user_id' => null,
            'employee_id' => strtoupper(fake()->bothify('EMP-####')),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'department_id' => Department::factory(),
            'position' => fake()->jobTitle(),
            'salary' => fake()->randomFloat(2, 35000, 120000),
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'date_of_birth' => fake()->dateTimeBetween('-50 years', '-20 years'),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'status' => 'active',
            'avatar' => fake()->optional()->imageUrl(200, 200, 'people'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}
