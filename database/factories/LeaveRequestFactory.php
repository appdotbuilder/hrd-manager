<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-6 months', '+3 months');
        $daysRequested = $this->faker->numberBetween(1, 10);
        $endDate = (clone $startDate)->modify("+{$daysRequested} days");
        $status = $this->faker->randomElement(['pending', 'approved', 'rejected']);

        return [
            'employee_id' => User::factory(),
            'type' => $this->faker->randomElement(['vacation', 'sick', 'personal', 'emergency']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_requested' => $daysRequested,
            'reason' => $this->faker->optional()->sentence(),
            'status' => $status,
            'approved_by' => $status !== 'pending' ? User::factory() : null,
            'approval_notes' => $status !== 'pending' ? $this->faker->optional()->sentence() : null,
            'approved_at' => $status !== 'pending' ? $this->faker->dateTimeBetween($startDate, 'now') : null,
        ];
    }
}