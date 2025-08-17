<?php

namespace Database\Factories;

use App\Models\JobPosting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_posting_id' => JobPosting::factory(),
            'candidate_name' => $this->faker->name(),
            'candidate_email' => $this->faker->unique()->safeEmail(),
            'candidate_phone' => $this->faker->phoneNumber(),
            'cover_letter' => $this->faker->optional()->paragraphs(2, true),
            'resume_path' => $this->faker->optional()->filePath(),
            'status' => $this->faker->randomElement(['pending', 'reviewing', 'interview', 'rejected', 'hired']),
            'notes' => $this->faker->optional()->paragraph(),
            'applied_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}