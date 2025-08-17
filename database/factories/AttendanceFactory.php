<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-30 days', 'now');
        $clockIn = Carbon::parse($date)->setTime(8, $this->faker->numberBetween(0, 30));
        $clockOut = $clockIn->copy()->addHours(8)->addMinutes($this->faker->numberBetween(0, 60));
        $breakMinutes = $this->faker->numberBetween(30, 60);
        $hoursWorked = $clockOut->diffInMinutes($clockIn) / 60;
        $hoursWorked = max(0, $hoursWorked - ($breakMinutes / 60));

        return [
            'user_id' => User::factory(),
            'date' => $date,
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'break_minutes' => $breakMinutes,
            'hours_worked' => round($hoursWorked, 2),
            'status' => $this->faker->randomElement(['present', 'late']),
        ];
    }
}