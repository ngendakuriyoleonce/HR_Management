<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $clockIn = fake()->dateTimeBetween('-1 day', 'now');
        $clockOut = (clone $clockIn)->modify('+' . fake()->numberBetween(4, 10) . ' hours');

        return [
            'employee_id' => Employee::factory(),
            'date' => fake()->dateTimeBetween('-30 days', 'now'),
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'status' => fake()->randomElement(['present', 'absent', 'late', 'half_day']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function present(): static
    {
        return $this->state(fn () => ['status' => 'present']);
    }

    public function absent(): static
    {
        return $this->state(fn () => ['status' => 'absent', 'clock_in' => null, 'clock_out' => null]);
    }
}
