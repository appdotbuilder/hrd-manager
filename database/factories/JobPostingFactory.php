<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPosting>
 */
class JobPostingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = ['Engineering', 'Marketing', 'Sales', 'HR', 'Finance', 'Operations'];
        $positions = [
            'Engineering' => ['Senior Developer', 'Frontend Developer', 'Backend Developer', 'DevOps Engineer'],
            'Marketing' => ['Marketing Manager', 'Content Creator', 'Digital Marketing Specialist'],
            'Sales' => ['Sales Representative', 'Account Manager', 'Business Development'],
            'HR' => ['HR Generalist', 'Recruiter', 'HR Manager'],
            'Finance' => ['Accountant', 'Financial Analyst', 'Controller'],
            'Operations' => ['Operations Manager', 'Project Manager', 'Coordinator'],
        ];

        $department = $this->faker->randomElement($departments);
        $title = $this->faker->randomElement($positions[$department] ?? ['Specialist', 'Coordinator']);

        return [
            'title' => $title,
            'department' => $department,
            'description' => $this->faker->paragraphs(3, true),
            'requirements' => $this->faker->paragraphs(2, true),
            'salary_min' => $this->faker->numberBetween(40000, 80000),
            'salary_max' => $this->faker->numberBetween(80000, 120000),
            'employment_type' => $this->faker->randomElement(['full_time', 'part_time', 'contract']),
            'location' => $this->faker->randomElement(['Remote', 'New York, NY', 'San Francisco, CA', 'Chicago, IL']),
            'status' => $this->faker->randomElement(['draft', 'published', 'closed']),
            'application_deadline' => $this->faker->optional()->dateTimeBetween('now', '+3 months'),
            'created_by' => User::factory(),
        ];
    }
}