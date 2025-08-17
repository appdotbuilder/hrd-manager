<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerformanceReview>
 */
class PerformanceReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-2 years', '-6 months');
        $endDate = (clone $startDate)->modify('+1 year');
        $status = $this->faker->randomElement(['draft', 'completed', 'acknowledged']);

        return [
            'employee_id' => User::factory(),
            'reviewer_id' => User::factory(),
            'review_period_start' => $startDate,
            'review_period_end' => $endDate,
            'performance_score' => $this->faker->numberBetween(1, 10),
            'goals_achieved' => $this->faker->optional()->paragraph(),
            'areas_for_improvement' => $this->faker->optional()->paragraph(),
            'manager_comments' => $this->faker->optional()->paragraph(),
            'employee_comments' => $this->faker->optional()->paragraph(),
            'status' => $status,
            'completed_at' => $status !== 'draft' ? $this->faker->dateTimeBetween($endDate, 'now') : null,
        ];
    }
}